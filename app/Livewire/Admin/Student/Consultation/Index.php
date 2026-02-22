<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\Student;
use App\Models\AdvisingSession;
use App\Models\AdvisingPreSession;
use App\Services\NotificationService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Database\Eloquent\Builder;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
class Index extends Component
{

    use WithPagination, SEOTools;

    public $search = '';

    // Modal 1: Student Selection
    public $showStudentSelectModal = false;
    public $studentSearch = '';
    public $selectedStudents = [];

    // Modal 2: Schedule Configuration
    public $showScheduleModal = false;
    public $selectedDay = '';
    public $selectedHour = '08';
    public $selectedMinute = '00';
    public $autoLocationType = 'online';
    public $autoSkyroomLink = '';

    public function mount()
    {
        // دریافت پارامتر course_id از URL
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('دانش آموزان');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

// ==================== Modal 1: Student Selection ====================

    public function openStudentSelectModal()
    {
        $this->selectedStudents = [];
        $this->studentSearch = '';
        $this->showStudentSelectModal = true;
    }

    public function closeStudentSelectModal()
    {
        $this->showStudentSelectModal = false;
        $this->selectedStudents = [];
    }

    public function toggleStudentSelection($studentId)
    {
        $studentId = (int) $studentId;
        if (in_array($studentId, $this->selectedStudents)) {
            $this->selectedStudents = array_values(array_diff($this->selectedStudents, [$studentId]));
        } else {
            $this->selectedStudents[] = $studentId;
        }
    }

    public function proceedToSchedule()
    {
        if (empty($this->selectedStudents)) {
            $this->dispatch('warning', 'لطفاً حداقل یک دانش‌آموز انتخاب کنید.');
            return;
        }
        $this->showStudentSelectModal = false;
        $this->selectedDay = '';
        $this->selectedHour = '08';
        $this->selectedMinute = '00';
        $this->autoLocationType = 'online';
        $this->autoSkyroomLink = '';
        $this->showScheduleModal = true;
    }

    // ==================== Modal 2: Schedule Config ====================

    public function closeScheduleModal()
    {
        $this->showScheduleModal = false;
    }

    public function backToStudentSelect()
    {
        $this->showScheduleModal = false;
        $this->showStudentSelectModal = true;
    }

    public function createAutoSessions()
    {
        $this->validate([
            'selectedDay'     => 'required|in:0,1,2,3,4,5,6',
            'selectedHour'    => 'required|integer|between:0,23',
            'selectedMinute'  => 'required|integer|between:0,59',
            'autoLocationType' => 'required|in:in_person,online',
        ], [
            'selectedDay.required'      => 'انتخاب روز هفته الزامی است.',
            'selectedDay.in'            => 'روز انتخابی معتبر نیست.',
            'selectedHour.required'     => 'ساعت الزامی است.',
            'selectedMinute.required'   => 'دقیقه الزامی است.',
            'autoLocationType.required' => 'محل برگزاری الزامی است.',
        ]);

        if ($this->autoLocationType === 'online' && empty($this->autoSkyroomLink)) {
            $this->addError('autoSkyroomLink', 'لینک جلسه آنلاین الزامی است.');
            return;
        }

        $sessionTime = sprintf('%02d:%02d', (int)$this->selectedHour, (int)$this->selectedMinute);
        $targetDay = (int) $this->selectedDay;

        // Find the next occurrence of the selected weekday (including today if it matches)
        $today = Carbon::today();
        $daysUntilTarget = ($targetDay - $today->dayOfWeek + 7) % 7;
        $firstDate = $today->copy()->addDays($daysUntilTarget);

        $adminId = auth()->id();
        $totalCreated = 0;
        $studentCount = count($this->selectedStudents);

        foreach ($this->selectedStudents as $studentId) {
            $student = Student::with('user.personalInformation')->find($studentId);
            if (!$student) continue;

            for ($i = 0; $i < 4; $i++) {
                $sessionDate = $firstDate->copy()->addWeeks($i);
                $jalali = Jalalian::fromCarbon($sessionDate);

                $yearShort = substr((string) $jalali->getYear(), -2);
                $month     = str_pad((string) $jalali->getMonth(), 2, '0', STR_PAD_LEFT);
                $day       = str_pad((string) $jalali->getDay(), 2, '0', STR_PAD_LEFT);
                $title = $yearShort . $month . $day;

                $session = AdvisingSession::create([
                    'student_id'   => $student->id,
                    'advisor_id'   => $adminId,
                    'title'        => $title,
                    'description'  => 'جلسه مشاوره فردی',
                    'activation_date' => $sessionDate->format('Y-m-d'),
                    'session_time' => $sessionTime,
                    'location_type' => $this->autoLocationType,
                    'skyroom_link' => $this->autoLocationType === 'online' ? $this->autoSkyroomLink : null,
                    'status'       => 'inactive',
                    'is_active'    => false,
                ]);

                AdvisingPreSession::create([
                    'advising_session_id' => $session->id,
                    'student_id'          => $student->id,
                    'title'               => $title,
                    'status'              => 'pending',
                ]);

                $totalCreated++;
            }

            $this->sendAutoSessionNotification($student, $firstDate, $sessionTime);
        }

        $this->showScheduleModal = false;
        $this->selectedStudents = [];
        $this->dispatch('success', "{$totalCreated} جلسه برای {$studentCount} دانش‌آموز با موفقیت ثبت شد.");
    }

    protected function sendAutoSessionNotification(Student $student, Carbon $firstDate, string $sessionTime): void
    {
        if (!$student->user) return;

        $name = $student->user->personalInformation->name ?? 'دانش آموز';
        $lastName = $student->user->personalInformation->name_full ?? '';
        $studentName = trim($name . ' ' . $lastName);

        $jalaliDate = Jalalian::fromCarbon($firstDate)->format('Y/m/d');

        $message = "{$studentName} عزیز\n۴ جلسه مشاوره فردی از تاریخ {$jalaliDate} در ساعت {$sessionTime} برای شما ثبت شد.\nبرای مشاهده به اتاق مشاوره مراجعه کنید.\nبا تشکر";

        NotificationService::sendToStudent($student->id, 'جلسات مشاوره جدید', $message);
    }

    public function render()
    {
        $adminId = auth()->id();
        $studentsQuery = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation',
                'user.profile'
            ])
            ->where('supporter_id', $adminId);

        // اگر جستجو فعال بود
        if ($this->search) {
            $studentsQuery->whereHas('user.personalInformation', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        $students = $studentsQuery->paginate(10);

        // Students for the auto-session modal (all, with search across multiple fields)
        $modalStudentsQuery = Student::query()
            ->with(['user.personalInformation', 'user.profile'])
            ->where('supporter_id', $adminId);

        if ($this->studentSearch) {
            $modalStudentsQuery->where(function ($q) {
                $q->whereHas('user.personalInformation', function ($q2) {
                    $q2->where('name', 'like', '%' . $this->studentSearch . '%')
                        ->orWhere('name_full', 'like', '%' . $this->studentSearch . '%');
                })->orWhereHas('user', function ($q2) {
                    $q2->where('mobile', 'like', '%' . $this->studentSearch . '%');
                })->orWhereHas('user.profile', function ($q2) {
                    $q2->where('full_name', 'like', '%' . $this->studentSearch . '%');
                });
            });
        }

        $modalStudents = $modalStudentsQuery->get();

        return view('livewire.admin.student.consultation.index', [
            'students'      => $students,
            'modalStudents' => $modalStudents,
        ])->layout('layouts.admin.app');    }
}
