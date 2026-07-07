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
    public ?int $talkSeconds = null;
    public string $callSummary = '';
    public string $failReason = 'no_answer';

    // ── ورودی‌های زمان‌بندیِ هر دانش‌آموز (کلید: studentId) ──────────
    public array $schedule = [];

    // ── تعیینِ روزِ جلسه‌ی جبرانیِ مرخصی (کلید: sessionId) ──────────
    public array $makeupDay = [];

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

    /** نخستین تاریخِ پس از $afterDate که روزِ هفته‌ی ایرانی‌اش $persianDay باشد. */
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
                $q->where('session_day', $dow)
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

    /** تعیینِ روزِ یک جلسه‌ی جبرانیِ بدونِ تاریخ (ناشی از مرخصیِ مشاور). */
    public function assignMakeupDay(int $sessionId): void
    {
        $session = AdvisingSession::where('advisor_id', $this->adminId())
            ->where('is_makeup', true)
            ->whereNull('activation_date')
            ->find($sessionId);

        if (! $session) {
            $this->dispatch('warning', 'جلسه‌ی جبرانی یافت نشد.');
            return;
        }

        $day = $this->makeupDay[$sessionId] ?? null;
        if ($day === null || $day === '' || (int) $day < 0 || (int) $day > 6) {
            $this->dispatch('warning', 'روزِ جلسه‌ی جبرانی را انتخاب کنید.');
            return;
        }

        $date = $this->nextDateForPersianDay((int) $day, Carbon::today());
        $session->update([
            'activation_date' => $date->toDateString(),
            'title'           => Jalalian::fromCarbon($date)->format('Y/m/d'),
        ]);

        unset($this->makeupDay[$sessionId]);
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
        $this->talkSeconds = null;
        $this->callSummary = '';
        $this->failReason  = 'no_answer';
        $this->resetErrorBag();
    }

    public function startRinging(): void
    {
        if (! in_array($this->respondent, array_keys(ContactDocumentation::RESPONDENT), true)) {
            $this->dispatch('warning', 'ابتدا شخصِ پاسخگو را انتخاب کنید.');
            return;
        }
        $this->callPhase = 'ringing';
    }

    public function markAnswered(): void
    {
        $this->callPhase = 'talking';
    }

    /** عدم‌پاسخِ خودکار پس از ۲۵ ثانیه. */
    public function autoNoAnswer(): void
    {
        $this->failReason = ContactDocumentation::FAIL_NO_ANSWER ?? 'no_answer';
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
            ['failReason' => 'required|in:no_answer,off,rejected,wrong'],
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

        ContactDocumentation::create([
            'admin_id'              => $this->adminId(),
            'student_id'            => $student->id,
            'title'                 => 'تماسِ هماهنگیِ جلسه‌ی مشاوره — ' . $this->tomorrowTitle(),
            'description'           => $this->callSummary ?: null,
            'contact_status'        => $connected ? 'successful' : 'unsuccessful',
            'contact_date'          => Carbon::today()->toDateString(),
            'respondent'            => $this->respondent,
            'connected'             => $connected,
            'talk_duration_seconds' => $connected ? $this->talkSeconds : null,
            'answered_at'           => $connected ? now() : null,
            'fail_reason'           => $connected ? null : $this->failReason,
        ]);
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
            ->where('status', 'held') // FIX: Used string literal instead of undefined constant
            ->where('activation_date', '<=', $today->toDateString())
            ->count();

        $this->totalMakeupSessionsCount = AdvisingSession::where('advisor_id', $adminId)
            ->where('is_makeup', true)
            ->count();

        // 3. غایبین این هفته
        $startOfWeek = $today->copy()->startOfWeek(Carbon::SATURDAY);
        $endOfWeek = $today->copy()->endOfWeek(Carbon::FRIDAY);

        // فرض: غایب یعنی جلسه‌اش در گذشته است ولی وضعیت 'برگزار شده' را ندارد
        $this->absenteesThisWeek = AdvisingSession::where('advisor_id', $adminId)
            ->whereBetween('activation_date', [$startOfWeek, $today])
            ->where('status', '!=', 'held') // FIX: Used string literal instead of undefined constant
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
        $grouped     = $allStudents->groupBy('session_day');

        // محاسبه آمار داشبورد با همه دانش‌آموزان (بدون فیلتر جستجو)
        $this->calculateDashboardData($studentsQuery->get());


        // وضعیتِ فردا (شاملِ روزِ ثابت + جلساتِ جبرانیِ فردا)
        $tomorrowStudents = $this->tomorrowStudents();

        $tomorrowSessions = AdvisingSession::where('advisor_id', $adminId)
            ->whereDate('activation_date', $tomorrow->toDateString())
            ->get()
            ->keyBy('student_id');

        $calledIds = ContactDocumentation::where('admin_id', $adminId)
            ->where('connected', true)
            ->whereDate('contact_date', Carbon::today())
            ->pluck('student_id')
            ->unique()
            ->flip();

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
            ->get();

        $activeStudent = $this->activeStudentId
            ? Student::with('user.personalInformation')->find($this->activeStudentId)
            : null;

        return view('livewire.admin.student.consultation.index', [
            'days'             => AdminWorkSchedule::DAYS,
            'grouped'          => $grouped,
            'allStudents'      => $allStudents,
            'noDayStudents'    => $allStudents->whereNull('session_day')->values(),
            'tomorrowDow'      => $tomorrowDow,
            'tomorrowStudents' => $tomorrowStudents,
            'tomorrowSessions' => $tomorrowSessions,
            'calledIds'        => $calledIds,
            'tomorrowTitle'    => $this->tomorrowTitle(),
            'canFinalize'      => $canFinalize,
            'pendingMakeups'   => $pendingMakeups,
            'activeStudent'    => $activeStudent,
        ])->layout('layouts.admin.app');
    }
}
