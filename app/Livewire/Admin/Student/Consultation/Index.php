<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\Student;
use App\Models\AdvisingSession;
use App\Models\AdvisingPreSession;
use App\Services\NotificationService;
use Artesaos\SEOTools\Traits\SEOTools;

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

    // Modal 2: Schedule Configuration (per-student)
    public $showScheduleModal = false;

    public $studentSchedules = []; // keyed by student ID

    public function mount()
    {
        // دریافت پارامتر course_id از URL
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('اتاق مشاوره');
    }

    // ==================== Modal 1: Student Selection ====================

    public function openStudentSelectModal()
    {
        $this->selectedStudents = [];
        $this->studentSearch    = '';
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
            // Remove their schedule entry too
            unset($this->studentSchedules[$studentId]);
        } else {
            $this->selectedStudents[] = $studentId;
            // Initialize schedule entry with defaults
            $this->studentSchedules[$studentId] = [
                'day'           => (string) $this->selectedDay,
                'hour'          => '08',
                'minute'        => '00',
                'location_type' => 'online',
                'skyroom_link'  => '',
            ];
        }
    }

    public function proceedToSchedule()
    {
        if (empty($this->selectedStudents)) {
            $this->dispatch('warning', 'لطفاً حداقل یک دانش‌آموز انتخاب کنید.');
            return;
        }


        $this->showStudentSelectModal = false;
        $this->showScheduleModal      = true;
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
        // Build per-student validation rules
        $rules    = [];
        $messages = [];
        foreach ($this->selectedStudents as $studentId) {
            $student = Student::with('user.personalInformation')->find($studentId);
            $name = $student?->user?->personalInformation?->name ?? "دانش‌آموز {$studentId}";
            $rules["studentSchedules.{$studentId}.day"]           = 'required|in:0,1,2,3,4,5,6';
            $rules["studentSchedules.{$studentId}.hour"]          = 'required|integer|min:0|max:23';
            $rules["studentSchedules.{$studentId}.minute"]        = 'required|integer|min:0|max:59';
            $rules["studentSchedules.{$studentId}.location_type"] = 'required|in:in_person,online';

            $schedule = $this->studentSchedules[$studentId] ?? [];
            if (($schedule['location_type'] ?? 'online') === 'online') {
                $rules["studentSchedules.{$studentId}.skyroom_link"] = 'required|url';
                $messages["studentSchedules.{$studentId}.skyroom_link.required"] = "لینک جلسه آنلاین برای {$name} الزامی است.";
                $messages["studentSchedules.{$studentId}.skyroom_link.url"]      = "لینک وارد شده برای {$name} معتبر نیست.";
            }
            $messages["studentSchedules.{$studentId}.day.required"]          = "روز جلسه برای {$name} الزامی است.";
            $messages["studentSchedules.{$studentId}.day.in"]                = "روز انتخابی برای {$name} معتبر نیست.";
            $messages["studentSchedules.{$studentId}.hour.required"]         = "ساعت جلسه برای {$name} الزامی است.";
            $messages["studentSchedules.{$studentId}.hour.min"]              = "ساعت باید بین ۰ تا ۲۳ باشد ({$name}).";
            $messages["studentSchedules.{$studentId}.hour.max"]              = "ساعت باید بین ۰ تا ۲۳ باشد ({$name}).";
            $messages["studentSchedules.{$studentId}.hour.integer"]          = "ساعت وارد شده برای {$name} نامعتبر است.";
            $messages["studentSchedules.{$studentId}.minute.required"]       = "دقیقه جلسه برای {$name} الزامی است.";
            $messages["studentSchedules.{$studentId}.minute.min"]            = "دقیقه باید بین ۰ تا ۵۹ باشد ({$name}).";
            $messages["studentSchedules.{$studentId}.minute.max"]            = "دقیقه باید بین ۰ تا ۵۹ باشد ({$name}).";
            $messages["studentSchedules.{$studentId}.minute.integer"]        = "دقیقه وارد شده برای {$name} نامعتبر است.";
            $messages["studentSchedules.{$studentId}.location_type.required"] = "محل برگزاری جلسه برای {$name} الزامی است.";
            $messages["studentSchedules.{$studentId}.location_type.in"]       = "محل برگزاری جلسه برای {$name} نامعتبر است.";
        }
        $this->validate($rules, $messages);

        $adminId      = auth()->id();
        $today        = Carbon::today();
        $totalCreated = 0;
        $studentCount = count($this->selectedStudents);

        foreach ($this->selectedStudents as $studentId) {
            $student = Student::with('user.personalInformation')->find($studentId);
            if (!$student) continue;

            $schedule     = $this->studentSchedules[$studentId];
            $targetDay    = (int) $schedule['day'];
            $sessionTime  = sprintf('%02d:%02d', (int)$schedule['hour'], (int)$schedule['minute']);
            $locationType = $schedule['location_type'];
            $skyroomLink  = $locationType === 'online' ? $schedule['skyroom_link'] : null;

            $daysUntilTarget = ($targetDay - $today->dayOfWeek + 7) % 7;
            $firstDate       = $today->copy()->addDays($daysUntilTarget);

            for ($i = 0; $i < 4; $i++) {
                $sessionDate = $firstDate->copy()->addWeeks($i);
                $jalali      = Jalalian::fromCarbon($sessionDate);

                $yearShort = substr((string) $jalali->getYear(), -2);
                $month     = str_pad((string) $jalali->getMonth(), 2, '0', STR_PAD_LEFT);
                $day       = str_pad((string) $jalali->getDay(), 2, '0', STR_PAD_LEFT);
                $title     = $yearShort . $month . $day;

                $session = AdvisingSession::create([
                    'student_id'      => $student->id,
                    'advisor_id'      => $adminId,
                    'title'           => $title,
                    'description'     => 'جلسه مشاوره فردی',
                    'activation_date' => $sessionDate->format('Y-m-d'),
                    'session_time'    => $sessionTime,
                    'location_type'   => $locationType,
                    'skyroom_link'    => $skyroomLink,
                    'status'          => 'inactive',
                    'is_active'       => false,
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
        $this->selectedStudents  = [];
        $this->studentSchedules  = [];
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

        // لیست اصلی: همه دانش‌آموزان این مشاور با قابلیت جستجو
        $studentsQuery = Student::query()
            ->with([
                'user.profile',
                'user.personalInformation',
                'payment.order.user',
                'advisingSessions' => function ($q) {
                    $q->orderBy('activation_date');
                },
            ])
            ->where('advisor_id', $adminId);

        if ($this->search) {
            $studentsQuery->where(function ($q) {
                $q->whereHas('user.personalInformation', function ($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('name_full', 'like', '%' . $this->search . '%');
                })->orWhereHas('user', function ($q2) {
                    $q2->where('mobile', 'like', '%' . $this->search . '%');
                })->orWhereHas('user.profile', function ($q2) {
                    $q2->where('full_name', 'like', '%' . $this->search . '%');
                });
            });
        }

        $allStudents = $studentsQuery->get();

        $totalStudentCount = Student::where('advisor_id', $adminId)->count();

        // دانش‌آموزان مودال: فقط آنهایی که هیچ جلسه‌ای ندارند
        $modalStudentsQuery = Student::query()
            ->with(['user.personalInformation', 'user.profile'])
            ->where('advisor_id', $adminId)
            ->whereDoesntHave('advisingSessions');

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
            'allStudents'       => $allStudents,
            'modalStudents'     => $modalStudents,
            'totalStudentCount' => $totalStudentCount,
        ])->layout('layouts.admin.app');
    }
}
