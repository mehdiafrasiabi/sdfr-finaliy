<?php

namespace App\Livewire\Admin\Student\Consultation;

use App\Models\AdminWorkSchedule;
use App\Models\AdvisorOnboarding;
use App\Models\AdvisingPreSession;
use App\Models\AdvisingSession;
use App\Models\Student;
use App\Services\NotificationService;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

/**
 * صفحه‌ی جلساتِ مشاور — تعریفِ دستیِ جلسه.
 *
 * هفت باکسِ روزِ هفته نمایش داده می‌شود؛ هر دانش‌آموز در باکسِ روزِ ثابتِ هفتگی‌اش
 * (session_day) یا روزِ جلسه‌ی جبرانیِ فعالش قرار می‌گیرد. برایِ هر دانش‌آموز، مشاور
 * می‌تواند مستقیماً و بدون هیچ پیش‌نیازی (تماس/اتمام‌حجت) جلسه را برایِ تاریخِ همان روز
 * تعریف یا ویرایش کند («تعریفِ سریعِ جلسه»)؛ جلسه بلافاصله ثبتِ نهایی می‌شود، پیش‌جلسه
 * ساخته می‌شود و به دانش‌آموز اطلاع داده می‌شود. غیبت نیز به همین شکل به‌صورتِ دستی
 * ثبت می‌شود و در صورتِ نیاز، جلسه‌ی جبرانی می‌سازد.
 */
class Index extends Component
{
    use SEOTools;

    public $search = '';

    // ── مودالِ تعریف/ویرایشِ سریعِ جلسه ──────────────────────────────
    public ?int $quickStudentId = null;
    public string $quickDate = '';
    public array $quickTime = ['hour' => '', 'minute' => ''];
    public string $quickLink = '';

    // ── تعیینِ تاریخ جلسه‌ی جبرانی (کلید: sessionId یا studentId) ──────────
    public array $makeupDate = [];
    public array $rescheduleMakeupDate = [];

    // ── داده‌های داشبورد ──────────────────────────────────────
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

    /** نزدیک‌ترین تاریخِ وقوعِ یک روزِ هفته (۰=شنبه .. ۶=جمعه) در ۷ روزِ آینده، شاملِ امروز. */
    protected function dayOccurrenceDate(int $dow): Carbon
    {
        $today = Carbon::today();

        for ($i = 0; $i <= 6; $i++) {
            $date = $today->copy()->addDays($i);
            if ((($date->dayOfWeek + 1) % 7) === $dow) {
                return $date;
            }
        }

        return $today;
    }

    protected function unresolvedOnboardingStatuses(): array
    {
        return [
            AdvisorOnboarding::STATUS_PENDING_CALL,
            AdvisorOnboarding::STATUS_PENDING_LINK,
            AdvisorOnboarding::STATUS_PENDING_REVIEW,
            AdvisorOnboarding::STATUS_REJECTED,
        ];
    }

    protected function onboardingRecordForStudent(int $studentId): ?AdvisorOnboarding
    {
        return AdvisorOnboarding::where('advisor_id', $this->adminId())
            ->where('student_id', $studentId)
            ->first();
    }

    protected function ensureConsultationUnlocked(Student $student): bool
    {
        $onboarding = $this->onboardingRecordForStudent($student->id);

        if ($onboarding && $onboarding->status !== AdvisorOnboarding::STATUS_APPROVED) {
            $this->dispatch('warning', 'تا قبل از تایید لینک گروه بله توسط مدیر آموزشی، هیچ جلسه‌ای برای این دانش‌آموز فعال نمی‌شود.');
            return false;
        }

        return true;
    }

    /**
     * بازکردنِ مودالِ تعریف/ویرایشِ سریعِ جلسه.
     * اگر برای دانش‌آموز و تاریخِ دادهشده جلسه‌ای از قبل موجود باشد، فرم با اطلاعاتِ آن پر می‌شود.
     */
    public function openQuickSession(?int $studentId = null, ?string $date = null): void
    {
        $this->quickStudentId = $studentId;
        $this->quickDate = $date ?: Carbon::today()->toDateString();
        $this->quickTime = ['hour' => '', 'minute' => ''];
        $this->quickLink = '';

        if ($studentId) {
            $existing = AdvisingSession::where('advisor_id', $this->adminId())
                ->where('student_id', $studentId)
                ->whereDate('activation_date', $this->quickDate)
                ->first();

            if ($existing) {
                $this->quickTime = [
                    'hour'   => $existing->session_time ? (int) $existing->session_time->format('H') : '',
                    'minute' => $existing->session_time ? (int) $existing->session_time->format('i') : '',
                ];
                $this->quickLink = $existing->skyroom_link ?? '';
            }
        }

        $this->resetErrorBag();
        $this->dispatch('open-quick-session-modal', studentId: $this->quickStudentId);
    }

    public function closeQuickSession(): void
    {
        $this->quickStudentId = null;
    }

    /**
     * تعریف/ویرایشِ دستیِ جلسه توسطِ مشاور: بدون هیچ پیش‌نیازی.
     * جلسه مستقیم finalized می‌شود، پیش‌جلسه ساخته می‌شود و به دانش‌آموز اطلاع داده می‌شود.
     */
    public function saveQuickSession(): void
    {
        $student = Student::find($this->quickStudentId);
        if (! $student || $student->advisor_id !== $this->adminId()) {
            $this->dispatch('error', 'این دانش‌آموز در فهرستِ شما نیست.');
            return;
        }

        if (! $this->ensureConsultationUnlocked($student)) {
            return;
        }

        $this->validate([
            'quickDate'         => 'required|date',
            'quickTime.hour'    => 'required|integer|min:0|max:23',
            'quickTime.minute'  => 'required|integer|min:0|max:59',
            'quickLink'         => 'required|url',
        ], [
            'quickDate.required'        => 'تاریخِ جلسه را انتخاب کنید.',
            'quickTime.hour.required'   => 'ساعت را وارد کنید.',
            'quickTime.minute.required' => 'دقیقه را وارد کنید.',
            'quickLink.required'        => 'لینکِ جلسه‌ی آنلاین الزامی است.',
            'quickLink.url'             => 'لینکِ واردشده معتبر نیست.',
        ]);

        $date = Carbon::parse($this->quickDate);
        $time = sprintf('%02d:%02d', (int) $this->quickTime['hour'], (int) $this->quickTime['minute']);

        $session = AdvisingSession::firstOrNew([
            'advisor_id'      => $this->adminId(),
            'student_id'      => $student->id,
            'activation_date' => $date->toDateString(),
        ]);

        $isNew = ! $session->exists;

        $session->fill([
            'title'         => Jalalian::fromCarbon($date)->format('Y/m/d'),
            'description'   => $session->description ?: 'جلسه مشاوره فردی',
            'session_time'  => $time,
            'location_type' => AdvisingSession::LOCATION_ONLINE,
            'skyroom_link'  => $this->quickLink,
            'status'        => AdvisingSession::STATUS_INACTIVE,
            'is_active'     => false,
            'finalized'     => true,
            'result_status' => null,
        ])->save();

        AdvisingPreSession::firstOrCreate(
            ['advising_session_id' => $session->id],
            [
                'student_id' => $session->student_id,
                'title'      => $session->title,
                'status'     => 'pending',
            ]
        );

        $this->sendSessionNotification($session);

        $this->closeQuickSession();
        $this->dispatch('success', $isNew
            ? 'جلسه با موفقیت تعریف و به دانش‌آموز اطلاع داده شد.'
            : 'جلسه با موفقیت ویرایش شد و به دانش‌آموز اطلاع داده شد.');
    }

    public function studentDisplayName($student): string
    {
        $personalInfo = $student?->user?->personalInformation;
        $fullName = trim(($personalInfo?->name ?? '') . ' ' . ($personalInfo?->name_full ?? ''));

        return $fullName !== '' ? $fullName : ($student?->user?->name ?? 'دانش‌آموز');
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
        $this->dispatch('success', 'روزِ جلسه‌ی جبرانی تعیین شد. لطفاً ساعتِ آن را نیز از طریقِ «تعریف جلسه» ثبت کنید.');
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

    /**
     * ثبتِ دستیِ غیبتِ جلسه‌ی یک دانش‌آموز برای تاریخِ مشخص.
     * در صورتِ انتخابِ تاریخِ جبرانی، جلسه‌ی جبرانی مستقیماً برای همان تاریخ ساخته می‌شود؛
     * در غیرِ این‌صورت، جلسه‌ی جبرانیِ بدونِ تاریخ ساخته می‌شود تا بعداً تعیینِ روز شود.
     */
    public function markSessionAbsent(int $studentId, string $date): void
    {
        $student = Student::find($studentId);
        if (! $student || $student->advisor_id !== $this->adminId()) {
            $this->dispatch('error', 'این دانش‌آموز در فهرستِ شما نیست.');
            return;
        }

        if (! $this->ensureConsultationUnlocked($student)) {
            return;
        }

        $parsedDate = Carbon::parse($date);

        $session = AdvisingSession::firstOrNew([
            'advisor_id'      => $this->adminId(),
            'student_id'      => $studentId,
            'activation_date' => $parsedDate->toDateString(),
        ]);

        $session->fill([
            'title'         => $session->title ?: Jalalian::fromCarbon($parsedDate)->format('Y/m/d'),
            'description'   => $session->description ?: 'جلسه مشاوره فردی',
            'location_type' => $session->location_type ?: AdvisingSession::LOCATION_ONLINE,
            'status'        => AdvisingSession::STATUS_COMPLETED,
            'is_active'     => false,
            'finalized'     => true,
            'result_status' => AdvisingSession::RESULT_STUDENT_ABSENT,
        ])->save();

        $makeupDate = $this->rescheduleMakeupDate[$studentId] ?? null;
        if ($makeupDate && array_key_exists($makeupDate, $this->allowedMakeupDates())) {
            $this->createMakeupForAbsence($session, Carbon::parse($makeupDate)->toDateString());
            unset($this->rescheduleMakeupDate[$studentId]);
            $this->dispatch('success', 'غیبتِ جلسه ثبت شد و جلسه‌ی جبرانی برایِ تاریخِ انتخاب‌شده تعیین شد.');
        } else {
            $this->createMakeupForAbsence($session);
            $this->dispatch('success', 'غیبتِ جلسه ثبت شد و جلسه‌ی جبرانی در انتظارِ تعیینِ روز قرار گرفت.');
        }
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

    /** متدهای مربوط به داشبورد */
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
        AdvisingSession::markExpiredSessionsAsAdvisorAbsent();

        $adminId = $this->adminId();
        $today   = Carbon::today();
        $todayDow = ($today->dayOfWeek + 1) % 7;

        $lockedStudentIds = AdvisorOnboarding::where('advisor_id', $adminId)
            ->whereIn('status', $this->unresolvedOnboardingStatuses())
            ->pluck('student_id')
            ->unique()
            ->values();

        $studentsQuery = Student::query()
            ->where('advisor_id', $adminId)
            ->when($lockedStudentIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $lockedStudentIds))
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

        // فهرستِ کاملِ دانش‌آموزان برایِ انتخابگرِ مودالِ تعریفِ جلسه (مستقل از جستجویِ صفحه)
        $modalStudents = $studentsQuery->get();

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

        // نزدیک‌ترین تاریخِ وقوعِ هر روزِ هفته در ۷ روزِ آینده (شاملِ امروز)
        $dayDates = [];
        for ($d = 0; $d <= 6; $d++) {
            $dayDates[$d] = $this->dayOccurrenceDate($d);
        }
        $weekDateStrings = collect($dayDates)->map(fn ($c) => $c->toDateString())->values();

        // جلساتِ تعریف‌شده برایِ همانِ تاریخ‌های هفته (کلید: student_id)
        $weekSessions = AdvisingSession::where('advisor_id', $adminId)
            ->when($lockedStudentIds->isNotEmpty(), fn ($q) => $q->whereNotIn('student_id', $lockedStudentIds))
            ->whereIn('activation_date', $weekDateStrings)
            ->get()
            ->keyBy('student_id');

        $todaySessions = AdvisingSession::where('advisor_id', $adminId)
            ->when($lockedStudentIds->isNotEmpty(), fn ($q) => $q->whereNotIn('student_id', $lockedStudentIds))
            ->whereDate('activation_date', $today->toDateString())
            ->with('student.user.personalInformation')
            ->orderByRaw('session_time IS NULL')
            ->orderBy('session_time')
            ->orderBy('id')
            ->get();

        // جلساتِ جبرانیِ بدونِ تاریخ که منتظرِ تعیینِ روز هستند
        $pendingMakeups = AdvisingSession::where('advisor_id', $adminId)
            ->where('is_makeup', true)
            ->whereNull('activation_date')
            ->with('student.user.personalInformation')
            ->oldest()
            ->get();

        return view('livewire.admin.student.consultation.index', [
            'days'             => AdminWorkSchedule::DAYS,
            'todayDow'         => $todayDow,
            'dayDates'         => $dayDates,
            'grouped'          => $grouped,
            'allStudents'      => $allStudents,
            'modalStudents'    => $modalStudents,
            'noDayStudents'    => $allStudents
                ->filter(fn ($student) => $student->session_day === null && ! $activeMakeupsByStudent->has($student->id))
                ->values(),
            'weekSessions'     => $weekSessions,
            'todaySessions'    => $todaySessions,
            'pendingMakeups'   => $pendingMakeups,
            'allowedMakeupDates' => $this->allowedMakeupDates(),
            'activeMakeupsByStudent' => $activeMakeupsByStudent,
        ])->layout('layouts.admin.app');
    }
}
