<?php

namespace App\Livewire\Client\Profile\Appointment;

use App\Models\SessionRescheduleRequest;
use App\Models\Student;
use App\Models\StudentSchedulePreference;
use App\Models\StudentSchedulePreferenceTime;
use App\Models\AdvisingSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public const ALLOWED_DAYS      = [0, 1, 2, 3, 4, 5, 6];
    public const SLOT_START_HOUR   = 8;
    public const SLOT_END_HOUR     = 21; // end exclusive (valid start hours: 8..20)
    public const MAX_YEARLY_CHANGES = 2;

    /**
     * آرایه [day => ['enabled'=>bool, 'start_time'=>'HH:MM', 'end_time'=>'HH:MM']]
     */
    public array $slots = [];

    public string $notes = '';

    // فرم جابجایی
    public bool $showRescheduleForm = false;
    public string $rescheduleType = 'exception'; // exception | permanent
    public ?int $rescheduleOriginalSessionId = null;
    public ?int $rescheduleOriginalDay = null;
    public ?string $rescheduleOriginalTime = null;
    public ?int $rescheduleProposedDay = null;
    public ?string $rescheduleProposedTime = null;
    public string $rescheduleDescription = '';

    public function mount(): void
    {
        $prefs = $this->existingPreference();
        $existingTimes = $prefs?->times->keyBy('day_of_week');

        foreach (self::ALLOWED_DAYS as $day) {
            $row = $existingTimes?->get($day);
            $this->slots[$day] = [
                'enabled'    => (bool) $row,
                'start_time' => $row ? substr($row->start_time, 0, 5) : '16:00',
                'end_time'   => $row ? substr($row->end_time, 0, 5)   : '18:00',
            ];
        }
        $this->notes = $prefs?->student_notes ?? '';
    }

    protected function student(): ?Student
    {
        return Auth::user()?->student;
    }

    protected function existingPreference(): ?StudentSchedulePreference
    {
        $student = $this->student();
        if (!$student) {
            return null;
        }
        return $student->schedulePreferences()
            ->with('times')
            ->latest()
            ->first();
    }

    public function save(): void
    {
        $student = $this->student();
        if (!$student) {
            session()->flash('error', 'برای انجام این کار باید وارد حساب شده باشید.');
            return;
        }

        $selected = collect($this->slots)->filter(fn ($s) => !empty($s['enabled']));
        if ($selected->isEmpty()) {
            $this->addError('slots', 'حداقل یک روز را انتخاب کنید.');
            return;
        }

        // اعتبارسنجی هر اسلات
        foreach ($selected as $day => $slot) {
            $start = $slot['start_time'] ?? null;
            $end   = $slot['end_time']   ?? null;
            if (!preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $start)
                || !preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $end)) {
                $this->addError("slots.$day", 'فرمت ساعت نامعتبر است.');
                return;
            }
            if ($start >= $end) {
                $this->addError("slots.$day", 'ساعت پایان باید بعد از ساعت شروع باشد.');
                return;
            }
            // محدوده 8 تا 21
            $startHour = (int) substr($start, 0, 2);
            $endHour   = (int) substr($end, 0, 2);
            $endMin    = (int) substr($end, 3, 2);
            if ($startHour < self::SLOT_START_HOUR || $endHour > self::SLOT_END_HOUR
                || ($endHour === self::SLOT_END_HOUR && $endMin > 0)) {
                $this->addError("slots.$day", 'بازه‌ی مجاز از ۸ صبح تا ۲۱ است.');
                return;
            }
        }

        // چک محدودیت تغییر 2 بار در سال
        $current = $this->existingPreference();
        $isInitial = !$current;
        $year = (int) now()->year;

        if (!$isInitial) {
            $changesThisYear = $student->schedulePreferences()
                ->where('year_period', $year)
                ->where('change_index', '>', 0)
                ->count();
            if ($changesThisYear >= self::MAX_YEARLY_CHANGES) {
                session()->flash('error', 'سقف تغییر برنامه ۲ بار در سال است و شما به این سقف رسیده‌اید.');
                return;
            }
        }

        DB::transaction(function () use ($student, $selected, $current, $isInitial, $year) {
            $nextIndex = $isInitial ? 0 : (int) ($current->change_index + 1);

            // علامت زدن ترجیح قبلی به replaced اگر اصلاح است
            if ($current) {
                $current->update(['status' => StudentSchedulePreference::STATUS_REPLACED]);
            }

            $pref = StudentSchedulePreference::create([
                'student_id'    => $student->id,
                'status'        => StudentSchedulePreference::STATUS_PENDING,
                'year_period'   => $year,
                'change_index'  => $nextIndex,
                'student_notes' => $this->notes ?: null,
                'submitted_at'  => now(),
            ]);

            foreach ($selected as $day => $slot) {
                StudentSchedulePreferenceTime::create([
                    'student_schedule_preference_id' => $pref->id,
                    'day_of_week' => (int) $day,
                    'start_time'  => $slot['start_time'],
                    'end_time'    => $slot['end_time'],
                ]);
            }
        });

        session()->flash('message', 'درخواست شما برای تعیین وقت ثبت شد و برای مدیر آموزشی ارسال گردید.');
    }

    public function toggleRescheduleForm(): void
    {
        $this->showRescheduleForm = !$this->showRescheduleForm;
    }

    public function submitReschedule(): void
    {
        $student = $this->student();
        if (!$student) {
            return;
        }

        if (!in_array($this->rescheduleType, [SessionRescheduleRequest::TYPE_EXCEPTION, SessionRescheduleRequest::TYPE_PERMANENT], true)) {
            $this->addError('rescheduleType', 'نوع درخواست نامعتبر است.');
            return;
        }

        $validBase = $this->rescheduleProposedDay !== null
            && $this->rescheduleProposedTime !== null
            && preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $this->rescheduleProposedTime);

        if (!$validBase) {
            $this->addError('rescheduleProposedTime', 'روز و ساعت پیشنهادی را وارد کنید.');
            return;
        }

        $data = [
            'student_id'            => $student->id,
            'advisor_id'            => $student->advisor_id,
            'type'                  => $this->rescheduleType,
            'student_proposed_day'  => (int) $this->rescheduleProposedDay,
            'student_proposed_time' => $this->rescheduleProposedTime,
            'student_description'   => $this->rescheduleDescription ?: null,
            'status'                => SessionRescheduleRequest::STATUS_PENDING_MANAGER,
        ];

        if ($this->rescheduleType === SessionRescheduleRequest::TYPE_EXCEPTION) {
            if ($this->rescheduleOriginalSessionId) {
                $session = AdvisingSession::find($this->rescheduleOriginalSessionId);
                if ($session && $session->student_id === $student->id) {
                    $data['original_session_id'] = $session->id;
                }
            }
        } else {
            $data['original_day']  = $this->rescheduleOriginalDay !== null ? (int) $this->rescheduleOriginalDay : null;
            $data['original_time'] = $this->rescheduleOriginalTime ?: null;
        }

        SessionRescheduleRequest::create($data);

        $this->reset([
            'rescheduleOriginalSessionId', 'rescheduleOriginalDay', 'rescheduleOriginalTime',
            'rescheduleProposedDay', 'rescheduleProposedTime', 'rescheduleDescription',
            'showRescheduleForm',
        ]);

        session()->flash('message', 'درخواست جابجایی شما برای مدیر آموزشی ارسال شد.');
    }

    /**
     * انتخاب نهایی دانش‌آموز بین اسلات‌های ارسال‌شده (مدیر + مشاور).
     */
    public function chooseRescheduleSlot(int $requestId, int $day, string $time): void
    {
        $student = $this->student();
        $request = SessionRescheduleRequest::find($requestId);
        if (!$student || !$request || $request->student_id !== $student->id) {
            return;
        }
        if ($request->status !== SessionRescheduleRequest::STATUS_AWAITING_STUDENT_CHOICE) {
            return;
        }

        $request->update([
            'student_selected_day'  => $day,
            'student_selected_time' => $time,
            'status'                => SessionRescheduleRequest::STATUS_AWAITING_CONSULTANT_CONF,
        ]);
        session()->flash('message', 'انتخاب شما برای تایید مشاور ارسال شد.');
    }

    public function render()
    {
        $student = $this->student();
        $current = $this->existingPreference();
        $approvedPref = $student?->schedulePreferences()
            ->where('status', StudentSchedulePreference::STATUS_APPROVED)
            ->with(['times', 'assignedAdvisor'])
            ->latest()
            ->first();

        $rescheduleRequests = $student
            ? $student->rescheduleRequests()
                ->with(['advisor', 'originalSession'])
                ->latest()
                ->limit(20)
                ->get()
            : collect();

        $upcomingSessions = $student
            ? $student->advisingSessions()
                ->whereDate('activation_date', '>=', now()->toDateString())
                ->orderBy('activation_date')
                ->limit(20)
                ->get()
            : collect();

        return view('livewire.client.profile.appointment.index', [
            'days'               => \App\Models\AdminWorkSchedule::DAYS,
            'current'            => $current,
            'approvedPref'       => $approvedPref,
            'canSubmit'          => $this->canSubmitNewPreference($student),
            'rescheduleRequests' => $rescheduleRequests,
            'upcomingSessions'   => $upcomingSessions,
            'student'            => $student,
        ])->layout('layouts.client.app');
    }

    protected function canSubmitNewPreference(?Student $student): bool
    {
        if (!$student) {
            return false;
        }
        $any = $student->schedulePreferences()->exists();
        if (!$any) {
            return true;
        }
        $changes = $student->schedulePreferences()
            ->where('year_period', (int) now()->year)
            ->where('change_index', '>', 0)
            ->count();
        return $changes < self::MAX_YEARLY_CHANGES;
    }
}
