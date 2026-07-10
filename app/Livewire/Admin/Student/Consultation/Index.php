<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\AdminWorkSchedule;
use App\Models\AdvisingPreSession;
use App\Models\AdvisingSession;
use App\Models\ContactDocumentation;
use App\Models\Student;
use App\Services\NotificationService;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

/**
 * صفحه‌ی جلساتِ مشاور (b) — جریانِ «یک روز قبل».
 *
 * هفت باکسِ روزِ هفته نمایش داده می‌شود؛ هر دانش‌آموز در باکسِ روزِ ثابتِ هفتگی‌اش
 * (session_day) قرار می‌گیرد. فقط باکسِ «فردا» قابلِ عملیات است: مشاور با هر دانش‌آموز
 * تماس می‌گیرد (با تایمر و ثبتِ نتیجه در مستنداتِ تماس)، سپس ساعت/دقیقه/لینکِ جلسه را
 * تعیین و «ذخیره» می‌کند. وقتی همه‌ی دانش‌آموزانِ فردا ساعت‌دار شدند، «ثبت نهایی» جلسات
 * را قطعی کرده، پیش‌جلسه می‌سازد و به دانش‌آموزان اطلاع می‌دهد.
 */
class Index extends Component
{
    use SEOTools;

    public $search = '';

    // ── مودالِ تماس ──────────────────────────────────────────────
    public ?int $activeStudentId = null;
    /** select | ringing | talking | answerForm | noAnswerForm */
    public string $callPhase = 'select';
    public string $respondent = 'student';
    public array $respondents = ['student'];
    public ?int $talkSeconds = null;
    public string $callSummary = '';
    public string $failReason = 'no_answer';

    // ── ورودی‌های زمان‌بندیِ هر دانش‌آموز (کلید: studentId) ──────────
    public array $schedule = [];

    // ── تعیینِ تاریخ جلسه‌ی جبرانی (کلید: sessionId یا studentId) ──────────
    public array $makeupDate = [];
    public array $rescheduleMakeupDate = [];

    // ── داده‌های داشبورد جدید ──────────────────────────────────────
    public int $totalStudentsCount = 0;
    public int $heldSessionsCount = 0;
    public int $totalMakeupSessionsCount = 0;
    public Collection $absenteesThisWeek;


    public function mount(): void
    {
        $this->seo()->setTitle('جلسات مشاوره');
        $this->absenteesThisWeek = collect();
    }

    protected function adminId()
    {
        return auth('admin')->id();
    }

    protected function tomorrow(): Carbon
    {
        return Carbon::tomorrow();
    }

    /** روزِ هفته‌ی فردا به تقویم ایرانی (۰=شنبه .. ۶=جمعه). */
    protected function tomorrowDow(): int
    {
        return ($this->tomorrow()->dayOfWeek + 1) % 7;
    }

    protected function tomorrowTitle(): string
    {
        return Jalalian::fromCarbon($this->tomorrow())->format('Y/m/d');
    }

    protected function allowedMakeupDates(): array
    {
        $dates = [];
        for ($i = 0; $i <= 7; $i++) {
            $date = Carbon::today()->addDays($i);
            $dates[$date->toDateString()] = Jalalian::fromCarbon($date)->format('l Y/m/d');
        }

        return $dates;
    }

    /**
     * دانش‌آموزانِ فردا: روزِ ثابتِ هفتگی‌شان فردا است یا جلسه‌ی جبرانیِ نهایی‌نشده‌ای برای فردا دارند.
     */
    protected function tomorrowStudents()
    {
        $adminId  = $this->adminId();
        $tomorrow = $this->tomorrow()->toDateString();
        $dow      = $this->tomorrowDow();

        $query = Student::where('advisor_id', $adminId)
            ->where(function ($q) use ($dow, $tomorrow, $adminId) {
                $q->where(function ($weekly) use ($dow, $tomorrow, $adminId) {
                    $weekly->where('session_day', $dow)
                        ->whereDoesntHave('advisingSessions', function ($s) use ($tomorrow, $adminId) {
                            $s->where('advisor_id', $adminId)
                                ->whereDate('activation_date', $tomorrow)
                                ->whereNotNull('result_status');
                        });
                })
                    ->orWhereHas('advisingSessions', function ($s) use ($tomorrow, $adminId) {
                        $s->where('advisor_id', $adminId)
                            ->where('is_makeup', true)
                            ->where('finalized', false)
                            ->whereDate('activation_date', $tomorrow);
                    });
            })
            ->with(['user.personalInformation', 'user.profile']);

        if ($this->search) {
            $query->whereHas('user.personalInformation', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('name_full', 'like', "%{$this->search}%");
            });
        }

        return $query->get();
    }

    /** تعیینِ تاریخ یک جلسه‌ی جبرانیِ بدونِ تاریخ. */
    public function assignMakeupDate(int $sessionId): void
    {
        $session = AdvisingSession::where('advisor_id', $this->adminId())
            ->where('is_makeup', true)
            ->whereNull('activation_date')
            ->find($sessionId);

        if (! $session) {
            $this->dispatch('warning', 'جلسه‌ی جبرانی یافت نشد.');
            return;
        }

        $date = $this->makeupDate[$sessionId] ?? null;
        if (! $date || ! array_key_exists($date, $this->allowedMakeupDates())) {
            $this->dispatch('warning', 'تاریخ جلسه‌ی جبرانی را از بازه‌ی مجاز انتخاب کنید.');
            return;
        }

        $date = Carbon::parse($date);
        $session->update([
            'activation_date' => $date->toDateString(),
            'title'           => Jalalian::fromCarbon($date)->format('Y/m/d'),
        ]);

        unset($this->makeupDate[$sessionId]);
        $this->dispatch('success', 'روزِ جلسه‌ی جبرانی تعیین شد. یک روز قبل با دانش‌آموز تماس بگیرید و ساعت را ثبت کنید.');
    }

    // ==================== مودالِ تماس ====================

    public function openCall(int $studentId): void
    {
        $this->resetCall();
        $this->activeStudentId = $studentId;
        $this->callPhase = 'select';
    }

    public function closeCall(): void
    {
        $this->resetCall();
    }

    protected function resetCall(): void
    {
        $this->activeStudentId = null;
        $this->callPhase   = 'select';
        $this->respondent  = 'student';
        $this->respondents = ['student'];
        $this->talkSeconds = null;
        $this->callSummary = '';
        $this->failReason  = 'no_answer';
        $this->resetErrorBag();
    }

    public function startRinging(): void
    {
        $this->respondents = array_values(array_unique(array_filter($this->respondents)));
        $allowedRespondents = array_keys(ContactDocumentation::RESPONDENT_MULTI);

        if (empty($this->respondents) || array_diff($this->respondents, $allowedRespondents)) {
            $this->dispatch('warning', 'ابتدا حداقل یک پاسخگو را انتخاب کنید.');
            return;
        }

        $this->respondent = $this->respondents[0];
        $this->callPhase = 'ringing';
    }

    public function markAnswered(): void
    {
        $this->callPhase = 'talking';
    }

    /** عدم‌پاسخِ خودکار پس از ۲۵ ثانیه. */
    public function autoNoAnswer(): void
    {
        $this->failReason = 'no_answer';
        $this->recordCall(false);
        $this->dispatch('warning', 'عدم پاسخ ثبت شد. می‌توانید دوباره تماس بگیرید.');
        $this->closeCall();
    }

    /** عدم‌پاسخِ دستی — برای انتخابِ علت به فرم می‌رویم. */
    public function markNoAnswer(): void
    {
        $this->callPhase = 'noAnswerForm';
    }

    public function endConversation(int $seconds): void
    {
        $this->talkSeconds = max(0, $seconds);
        $this->callPhase = 'answerForm';
    }

    /** ثبتِ تماسِ موفق (پاسخ داده شد). */
    public function saveAnsweredCall(): void
    {
        if (! $this->activeStudentId) {
            return;
        }
        $this->recordCall(true);
        $this->dispatch('success', 'تماس ثبت شد. حالا ساعتِ جلسه را تعیین کنید.');
        $this->closeCall();
    }

    /** ثبتِ تماسِ ناموفق با علتِ انتخابی. */
    public function saveNoAnswer(): void
    {
        $this->validate(
            ['failReason' => 'required|in:no_answer,off,rejected'],
            ['failReason.required' => 'علتِ عدم برقراری تماس را انتخاب کنید.']
        );
        $this->recordCall(false);
        $this->dispatch('success', 'عدم پاسخ ثبت شد.');
        $this->closeCall();
    }

    protected function recordCall(bool $connected): void
    {
        $student = Student::find($this->activeStudentId);
        if (! $student || $student->advisor_id !== $this->adminId()) {
            return;
        }

        $respondents = $connected
            ? array_values(array_unique(array_filter($this->respondents)))
            : [];

        ContactDocumentation::create([
            'admin_id'              => $this->adminId(),
            'student_id'            => $student->id,
            'title'                 => $this->callTitleForStudent($student->id),
            'description'           => $this->callSummary ?: null,
            'contact_status'        => $connected ? 'successful' : 'unsuccessful',
            'contact_date'          => Carbon::today()->toDateString(),
            'respondent'            => $respondents[0] ?? $this->respondent,
            'respondents'           => $connected ? $respondents : null,
            'connected'             => $connected,
            'talk_duration_seconds' => $connected ? $this->talkSeconds : null,
            'answered_at'           => $connected ? now() : null,
            'fail_reason'           => $connected ? null : $this->failReason,
        ]);
    }

    protected function callTitleForStudent(int $studentId): string
    {
        $session = AdvisingSession::where('advisor_id', $this->adminId())
            ->where('student_id', $studentId)
            ->whereDate('activation_date', $this->tomorrow()->toDateString())
            ->first();

        $title = $session?->is_makeup ? 'تماس برای جلسه جبرانی' : 'تماسِ هماهنگیِ جلسه‌ی مشاوره';

        return $title . ' — ' . $this->tomorrowTitle();
    }

    // ==================== زمان‌بندیِ جلسه ====================

    /** آیا امروز تماسِ موفقی با این دانش‌آموز ثبت شده است؟ */
    protected function calledSuccessfullyToday(int $studentId): bool
    {
        return ContactDocumentation::where('admin_id', $this->adminId())
            ->where('student_id', $studentId)
            ->where('connected', true)
            ->whereDate('contact_date', Carbon::today())
            ->exists();
    }

    protected function noAnswerCallsToday(int $studentId): int
    {
        return ContactDocumentation::where('admin_id', $this->adminId())
            ->where('student_id', $studentId)
            ->where('connected', false)
            ->whereDate('contact_date', Carbon::today())
            ->count();
    }

    protected function createMakeupForAbsence(AdvisingSession $sourceSession, ?string $activationDate = null): ?AdvisingSession
    {
        $makeup = AdvisingSession::firstOrCreate(
            [
                'advisor_id'        => $sourceSession->advisor_id,
                'student_id'        => $sourceSession->student_id,
                'source_session_id' => $sourceSession->id,
                'is_makeup'         => true,
            ],
            [
                'title'           => $activationDate
                    ? Jalalian::fromCarbon(Carbon::parse($activationDate))->format('Y/m/d')
                    : 'جلسه جبرانی',
                'description'     => 'جلسه جبرانی (غیبت دانش‌آموز)',
                'activation_date' => $activationDate,
                'session_time'    => null,
                'location_type'   => AdvisingSession::LOCATION_ONLINE,
                'status'          => AdvisingSession::STATUS_INACTIVE,
                'is_active'       => false,
                'finalized'       => false,
                'makeup_reason'   => AdvisingSession::MAKEUP_STUDENT_ABSENCE,
            ]
        );

        if (
            $activationDate
            && ! $makeup->finalized
            && $makeup->result_status === null
            && (! $makeup->activation_date || ! $makeup->activation_date->isSameDay(Carbon::parse($activationDate)))
        ) {
            $makeup->update([
                'activation_date' => $activationDate,
                'title'           => Jalalian::fromCarbon(Carbon::parse($activationDate))->format('Y/m/d'),
                'session_time'    => null,
                'skyroom_link'    => null,
            ]);
        }

        return $makeup;
    }

    public function markAbsentAfterNoAnswers(int $studentId): void
    {
        $student = Student::find($studentId);
        if (! $student || $student->advisor_id !== $this->adminId()) {
            $this->dispatch('error', 'این دانش‌آموز در فهرستِ شما نیست.');
            return;
        }

        if ($this->noAnswerCallsToday($studentId) < 3) {
            $this->dispatch('warning', 'برای ثبت غیبت، باید امروز حداقل ۳ تماس ناموفق ثبت شده باشد.');
            return;
        }

        $isWeeklyTomorrow = (int) $student->session_day === $this->tomorrowDow();
        $hasMakeupTomorrow = AdvisingSession::where('advisor_id', $this->adminId())
            ->where('student_id', $studentId)
            ->where('is_makeup', true)
            ->whereDate('activation_date', $this->tomorrow()->toDateString())
            ->exists();

        if (! $isWeeklyTomorrow && ! $hasMakeupTomorrow) {
            $this->dispatch('error', 'این دانش‌آموز در فهرستِ فردا نیست.');
            return;
        }

        $session = AdvisingSession::firstOrNew([
            'advisor_id'      => $this->adminId(),
            'student_id'      => $studentId,
            'activation_date' => $this->tomorrow()->toDateString(),
        ]);

        $session->fill([
            'title'         => $session->title ?: $this->tomorrowTitle(),
            'description'   => $session->description ?: 'جلسه مشاوره فردی',
            'location_type' => $session->location_type ?: AdvisingSession::LOCATION_ONLINE,
            'status'        => AdvisingSession::STATUS_COMPLETED,
            'is_active'     => false,
            'finalized'     => true,
            'result_status' => AdvisingSession::RESULT_STUDENT_ABSENT,
        ])->save();

        $this->createMakeupForAbsence($session);

        $this->dispatch('success', 'غیبت جلسه ثبت شد و جلسه‌ی جبرانی در انتظار تعیین روز قرار گرفت.');
    }

    public function createMakeupForAbsentSession(int $sessionId): void
    {
        $session = AdvisingSession::where('advisor_id', $this->adminId())
            ->where('result_status', AdvisingSession::RESULT_STUDENT_ABSENT)
            ->find($sessionId);

        if (! $session) {
            $this->dispatch('warning', 'جلسه‌ی غیبت یافت نشد.');
            return;
        }

        $makeup = $this->createMakeupForAbsence($session);

        if (! $makeup) {
            $this->dispatch('warning', 'برای این جلسه امکان ساخت جبرانی وجود ندارد.');
            return;
        }

        $this->dispatch('success', 'جلسه‌ی جبرانی ساخته شد و در انتظار تعیین روز قرار گرفت.');
    }

    public function rescheduleTomorrowSessionToMakeup(int $studentId): void
    {
        $student = Student::find($studentId);
        if (! $student || $student->advisor_id !== $this->adminId()) {
            $this->dispatch('error', 'این دانش‌آموز در فهرستِ شما نیست.');
            return;
        }

        if (! $this->calledSuccessfullyToday($studentId)) {
            $this->dispatch('warning', 'ابتدا باید تماس موفق ثبت شود.');
            return;
        }

        $date = $this->rescheduleMakeupDate[$studentId] ?? null;
        if (! $date || ! array_key_exists($date, $this->allowedMakeupDates())) {
            $this->dispatch('warning', 'تاریخ جلسه‌ی جبرانی را از بازه‌ی مجاز انتخاب کنید.');
            return;
        }

        $isWeeklyTomorrow = (int) $student->session_day === $this->tomorrowDow();
        $hasMakeupTomorrow = AdvisingSession::where('advisor_id', $this->adminId())
            ->where('student_id', $studentId)
            ->where('is_makeup', true)
            ->whereDate('activation_date', $this->tomorrow()->toDateString())
            ->exists();

        if (! $isWeeklyTomorrow && ! $hasMakeupTomorrow) {
            $this->dispatch('error', 'این دانش‌آموز در فهرستِ فردا نیست.');
            return;
        }

        $session = AdvisingSession::firstOrNew([
            'advisor_id'      => $this->adminId(),
            'student_id'      => $studentId,
            'activation_date' => $this->tomorrow()->toDateString(),
        ]);

        $session->fill([
            'title'         => $session->title ?: $this->tomorrowTitle(),
            'description'   => $session->description ?: 'جلسه مشاوره فردی',
            'location_type' => $session->location_type ?: AdvisingSession::LOCATION_ONLINE,
            'status'        => AdvisingSession::STATUS_COMPLETED,
            'is_active'     => false,
            'finalized'     => true,
            'result_status' => AdvisingSession::RESULT_STUDENT_ABSENT,
        ])->save();

        $this->createMakeupForAbsence($session, Carbon::parse($date)->toDateString());
        unset($this->rescheduleMakeupDate[$studentId]);

        $this->dispatch('success', 'جلسه جبرانی برای تاریخ انتخاب‌شده ثبت شد و ساعت آن یک روز قبل هماهنگ می‌شود.');
    }

    public function saveSchedule(int $studentId): void
    {
        $student = Student::find($studentId);
        if (! $student || $student->advisor_id !== $this->adminId()) {
            $this->dispatch('error', 'این دانش‌آموز در فهرستِ شما نیست.');
            return;
        }

        $isWeeklyTomorrow = (int) $student->session_day === $this->tomorrowDow();
        $hasMakeupTomorrow = AdvisingSession::where('advisor_id', $this->adminId())
            ->where('student_id', $studentId)
            ->where('is_makeup', true)
            ->whereDate('activation_date', $this->tomorrow()->toDateString())
            ->exists();

        if (! $isWeeklyTomorrow && ! $hasMakeupTomorrow) {
            $this->dispatch('error', 'این دانش‌آموز در فهرستِ فردا نیست.');
            return;
        }

        if (! $this->calledSuccessfullyToday($studentId)) {
            $this->dispatch('warning', 'ابتدا باید با دانش‌آموز تماسِ موفق برقرار شود.');
            return;
        }

        $this->validate([
            "schedule.$studentId.hour"   => 'required|integer|min:0|max:23',
            "schedule.$studentId.minute" => 'required|integer|min:0|max:59',
            "schedule.$studentId.link"   => 'required|url',
        ], [
            "schedule.$studentId.hour.required"   => 'ساعت را وارد کنید.',
            "schedule.$studentId.minute.required" => 'دقیقه را وارد کنید.',
            "schedule.$studentId.link.required"   => 'لینکِ جلسه‌ی آنلاین الزامی است.',
            "schedule.$studentId.link.url"        => 'لینکِ واردشده معتبر نیست.',
        ]);

        $data = $this->schedule[$studentId];
        $time = sprintf('%02d:%02d', (int) $data['hour'], (int) $data['minute']);

        $session = AdvisingSession::firstOrNew([
            'advisor_id'      => $this->adminId(),
            'student_id'      => $studentId,
            'activation_date' => $this->tomorrow()->toDateString(),
        ]);

        $session->fill([
            'title'         => $this->tomorrowTitle(),
            'description'   => 'جلسه مشاوره فردی',
            'session_time'  => $time,
            'location_type' => AdvisingSession::LOCATION_ONLINE,
            'skyroom_link'  => $data['link'],
            'status'        => AdvisingSession::STATUS_INACTIVE,
            'is_active'     => false,
            'finalized'     => false,
        ])->save();

        $this->dispatch('success', 'ساعتِ جلسه ذخیره شد.');
    }

    public function finalizeAll(): void
    {
        $students = $this->tomorrowStudents();

        if ($students->isEmpty()) {
            $this->dispatch('warning', 'برای فردا دانش‌آموزی وجود ندارد.');
            return;
        }

        $sessions = AdvisingSession::where('advisor_id', $this->adminId())
            ->whereDate('activation_date', $this->tomorrow()->toDateString())
            ->get()
            ->keyBy('student_id');

        // اطمینان از اینکه همه‌ی دانش‌آموزانِ فردا ساعت‌دار شده‌اند
        foreach ($students as $st) {
            $s = $sessions->get($st->id);
            if (! $s || ! $s->session_time) {
                $this->dispatch('warning', 'ابتدا ساعتِ همه‌ی دانش‌آموزانِ فردا را ثبت کنید.');
                return;
            }
        }

        foreach ($sessions as $session) {
            $session->update(['finalized' => true]);

            AdvisingPreSession::firstOrCreate(
                ['advising_session_id' => $session->id],
                [
                    'student_id' => $session->student_id,
                    'title'      => $session->title,
                    'status'     => 'pending',
                ]
            );

            $this->sendSessionNotification($session);
        }

        $this->dispatch('success', 'جلساتِ فردا ثبتِ نهایی شد و به دانش‌آموزان اطلاع داده شد.');
    }

    protected function sendSessionNotification(AdvisingSession $session): void
    {
        $time = $session->session_time ? $session->session_time->format('H:i') : '';
        $date = Jalalian::fromCarbon(Carbon::parse($session->activation_date))->format('Y/m/d');
        NotificationService::sendToStudent(
            $session->student_id,
            'جلسه مشاوره',
            "جلسه‌ی مشاوره‌ی شما برای تاریخ {$date} ساعت {$time} (آنلاین) ثبت شد. لطفاً پیش‌جلسه‌ی خود را تکمیل کنید."
        );
    }

    /** متدهای مربوط به داشبورد جدید */
    protected function calculateDashboardData(Collection $allStudents): void
    {
        $adminId = $this->adminId();
        $today = Carbon::today();

        // 1. تعداد کل دانش آموزان تحت مشاوره من
        $this->totalStudentsCount = $allStudents->count();

        // 2. تعداد جلسات برگزار شده + تعداد جلسات جبرانی
        $this->heldSessionsCount = AdvisingSession::where('advisor_id', $adminId)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->where('activation_date', '<=', $today->toDateString())
            ->count();

        $this->totalMakeupSessionsCount = AdvisingSession::where('advisor_id', $adminId)
            ->where('is_makeup', true)
            ->count();

        // 3. غایبین این هفته
        $startOfWeek = $today->copy()->startOfWeek(Carbon::SATURDAY);
        $endOfWeek = $today->copy()->endOfWeek(Carbon::FRIDAY);

        $this->absenteesThisWeek = AdvisingSession::where('advisor_id', $adminId)
            ->whereBetween('activation_date', [$startOfWeek, $today])
            ->where('result_status', AdvisingSession::RESULT_STUDENT_ABSENT)
            ->with('student.user.personalInformation')
            ->get();
    }


    public function render()
    {
        $adminId    = $this->adminId();
        $tomorrowDow = $this->tomorrowDow();
        $tomorrow   = $this->tomorrow();

        $studentsQuery = Student::query()
            ->where('advisor_id', $adminId)
            ->with(['user.personalInformation', 'user.profile']);

        // جستجو باید قبل از گروه‌بندی اعمال شود
        $searchableStudentsQuery = clone $studentsQuery;
        if ($this->search) {
            $searchableStudentsQuery->whereHas('user.personalInformation', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('name_full', 'like', "%{$this->search}%");
            });
        }
        $allStudents = $searchableStudentsQuery->get();

        $activeMakeupsByStudent = AdvisingSession::where('advisor_id', $adminId)
            ->where('is_makeup', true)
            ->where('finalized', false)
            ->whereNull('result_status')
            ->whereNotNull('activation_date')
            ->orderBy('activation_date')
            ->get()
            ->keyBy('student_id');

        $grouped = $allStudents->groupBy(function ($student) use ($activeMakeupsByStudent) {
            $makeup = $activeMakeupsByStudent->get($student->id);
            if ($makeup?->activation_date) {
                return (Carbon::parse($makeup->activation_date)->dayOfWeek + 1) % 7;
            }

            return $student->session_day;
        });

        // محاسبه آمار داشبورد با همه دانش‌آموزان (بدون فیلتر جستجو)
        $this->calculateDashboardData($studentsQuery->get());


        // وضعیتِ فردا (شاملِ روزِ ثابت + جلساتِ جبرانیِ فردا)
        $tomorrowStudents = $this->tomorrowStudents();

        $tomorrowSessions = AdvisingSession::where('advisor_id', $adminId)
            ->whereDate('activation_date', $tomorrow->toDateString())
            ->get()
            ->keyBy('student_id');

        $todaySessions = AdvisingSession::where('advisor_id', $adminId)
            ->whereDate('activation_date', Carbon::today()->toDateString())
            ->with('student.user.personalInformation')
            ->orderByRaw('session_time IS NULL')
            ->orderBy('session_time')
            ->orderBy('id')
            ->get();

        $calledIds = ContactDocumentation::where('admin_id', $adminId)
            ->where('connected', true)
            ->whereDate('contact_date', Carbon::today())
            ->pluck('student_id')
            ->unique()
            ->flip();

        $noAnswerCounts = ContactDocumentation::where('admin_id', $adminId)
            ->where('connected', false)
            ->whereDate('contact_date', Carbon::today())
            ->selectRaw('student_id, COUNT(*) as count')
            ->groupBy('student_id')
            ->pluck('count', 'student_id');

        // مقداردهی اولیه‌ی ورودی‌های زمان از جلساتِ ذخیره‌شده‌ی فردا
        foreach ($tomorrowStudents as $st) {
            if (! isset($this->schedule[$st->id])) {
                $sess = $tomorrowSessions->get($st->id);
                $this->schedule[$st->id] = [
                    'hour'   => $sess && $sess->session_time ? (int) $sess->session_time->format('H') : '',
                    'minute' => $sess && $sess->session_time ? (int) $sess->session_time->format('i') : '',
                    'link'   => $sess->skyroom_link ?? '',
                ];
            }
        }

        // آیا «ثبت نهایی» قابلِ نمایش است؟ (همه ساعت‌دار + حداقل یکی نهایی‌نشده)
        $allSaved = $tomorrowStudents->isNotEmpty()
            && $tomorrowStudents->every(function ($st) use ($tomorrowSessions) {
                $s = $tomorrowSessions->get($st->id);
                return $s && $s->session_time;
            });
        $anyUnfinalized = $tomorrowSessions->contains(fn ($s) => ! $s->finalized && $s->session_time);
        $canFinalize = $allSaved && $anyUnfinalized;

        // جلساتِ جبرانیِ بدونِ تاریخ (ناشی از مرخصیِ تاییدشده) که منتظرِ تعیینِ روز هستند
        $pendingMakeups = AdvisingSession::where('advisor_id', $adminId)
            ->where('is_makeup', true)
            ->whereNull('activation_date')
            ->with('student.user.personalInformation')
            ->oldest()
            ->get();

        $activeStudent = $this->activeStudentId
            ? Student::with('user.personalInformation')->find($this->activeStudentId)
            : null;

        return view('livewire.admin.student.consultation.index', [
            'days'             => AdminWorkSchedule::DAYS,
            'grouped'          => $grouped,
            'allStudents'      => $allStudents,
            'noDayStudents'    => $allStudents
                ->filter(fn ($student) => $student->session_day === null && ! $activeMakeupsByStudent->has($student->id))
                ->values(),
            'tomorrowDow'      => $tomorrowDow,
            'tomorrowStudents' => $tomorrowStudents,
            'tomorrowSessions' => $tomorrowSessions,
            'todaySessions'    => $todaySessions,
            'calledIds'        => $calledIds,
            'noAnswerCounts'   => $noAnswerCounts,
            'tomorrowTitle'    => $this->tomorrowTitle(),
            'canFinalize'      => $canFinalize,
            'pendingMakeups'   => $pendingMakeups,
            'allowedMakeupDates' => $this->allowedMakeupDates(),
            'activeMakeupsByStudent' => $activeMakeupsByStudent,
            'activeStudent'    => $activeStudent,
        ])->layout('layouts.admin.app');
    }
}
