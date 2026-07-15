<?php

namespace App\Livewire\Client\Profile\Consultation;

use App\Models\AdvisingSession;
use App\Models\AdminWorkSchedule;
use App\Models\WeeklyProgram;
use App\Models\Student;
use App\Services\ExamPlanningService;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class SessionList extends Component
{
    use WithPagination, SEOTools;

    public $showPreSessionModal = false;
    public $selectedSession = null;

    // ── جابجاییِ جلسه (سلف‌سرویس) ──────────────────────────────
    public bool $showRescheduleModal = false;
    public ?int $rescheduleSessionId = null;
    public $rescheduleNewDay = null;

    public function mount()
    {
        $this->seo()->setTitle('اتاق مشاوره');
    }

    /**
     * نخستین تاریخِ پس از $afterDate که روزِ هفته‌ی ایرانی‌اش $persianDay باشد.
     */
    protected function nextDateForPersianDay(int $persianDay, Carbon $afterDate): Carbon
    {
        $d = $afterDate->copy()->addDay();
        for ($i = 0; $i < 7; $i++) {
            if ((($d->dayOfWeek + 1) % 7) === $persianDay) {
                return $d;
            }
            $d->addDay();
        }
        return $d;
    }

    public function openReschedule(int $sessionId): void
    {
        $student = auth()->user()?->student;
        $session = AdvisingSession::find($sessionId);
        if (! $student || ! $session || $session->student_id !== $student->id) {
            return;
        }
        if (! $session->canFillPreSession() || $session->result_status !== null) {
            $this->dispatch('warning', 'امکان جابجاییِ این جلسه وجود ندارد.');
            return;
        }

        $this->rescheduleSessionId = $sessionId;
        $this->rescheduleNewDay = null;
        $this->showRescheduleModal = true;
    }

    public function closeReschedule(): void
    {
        $this->showRescheduleModal = false;
        $this->rescheduleSessionId = null;
        $this->rescheduleNewDay = null;
    }

    public function submitReschedule(): void
    {
        $student = auth()->user()?->student;
        $session = $this->rescheduleSessionId ? AdvisingSession::find($this->rescheduleSessionId) : null;

        if (! $student || ! $session || $session->student_id !== $student->id) {
            $this->closeReschedule();
            return;
        }
        if (! $session->canFillPreSession() || $session->result_status !== null) {
            $this->dispatch('warning', 'امکان جابجاییِ این جلسه وجود ندارد.');
            $this->closeReschedule();
            return;
        }

        $day = $this->rescheduleNewDay;
        if ($day === null || $day === '' || (int) $day < 0 || (int) $day > 6) {
            $this->dispatch('warning', 'روزِ جدید را انتخاب کنید.');
            return;
        }

        // مبنا: دیرترِ بینِ تاریخِ جلسه‌ی فعلی و امروز
        $base = Carbon::parse($session->activation_date);
        if ($base->lt(Carbon::today())) {
            $base = Carbon::today();
        }
        $makeupDate = $this->nextDateForPersianDay((int) $day, $base);

        // جلسه‌ی فعلی: غیبتِ دانش‌آموز
        $session->update([
            'result_status' => AdvisingSession::RESULT_STUDENT_ABSENT,
            'status'        => AdvisingSession::STATUS_COMPLETED,
        ]);

        // ساختِ جلسه‌ی جبرانی بدونِ ساعت (مشاور یک روز قبل ساعتش را تعیین می‌کند)
        AdvisingSession::create([
            'student_id'        => $student->id,
            'advisor_id'        => $session->advisor_id,
            'title'             => 'جلسه جبرانی',
            'description'       => 'جلسه جبرانی (جابجایی توسط دانش‌آموز)',
            'activation_date'   => $makeupDate->toDateString(),
            'session_time'      => null,
            'location_type'     => AdvisingSession::LOCATION_ONLINE,
            'status'            => AdvisingSession::STATUS_INACTIVE,
            'is_active'         => false,
            'finalized'         => false,
            'is_makeup'         => true,
            'makeup_reason'     => AdvisingSession::MAKEUP_STUDENT_RESCHEDULE,
            'source_session_id' => $session->id,
        ]);

        $this->closeReschedule();
        $this->dispatch('success', 'درخواستِ جابجایی ثبت شد. جلسه‌ی جبرانی تعیین شد و مشاور ساعتِ آن را اعلام می‌کند.');
    }

    public function openPreSessionModal($sessionId)
    {
        $session = AdvisingSession::with('preSession')->find($sessionId);
        if ($session && $session->canFillPreSession()) {
            $this->selectedSession = $session;
            $this->showPreSessionModal = true;
        } else {
            $this->dispatch('warning', 'امکان ویرایش پیش‌جلسه وجود ندارد. زمان برگزاری جلسه فرا رسیده است.');
        }
    }

    public function closePreSessionModal()
    {
        $this->showPreSessionModal = false;
        $this->selectedSession = null;
    }

    public function confirmStartPreSession()
    {
        if ($this->selectedSession) {
            return redirect()->route('client.profile.consultation.pre-session', [
                'session' => $this->selectedSession->id
            ]);
        }
    }

    public function render()
    {
        $user    = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        $hideForExamProgramTrialStudent = app(ExamPlanningService::class)
            ->shouldHideTrialExamProgramSections($user);

        if ($hideForExamProgramTrialStudent) {
            return view('livewire.client.profile.consultation.session-list', [
                'sessions'         => collect(),
                'weeklyPrograms'   => collect(),
                'student'          => $student,
                'lockedSessionIds' => [],
                'weekDays'         => AdminWorkSchedule::DAYS,
                'hideForExamProgramTrialStudent' => true,
            ])->layout('layouts.client.app');
        }

        $sessions        = collect();
        $weeklyPrograms  = collect();
        $lockedSessionIds = [];

        if ($student) {
            // ترتیب صعودی برای تشخیص قفل بودن جلسات
            $allSessionsOrdered = AdvisingSession::where('student_id', $student->id)
                ->where('finalized', true)
                ->orderBy('activation_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($allSessionsOrdered as $index => $session) {
                if ($index === 0) continue;
                $prev = $allSessionsOrdered[$index - 1];
                if ($prev->result_status === null) {
                    $lockedSessionIds[] = $session->id;
                }
            }

            // ۱۰ جلسه آخر — جدیدترین اول
            $sessions = AdvisingSession::where('student_id', $student->id)
                ->where('finalized', true)
                ->with(['preSession', 'advisor', 'weeklyProgram'])
                ->orderBy('activation_date', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(10);

            foreach ($sessions as $session) {
                $session->activateIfNeeded();
            }

            $weeklyPrograms = WeeklyProgram::where('student_id', $student->id)
                ->with('parts')
                ->where('is_active', true)
                ->orderBy('start_date', 'desc')
                ->get();
        }

        return view('livewire.client.profile.consultation.session-list', [
            'sessions'         => $sessions,
            'weeklyPrograms'   => $weeklyPrograms,
            'student'          => $student,
            'lockedSessionIds' => $lockedSessionIds,
            'weekDays'         => AdminWorkSchedule::DAYS,
            'hideForExamProgramTrialStudent' => false,
        ])->layout('layouts.client.app');
    }
}
