<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AdvisingSession;
use App\Models\AdvisingPreSession;
use App\Models\ClassSchedule;
use App\Models\ProgramPart;
use App\Models\SmartReportCard;
use App\Models\Student;
use App\Models\StudentClassification;
use App\Models\TrialWeek;
use App\Models\User;
use App\Models\WeeklyProgram;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TrialWeekService
{
    // ایجاد هفته آزمایشی جدید + رکورد Student برای استفاده از سیستم موجود
    public function start(User $user, int $grade, ?string $field, string $fatherMobile, string $motherMobile, bool $attendsSchool = true): TrialWeek
    {
        $trial = DB::transaction(function () use ($user, $grade, $field, $fatherMobile, $motherMobile, $attendsSchool) {
            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                ['is_trial' => true],
            );

            return TrialWeek::create([
                'user_id'        => $user->id,
                'student_id'     => $student->id,
                'grade'          => $grade,
                'field'          => $grade == 9 ? null : $field,
                'attends_school' => $attendsSchool && $grade != TrialWeek::GRADE_GRADUATE,
                'father_mobile'  => $fatherMobile,
                'mother_mobile'  => $motherMobile,
                'status'         => TrialWeek::STATUS_PENDING,
                // شمارش ۸ روز فقط بعد از «ساخت برنامه» شروع می‌شود (buildProgram).
                'expires_at'     => null,
            ]);
        });

        // در مسیر جدید، آزمون‌ها قبل از ایجاد هفتهٔ آزمایشی تکمیل می‌شوند.
        if ($user->hasCompletedAllAssessments()) {
            $trial->update(['assessments_completed_at' => Carbon::now()]);
        }

        app(TrialLifecycleSmsService::class)->trySendTrialStarted($trial);

        return $trial;
    }

    /**
     * انتخاب خودکار «مشاور جذب» توسط سیستم: از بین ادمین‌های دارای نقش
     * «site acquisition»، کسی که کمترین دانش‌آموز (هفتهٔ آزمایشی) دارد.
     * جلسهٔ آزمایشی و پیش‌جلسه نیز همین‌جا ساخته می‌شوند.
     */
    public function autoAssignAcquisitionConsultant(TrialWeek $trialWeek): ?Admin
    {
        if ($trialWeek->status !== TrialWeek::STATUS_PENDING) {
            return $trialWeek->acquisitionSupporter;
        }

        $consultant = Admin::role('site acquisition')
            ->withCount('acquisitionTrialWeeks')
            ->orderBy('acquisition_trial_weeks_count')
            ->orderBy('id')
            ->first();

        DB::transaction(function () use ($trialWeek, $consultant) {
            $session = AdvisingSession::create([
                'student_id'      => $trialWeek->student_id,
                'advisor_id'      => null,
                'title'           => 'جلسه آزمایشی',
                'activation_date' => Carbon::now()->addDay(),
                'status'          => AdvisingSession::STATUS_ACTIVE,
                'location_type'   => 'online',
                'is_active'       => true,
            ]);

            AdvisingPreSession::create([
                'advising_session_id' => $session->id,
                'student_id'          => $trialWeek->student_id,
                'title'               => 'پیش‌جلسه آزمایشی',
                'status'              => AdvisingPreSession::STATUS_PENDING,
            ]);

            $trialWeek->update([
                'acquisition_supporter_id' => $consultant?->id,
                'advising_session_id'      => $session->id,
                'status'                   => TrialWeek::STATUS_SUPPORTER_ASSIGNED,
                'supporter_assigned_at'    => Carbon::now(),
            ]);
        });

        return $consultant;
    }

    // تخصیص «پشتیبان جذب» توسط مدیر آموزشی + ایجاد جلسهٔ آزمایشی
    public function assignSupporter(TrialWeek $trialWeek, Admin $supporter): void
    {
        DB::transaction(function () use ($trialWeek, $supporter) {
            $session = AdvisingSession::create([
                'student_id'      => $trialWeek->student_id,
                'advisor_id'      => null,
                'title'           => 'جلسه آزمایشی',
                'activation_date' => Carbon::now()->addDay(),
                'status'          => AdvisingSession::STATUS_ACTIVE,
                'location_type'   => 'online',
                'is_active'       => true,
            ]);

            AdvisingPreSession::create([
                'advising_session_id' => $session->id,
                'student_id'          => $trialWeek->student_id,
                'title'               => 'پیش‌جلسه آزمایشی',
                'status'              => AdvisingPreSession::STATUS_PENDING,
            ]);

            $trialWeek->update([
                'acquisition_supporter_id' => $supporter->id,
                'advising_session_id'      => $session->id,
                'status'                   => TrialWeek::STATUS_SUPPORTER_ASSIGNED,
                'supporter_assigned_at'    => Carbon::now(),
            ]);
        });
    }

    // قفل طبقه‌بندی پس از تایید دانش‌آموز
    public function lockClassification(TrialWeek $trialWeek): void
    {
        $trialWeek->update([
            'status'                   => TrialWeek::STATUS_CLASSIFICATION_DONE,
            'classification_locked_at' => Carbon::now(),
        ]);
    }

    // تکمیل پیش‌جلسه
    public function completePreSession(TrialWeek $trialWeek): void
    {
        $trialWeek->update([
            'status'                   => TrialWeek::STATUS_PRE_SESSION_DONE,
            'pre_session_completed_at' => Carbon::now(),
        ]);
    }

    // ساخت برنامه هفتگی آزمایشی
    public function buildProgram(TrialWeek $trialWeek, int $dailyHours): WeeklyProgram
    {
        return DB::transaction(function () use ($trialWeek, $dailyHours) {
            $now = Carbon::now();
            $programStart = $now->copy()->startOfDay();
            $programEnd = $programStart->copy()->addDays(self::TRIAL_PROGRAM_DAYS - 1);

            // برنامه به جلسهٔ آزمایشی پیوند می‌خورد و جلسه «برگزارشده»/زندهٔ امروز علامت می‌خورد تا
            // در /profile/plan و /profile/studySession و /profile/report (که روی result_status='held'
            // و جلسهٔ جاری فیلتر دارند) نمایش داده شود و دانش‌آموز بتواند گزارش بدهد و ساعت مطالعه ثبت کند.
            $session = $trialWeek->advisingSession;
            if ($session) {
                $session->update([
                    'result_status'   => AdvisingSession::RESULT_HELD,
                    'status'          => AdvisingSession::STATUS_COMPLETED,
                    'is_active'       => true,
                    'activation_date' => $now->toDateString(),
                    'session_time'    => $session->session_time ?? $now->format('H:i:s'),
                ]);
            }

            $program = WeeklyProgram::create([
                'student_id'          => $trialWeek->student_id,
                'advisor_id'          => null,
                'advising_session_id' => $session?->id,
                'start_date'          => $programStart->toDateString(),
                'end_date'            => $programEnd->toDateString(),
                'is_active'           => true,
            ]);

            $this->generateProgramParts($program, $trialWeek, $dailyHours);

            // آغاز رسمی هفتهٔ آزمایشی = لحظهٔ ساخت برنامه؛ مدت اعتبار ۸ روز.
            $trialWeek->update([
                'daily_study_hours' => $dailyHours,
                'status'            => TrialWeek::STATUS_PROGRAM_BUILT,
                'program_built_at'  => $now,
                'expires_at'        => self::trialAccessExpiresAt($now),
            ]);

            // کارنامهٔ هوشمند برای نمایش در /profile/reportStudentStudy (تحلیل زنده در طول هفتهٔ آزمایشی).
            $this->generateSmartReportCard($trialWeek, $program);

            return $program;
        });
    }

    public static function trialAccessExpiresAt(?Carbon $anchor = null): Carbon
    {
        $start = ($anchor ?: Carbon::now())->copy()->startOfDay();

        return $start->addDays(self::TRIAL_PROGRAM_DAYS - 1)->endOfDay();
    }

    /**
     * ساخت/به‌روزرسانی کارنامهٔ هوشمند برای دانش‌آموز آزمایشی.
     * بازهٔ تحلیل = از شروع برنامه تا پایان هفتهٔ آزمایشی. SmartReportCardShow به‌صورت زنده
     * از همین بازه آمار مطالعه/گزارش/کیفیت را محاسبه می‌کند.
     */
    public function generateSmartReportCard(TrialWeek $trialWeek, ?WeeklyProgram $program = null): ?SmartReportCard
    {
        $program = $program ?? WeeklyProgram::where('student_id', $trialWeek->student_id)
            ->where('is_active', true)
            ->latest('start_date')
            ->first();

        if (!$program) {
            return null;
        }

        $start = Carbon::parse($program->start_date)->startOfDay();
        $end   = $start->copy()->addDays(self::TRIAL_PROGRAM_DAYS - 1)->endOfDay();

        $jStart = jdate($start);

        return SmartReportCard::updateOrCreate(
            [
                'student_id'   => $trialWeek->student_id,
                'jalali_year'  => (int) $jStart->format('Y'),
                'jalali_month' => (int) $jStart->format('n'),
            ],
            [
                'admin_id'     => $trialWeek->acquisition_supporter_id,
                'start_date'   => $start->toDateString(),
                'end_date'     => $end->toDateString(),
                'is_active'    => true,
                'activated_at' => Carbon::now(),
            ]
        );
    }

    // تحلیل وضعیت طبقه‌بندی برای نمودار
    public function getClassificationAnalysis(int $userId): array
    {
        $classifications = StudentClassification::where('user_id', $userId)
            ->with('ratable')
            ->get();

        $ratingCounts = array_fill_keys(array_values(StudentClassification::RATINGS), 0);

        $subjectBreakdown = [];
        foreach ($classifications as $c) {
            $label = StudentClassification::RATINGS[$c->rating] ?? '?';
            if (isset($ratingCounts[$label])) {
                $ratingCounts[$label]++;
            }

            $ratable = $c->ratable;
            if ($ratable instanceof \App\Models\CcChapter) {
                $subjectName = $ratable->subject?->name ?? 'سایر';
            } elseif ($ratable instanceof \App\Models\CcSubject) {
                $subjectName = $ratable->name;
            } else {
                $subjectName = 'سایر';
            }

            if (!isset($subjectBreakdown[$subjectName])) {
                $subjectBreakdown[$subjectName] = ['total' => 0, 'sum' => 0];
            }
            $subjectBreakdown[$subjectName]['total']++;
            $subjectBreakdown[$subjectName]['sum'] += $c->rating;
        }

        $subjectAverages = [];
        foreach ($subjectBreakdown as $name => $data) {
            if ($data['total'] > 0) {
                $avg = $data['sum'] / $data['total'];
                $subjectAverages[$name] = [
                    'average' => round($avg, 1),
                    'count'   => $data['total'],
                    'label'   => StudentClassification::RATINGS[(int) round($avg)] ?? '?',
                ];
            }
        }

        arsort($subjectAverages);

        return [
            'total'            => $classifications->count(),
            'rating_counts'    => $ratingCounts,
            'subject_averages' => $subjectAverages,
            'weak_count'       => $classifications->where('rating', '<=', 1)->count(),
            'medium_count'     => $classifications->whereBetween('rating', [2, 3])->count(),
            'strong_count'     => $classifications->where('rating', '>=', 4)->count(),
        ];
    }

    const TRIAL_PROGRAM_DAYS = TrialWeek::PROGRAM_DAYS;   // امروز + ۷ روز بعد
    const MIN_PART_MINUTES = 15;    // حداقل قدیمی برای مسیرهای غیرآزمایشی
    const MIN_DAILY_MINUTES = 120; // حداقل ۲ ساعت مطالعه در روز

    // ───────── سهم پایهٔ هر سطل از ساعت مطالعهٔ روزانه (نرمال‌سازی دینامیک) ─────────
    const SHARE_EXAM     = 0.40; // آمادگی امتحان/پرسش‌وپاسخ (اولویت اول)
    const SHARE_SCHEDULE = 0.25; // روزخوانی/پیش‌خوانی/تکلیف برنامهٔ کلاسی
    const SHARE_TOPIC_A  = 0.15; // دستهٔ A → تست
    const SHARE_TOPIC_B  = 0.12; // دستهٔ B → تشریحی (آمادگی برای تست‌زنی)
    const SHARE_TOPIC_CD = 0.08; // دستهٔ C و D → مطالعهٔ فصل

    // نگاشت id درس → نوع (عمومی/تخصصی) برای تعیین lesson_type هر پارت
    private array $subjectTypeMap = [];
    // نگاشت id فصل/درس → دستهٔ طبقه‌بندی (A/B/C/D) برای رویدادهای امتحان
    private array $chapterCategoryMap = [];
    private array $subjectCategoryMap = [];

    /**
     * ساخت پارت‌های برنامهٔ هفتگی بر اساس الگوریتمِ امتحان‌محور + دسته‌بندی ABCD:
     *
     *   • شب قبل از امتحان/پرسش‌وپاسخ کلاسی، فقط همان درس(های) امتحان چیده می‌شود.
     *   • روزخوانی و پیش‌خوانی در «روز قبل» و «روز بعدِ» امتحان قرار نمی‌گیرد.
     *   • اگر فصلِ امتحان دستهٔ C یا D باشد، آمادگی از ۲ روز قبلِ امتحان شروع می‌شود
     *     (در غیر این صورت از ۱ روز قبل).
     *   • تکالیف فقط در روزهای «آزاد» (بدون امتحان و بدون پرسش‌وپاسخِ همان روز) قرار می‌گیرد.
     *   • مطالعهٔ دسته‌بندی در روزهای عادی: A → تست، B → تشریحی «آمادگی برای تست‌زنی»،
     *     C/D → تشریحی «مطالعهٔ فصل …».
     *
     * بازتوزیع دینامیک: سهم سطل‌های بدونِ محتوای آن روز بین بقیه پخش می‌شود تا
     * مجموع دقیقهٔ روز همیشه دقیقاً برابر ساعتِ انتخابی بماند.
     */
    private function generateProgramParts(WeeklyProgram $program, TrialWeek $trialWeek, int $dailyHours): void
    {
        $userId = (int) $trialWeek->user_id;

        $grade = (int) $trialWeek->grade;
        $gradeForPart = match (true) {
            $grade >= 10 && $grade <= 12        => (string) $grade,
            $grade === TrialWeek::GRADE_GRADUATE => '12', // فارغ‌التحصیل → مطالب پایهٔ ۱۲
            default                             => '10',  // پایهٔ ۹ → از مطالب پایهٔ ۱۰ شروع می‌کند
        };
        if (!in_array($gradeForPart, ['10', '11', '12'], true)) {
            $gradeForPart = '10';
        }

        // حداقل ۲ ساعت روزانه رعایت شود
        $dailyMinutes = max($dailyHours * 60, self::MIN_DAILY_MINUTES);
        $minPartMinutes = $this->minPartMinutesForDailyHours($dailyHours);

        // نگاشت نوع درس‌ها (عمومی/تخصصی) + نگاشت دستهٔ طبقه‌بندی کاربر
        $this->subjectTypeMap = \App\Models\CcSubject::pluck('type', 'id')->all();
        $this->loadCategoryMaps($userId);

        // مباحث طبقه‌بندی به تفکیک دسته (A=۴ ، B=۳ ، C=۲ ، D=۱)
        $topicsA  = $this->classificationTopics($userId, 4);
        $topicsB  = $this->classificationTopics($userId, 3);
        $topicsCD = array_merge(
            $this->classificationTopics($userId, 2),
            $this->classificationTopics($userId, 1),
        );
        $topicPools = [
            'a'  => $this->buildTopicDesired($topicsA,  'A'),
            'b'  => $this->buildTopicDesired($topicsB,  'B'),
            'cd' => $this->buildTopicDesired($topicsCD, 'CD'),
        ];
        $fallbackTopics = $this->fallbackCurriculumTopics($trialWeek);
        $topicCursors = ['a' => 0, 'b' => 0, 'cd' => 0, 'fallback' => 0];

        // برنامهٔ کلاسیِ نهایی‌شدهٔ مدرسه (فقط برای کسانی که مدرسه می‌روند)
        $schedule = null;
        if ($trialWeek->needsClassSchedule()) {
            $schedule = ClassSchedule::where('student_id', $trialWeek->student_id)
                ->where('is_finalized', true)
                ->with('parts.ccSubject')
                ->latest()
                ->first();
        }

        // رویدادهای امتحان و پرسش‌وپاسخ کلاسی (با تاریخ و دسته‌بندی)
        $events = $this->collectExamEvents($trialWeek);

        $startDay = Carbon::now()->startOfDay();
        $recentDayKeys = [];

        for ($dayIdx = 0; $dayIdx < self::TRIAL_PROGRAM_DAYS; $dayIdx++) {
            $date     = $startDay->copy()->addDays($dayIdx);
            $dateStr  = $date->toDateString();
            $jWeekday = jdate($date)->getDayOfWeek(); // 0=شنبه .. 6=جمعه

            $eventsToday     = $this->eventsOn($events, $date);
            $eventsTomorrow  = $this->eventsOn($events, $date->copy()->addDay());
            $eventsYesterday = $this->eventsOn($events, $date->copy()->subDay());

            // شب قبل از امتحان/پرسش‌وپاسخ → فقط همان درس(ها)
            $isNightBefore = !empty($eventsTomorrow);
            // روز قبل یا بعدِ امتحان → بدون روزخوانی/پیش‌خوانی
            $noDailyPre = !empty($eventsTomorrow) || !empty($eventsYesterday);
            // روز آزاد → تکلیف مجاز است
            $isFreeDay = empty($eventsToday);

            // درس‌های کلاسیِ همین روز (بدون تکرار درس)
            $scheduleSubjects = [];
            if ($schedule) {
                foreach ($schedule->parts->where('day_of_week', $jWeekday)->sortBy('part_order') as $cp) {
                    $scheduleSubjects[$cp->cc_subject_id] = $cp->lesson_name;
                }
            }

            // ساخت سطل‌های هر روز
            $bucketExam     = $this->buildExamDesired($events, $date);
            $bucketReading  = (!$isNightBefore && !$noDailyPre) ? $this->buildReadingDesired($scheduleSubjects) : [];
            $bucketHomework = (!$isNightBefore && $isFreeDay)   ? $this->buildHomeworkDesired($scheduleSubjects) : [];
            $bucketSchedule = array_merge($bucketReading, $bucketHomework);
            $bucketA  = $isNightBefore ? [] : $topicPools['a'];
            $bucketB  = $isNightBefore ? [] : $topicPools['b'];
            $bucketCD = $isNightBefore ? [] : $topicPools['cd'];
            $bucketFallback = $fallbackTopics;

            // سطل‌های دارای محتوا + سهم پایهٔ آن‌ها
            $shares  = [];
            $buckets = [];
            if (!empty($bucketExam))     { $shares['exam']     = self::SHARE_EXAM;     $buckets['exam']     = $bucketExam; }
            if (!empty($bucketSchedule)) { $shares['schedule'] = self::SHARE_SCHEDULE; $buckets['schedule'] = $bucketSchedule; }
            if (!empty($bucketA))        { $shares['a']        = self::SHARE_TOPIC_A;  $buckets['a']        = $bucketA; }
            if (!empty($bucketB))        { $shares['b']        = self::SHARE_TOPIC_B;  $buckets['b']        = $bucketB; }
            if (!empty($bucketCD))       { $shares['cd']       = self::SHARE_TOPIC_CD; $buckets['cd']       = $bucketCD; }
            if (!empty($bucketFallback)) { $shares['fallback'] = 0.03;                 $buckets['fallback'] = $bucketFallback; }

            // برنامهٔ آزمایشی نباید پارت کلی و بی‌درس بسازد.
            if (empty($shares)) {
                throw new \LogicException('برای ساخت برنامه آزمایشی، حداقل یک درس یا فصل فعال لازم است.');
            }

            $parts = $this->selectDailyParts(
                $buckets,
                $shares,
                $dailyMinutes,
                $minPartMinutes,
                $recentDayKeys,
                $eventsTomorrow,
                $topicCursors
            );

            $order = 1;
            $todayKeys = [];
            foreach ($parts as $p) {
                $this->createPart($program, $p, $p['minutes'], $dateStr, $dayIdx, $order++, $gradeForPart);
                $key = $this->partSequenceKey($p);
                if ($key) {
                    $todayKeys[$key] = true;
                }
            }
            $recentDayKeys[] = array_keys($todayKeys);
            $recentDayKeys = array_slice($recentDayKeys, -2);
        }
    }

    /**
     * رویدادهای امتحان و پرسش‌وپاسخ کلاسیِ پیش‌جلسه را با تاریخ و دستهٔ طبقه‌بندیِ
     * فصلِ مربوطه جمع می‌کند.
     */
    private function collectExamEvents(TrialWeek $trialWeek): array
    {
        $pre = $trialWeek->advisingSession?->preSession;
        if (!$pre) {
            return [];
        }

        $events = [];

        foreach ($pre->exams as $ex) {
            if (!$ex->exam_date) {
                continue;
            }
            $events[] = [
                'type'          => 'exam',
                'date'          => Carbon::parse($ex->exam_date)->startOfDay(),
                'subject'       => $ex->subject,
                'cc_subject_id' => $ex->cc_subject_id,
                'cc_chapter_id' => $ex->cc_chapter_id,
                'chapter'       => $this->chapterName($ex->cc_chapter_id),
                'category'      => $this->categoryOf($ex->cc_chapter_id, $ex->cc_subject_id),
            ];
        }

        foreach ($pre->qas as $qa) {
            if (!$qa->qa_date) {
                continue;
            }
            $events[] = [
                'type'          => 'qa',
                'date'          => Carbon::parse($qa->qa_date)->startOfDay(),
                'subject'       => $qa->subject,
                'cc_subject_id' => $qa->cc_subject_id,
                'cc_chapter_id' => $qa->cc_chapter_id,
                'chapter'       => $this->chapterName($qa->cc_chapter_id),
                'category'      => $this->categoryOf($qa->cc_chapter_id, $qa->cc_subject_id),
            ];
        }

        return $events;
    }

    /** رویدادهای واقع در یک تاریخ مشخص. */
    private function eventsOn(array $events, Carbon $date): array
    {
        return array_values(array_filter($events, fn ($e) => $e['date']->isSameDay($date)));
    }

    /**
     * سطل آمادگیِ امتحان/پرسش‌وپاسخ برای یک تاریخ:
     *   • اگر رویداد همین امروز است → خودِ امتحان/پرسش‌وپاسخ.
     *   • اگر امروز داخل بازهٔ آمادگیِ رویداد است → پارت آمادگی.
     *     بازهٔ آمادگی = [تاریخ - (۲ روز برای C/D وگرنه ۱ روز) ، تاریخ - ۱].
     */
    private function buildExamDesired(array $events, Carbon $date): array
    {
        $desired = [];
        foreach ($events as $e) {
            $isSameDay = $e['date']->isSameDay($date);
            $prepDays  = in_array($e['category'], ['C', 'D'], true) ? 2 : 1;
            $prepStart = $e['date']->copy()->subDays($prepDays);
            $prepEnd   = $e['date']->copy()->subDay();
            $inPrep    = $date->between($prepStart, $prepEnd);

            if (!$isSameDay && !$inPrep) {
                continue;
            }

            $source     = $e['type'] === 'exam' ? ProgramPart::SOURCE_EXAM : ProgramPart::SOURCE_CLASS_QA;
            $base       = $e['type'] === 'exam' ? 'امتحان' : 'پرسش و پاسخ کلاسی';
            $chapterTxt = $e['chapter'] ?: $e['subject'];
            $desc       = $isSameDay ? ($base . ' - ' . $chapterTxt) : ('آمادگی ' . $base . ' - ' . $chapterTxt);
            $partType   = $e['category'] === 'A'
                ? ProgramPart::PART_TYPE_TEST
                : ProgramPart::PART_TYPE_DESCRIPTIVE;

            $desired[] = [
                'lesson_name'   => $e['subject'],
                'source_type'   => $source,
                'part_type'     => $partType,
                'description'   => $desc,
                'cc_subject_id' => $e['cc_subject_id'],
                'cc_chapter_id' => $e['cc_chapter_id'],
                'is_hard_event' => $isSameDay,
            ];
        }

        return $desired;
    }

    /**
     * مباحث طبقه‌بندی با رتبهٔ مشخص → فهرست یکتای [name, chapter, cc_subject_id].
     * هر مبحث (فصل) یک‌بار می‌آید؛ name = نام درس، chapter = نام فصل.
     */
    private function classificationTopics(int $userId, int $rating): array
    {
        $rows = StudentClassification::where('user_id', $userId)
            ->where('rating', $rating)
            ->with('ratable')
            ->get();

        $topics = [];
        foreach ($rows as $c) {
            $ratable = $c->ratable;
            if ($ratable instanceof \App\Models\CcChapter) {
                $name    = $ratable->subject?->name ?? 'سایر';
                $chapter = $ratable->name;
                $subjId  = $ratable->cc_subject_id;
                $chapId  = $ratable->id;
                $subjectOrder = (int) ($ratable->subject?->order ?? 0);
                $chapterOrder = (int) ($ratable->order ?? 0);
            } elseif ($ratable instanceof \App\Models\CcSubject) {
                $name    = $ratable->name;
                $chapter = null;
                $subjId  = $ratable->id;
                $chapId  = null;
                $subjectOrder = (int) ($ratable->order ?? 0);
                $chapterOrder = 0;
            } else {
                continue;
            }
            $topics[get_class($ratable) . ':' . $ratable->id] = [
                'name'          => $name,
                'chapter'       => $chapter,
                'cc_subject_id' => $subjId,
                'cc_chapter_id' => $chapId,
                'subject_order' => $subjectOrder,
                'chapter_order' => $chapterOrder,
            ];
        }

        $topics = array_values($topics);
        usort($topics, fn ($a, $b) => [
            $a['subject_order'] ?? 0,
            $a['cc_subject_id'] ?? 0,
            $a['chapter_order'] ?? 0,
            $a['cc_chapter_id'] ?? 0,
        ] <=> [
            $b['subject_order'] ?? 0,
            $b['cc_subject_id'] ?? 0,
            $b['chapter_order'] ?? 0,
            $b['cc_chapter_id'] ?? 0,
        ]);

        return $topics;
    }

    /** روزخوانی و پیش‌خوانیِ هر درسِ کلاسیِ روز (نوع‌محور). */
    private function buildReadingDesired(array $scheduleSubjects): array
    {
        if (empty($scheduleSubjects)) {
            return [];
        }

        $types = [
            ProgramPart::SOURCE_DAILY_READING => 'روزخوانی',
            ProgramPart::SOURCE_PRE_READING   => 'پیش‌خوانی',
        ];

        $desired = [];
        foreach ($types as $sourceType => $label) {
            foreach ($scheduleSubjects as $subjectId => $subjectName) {
                $desired[] = [
                    'lesson_name'   => $subjectName,
                    'source_type'   => $sourceType,
                    'part_type'     => ProgramPart::PART_TYPE_DESCRIPTIVE,
                    'description'   => $label . ' - ' . $subjectName,
                    'cc_subject_id' => $subjectId ?: null,
                ];
            }
        }

        return $desired;
    }

    /** تکالیفِ هر درسِ کلاسیِ روز — فقط برای روزهای آزاد فراخوانی می‌شود. */
    private function buildHomeworkDesired(array $scheduleSubjects): array
    {
        if (empty($scheduleSubjects)) {
            return [];
        }

        $desired = [];
        foreach ($scheduleSubjects as $subjectId => $subjectName) {
            $desired[] = [
                'lesson_name'   => $subjectName,
                'source_type'   => ProgramPart::SOURCE_HOMEWORK,
                'part_type'     => ProgramPart::PART_TYPE_DESCRIPTIVE,
                'description'   => 'تکلیف - ' . $subjectName,
                'cc_subject_id' => $subjectId ?: null,
            ];
        }

        return $desired;
    }

    /**
     * یک پارت مطالعه برای هر مبحثِ دسته‌بندی‌شده، بر اساس دسته:
     *   A  → تست (part_type=test)
     *   B  → تشریحی با توضیح «مطالعه و آمادگی برای تست‌زنی»
     *   CD → تشریحی با توضیح «مطالعهٔ فصل …»
     */
    private function buildTopicDesired(array $topics, string $category): array
    {
        $desired = [];
        foreach ($topics as $idx => $t) {
            $chapterTxt = $t['chapter'] ?: $t['name'];

            switch ($category) {
                case 'A':
                    $partType = ProgramPart::PART_TYPE_TEST;
                    $desc     = 'تست‌زنی - ' . $chapterTxt;
                    break;
                case 'B':
                    $partType = ProgramPart::PART_TYPE_DESCRIPTIVE;
                    $desc     = 'مطالعه و آمادگی برای تست‌زنی - ' . $chapterTxt;
                    break;
                default: // C و D
                    $partType = ProgramPart::PART_TYPE_DESCRIPTIVE;
                    $desc     = 'مطالعهٔ فصل ' . $chapterTxt;
                    break;
            }

            $desired[] = [
                'lesson_name'   => $t['name'],
                'source_type'   => ProgramPart::SOURCE_CLASSIFICATION,
                'part_type'     => $partType,
                'description'   => $desc,
                'cc_subject_id' => $t['cc_subject_id'],
                'cc_chapter_id' => $t['cc_chapter_id'] ?? null,
                'topic_index'   => $idx,
            ];
        }

        return $desired;
    }

    private function fallbackCurriculumTopics(TrialWeek $trialWeek): array
    {
        $gradeNumber = match (true) {
            (int) $trialWeek->grade >= 10 && (int) $trialWeek->grade <= 12 => (int) $trialWeek->grade,
            (int) $trialWeek->grade === TrialWeek::GRADE_GRADUATE => 12,
            default => 10,
        };

        $fieldId = $trialWeek->field
            ? \App\Models\CcField::where('slug', $trialWeek->field)->value('id')
            : null;

        $ccGrade = \App\Models\CcGrade::where('grade_number', $gradeNumber)
            ->where('is_active', true)
            ->when($fieldId, fn ($q) => $q->where('cc_field_id', $fieldId))
            ->first()
            ?? \App\Models\CcGrade::where('grade_number', $gradeNumber)->where('is_active', true)->first();

        if (!$ccGrade) {
            return [];
        }

        $subjects = \App\Models\CcSubject::where('cc_grade_id', $ccGrade->id)
            ->where(function ($q) use ($fieldId) {
                $q->whereNull('cc_field_id');
                if ($fieldId) {
                    $q->orWhere('cc_field_id', $fieldId);
                }
            })
            ->with(['chapters' => fn ($q) => $q->active()->ordered()])
            ->ordered()
            ->get();

        $topics = [];
        foreach ($subjects as $subject) {
            if ($subject->chapters->isEmpty()) {
                $topics[] = [
                    'name'          => $subject->name,
                    'chapter'       => null,
                    'cc_subject_id' => $subject->id,
                    'cc_chapter_id' => null,
                    'subject_order' => (int) ($subject->order ?? 0),
                    'chapter_order' => 0,
                ];
                continue;
            }

            foreach ($subject->chapters as $chapter) {
                $topics[] = [
                    'name'          => $subject->name,
                    'chapter'       => $chapter->name,
                    'cc_subject_id' => $subject->id,
                    'cc_chapter_id' => $chapter->id,
                    'subject_order' => (int) ($subject->order ?? 0),
                    'chapter_order' => (int) ($chapter->order ?? 0),
                ];
            }
        }

        return $this->buildTopicDesired($topics, 'CD');
    }

    private function minPartMinutesForDailyHours(int $dailyHours): int
    {
        return match (true) {
            $dailyHours <= 2 => 40,
            $dailyHours <= 4 => 60,
            default          => 90,
        };
    }

    private function selectDailyParts(
        array $buckets,
        array $shares,
        int $dailyMinutes,
        int $minPartMinutes,
        array $recentDayKeys,
        array $eventsTomorrow,
        array &$topicCursors
    ): array {
        $maxParts = max(1, intdiv($dailyMinutes, $minPartMinutes));
        $counts = $this->allocatePartCounts($buckets, $shares, $maxParts);
        $tomorrowKeys = $this->eventKeys($eventsTomorrow);

        $parts = [];
        $usedStaticIndexes = [];

        foreach ($counts as $bucketKey => $count) {
            for ($i = 0; $i < $count; $i++) {
                $candidate = $this->nextCandidateForBucket(
                    $bucketKey,
                    $buckets[$bucketKey] ?? [],
                    $topicCursors,
                    $usedStaticIndexes,
                    $recentDayKeys,
                    $tomorrowKeys
                );

                if ($candidate) {
                    $parts[] = $candidate;
                }
            }
        }

        foreach (array_keys($buckets) as $bucketKey) {
            while (count($parts) < $maxParts) {
                $candidate = $this->nextCandidateForBucket(
                    $bucketKey,
                    $buckets[$bucketKey] ?? [],
                    $topicCursors,
                    $usedStaticIndexes,
                    $recentDayKeys,
                    $tomorrowKeys
                );

                if (!$candidate) {
                    break;
                }

                $parts[] = $candidate;
            }
        }

        if (count($parts) < $maxParts) {
            throw new \LogicException('محتوای درسی کافی برای ساخت پارت‌های استاندارد بدون تکرار سه‌روزه وجود ندارد.');
        }

        return $this->assignDailyMinutes($parts, $dailyMinutes);
    }

    private function allocatePartCounts(array $buckets, array $shares, int $maxParts): array
    {
        $priority = ['exam', 'schedule', 'a', 'b', 'cd', 'fallback'];
        $available = array_values(array_filter($priority, fn ($key) => !empty($buckets[$key] ?? [])));

        if (empty($available)) {
            return [];
        }

        $counts = [];
        foreach (array_slice($available, 0, $maxParts) as $key) {
            $counts[$key] = 1;
        }

        $remaining = $maxParts - array_sum($counts);
        while ($remaining > 0) {
            $best = null;
            $bestScore = -1;

            foreach ($available as $key) {
                $score = ($shares[$key] ?? 0) / (($counts[$key] ?? 0) + 1);
                if ($score > $bestScore) {
                    $best = $key;
                    $bestScore = $score;
                }
            }

            if ($best === null) {
                break;
            }

            $counts[$best] = ($counts[$best] ?? 0) + 1;
            $remaining--;
        }

        return $counts;
    }

    private function nextCandidateForBucket(
        string $bucketKey,
        array $bucket,
        array &$topicCursors,
        array &$usedStaticIndexes,
        array $recentDayKeys,
        array $tomorrowKeys
    ): ?array {
        if (empty($bucket)) {
            return null;
        }

        if (in_array($bucketKey, ['a', 'b', 'cd', 'fallback'], true)) {
            $count = count($bucket);
            $cursor = $topicCursors[$bucketKey] ?? 0;

            for ($offset = 0; $offset < $count; $offset++) {
                $idx = ($cursor + $offset) % $count;
                $candidate = $bucket[$idx];
                if ($this->breaksConsecutiveRule($candidate, $recentDayKeys, $tomorrowKeys)) {
                    continue;
                }

                $topicCursors[$bucketKey] = ($idx + 1) % $count;
                return $candidate;
            }

            return null;
        }

        $usedStaticIndexes[$bucketKey] ??= [];
        if (count($usedStaticIndexes[$bucketKey]) >= count($bucket)) {
            $usedStaticIndexes[$bucketKey] = [];
        }

        foreach ($bucket as $idx => $candidate) {
            if (in_array($idx, $usedStaticIndexes[$bucketKey], true)) {
                continue;
            }
            if ($this->breaksConsecutiveRule($candidate, $recentDayKeys, $tomorrowKeys)) {
                continue;
            }

            $usedStaticIndexes[$bucketKey][] = $idx;
            return $candidate;
        }

        return null;
    }

    private function assignDailyMinutes(array $parts, int $dailyMinutes): array
    {
        $count = count($parts);
        $base = intdiv($dailyMinutes, $count);
        $remainder = $dailyMinutes - ($base * $count);

        foreach ($parts as $idx => $part) {
            $parts[$idx]['minutes'] = $base + ($idx === $count - 1 ? $remainder : 0);
        }

        return $parts;
    }

    private function breaksConsecutiveRule(array $candidate, array $recentDayKeys, array $tomorrowKeys): bool
    {
        if (($candidate['is_hard_event'] ?? false) === true) {
            return false;
        }

        $key = $this->partSequenceKey($candidate);
        if (!$key) {
            return false;
        }

        $yesterday = $recentDayKeys[count($recentDayKeys) - 1] ?? [];
        $twoDaysAgo = $recentDayKeys[count($recentDayKeys) - 2] ?? [];

        if (in_array($key, $yesterday, true) && in_array($key, $twoDaysAgo, true)) {
            return true;
        }

        return in_array($key, $yesterday, true) && in_array($key, $tomorrowKeys, true);
    }

    private function eventKeys(array $events): array
    {
        return array_values(array_filter(array_map(fn ($event) => $this->partSequenceKey($event), $events)));
    }

    private function partSequenceKey(array $part): ?string
    {
        if (!empty($part['cc_chapter_id'])) {
            return 'chapter:' . $part['cc_chapter_id'];
        }

        if (!empty($part['cc_subject_id'])) {
            return 'subject:' . $part['cc_subject_id'];
        }

        $lesson = trim((string) ($part['lesson_name'] ?? $part['subject'] ?? ''));
        return $lesson !== '' ? 'lesson:' . mb_strtolower($lesson) : null;
    }

    /**
     * تبدیل سهم‌های نسبی به دقیقهٔ صحیح که جمعشان دقیقاً برابر $dailyMinutes است.
     * سهم‌ها بین سطل‌های موجود نرمال می‌شوند (بازتوزیع دینامیک).
     */
    private function allocateBudgets(array $shares, int $dailyMinutes): array
    {
        $total = array_sum($shares);
        $keys  = array_keys($shares);
        $last  = count($keys) - 1;

        $budgets = [];
        $acc = 0;
        foreach ($keys as $i => $key) {
            if ($i === $last) {
                $budgets[$key] = $dailyMinutes - $acc; // باقیمانده → جمع دقیق
            } else {
                $m = (int) round($shares[$key] / $total * $dailyMinutes);
                $budgets[$key] = $m;
                $acc += $m;
            }
        }

        return $budgets;
    }

    /**
     * تقسیم بودجهٔ یک سطل بین پارت‌های مطلوبش با رعایت حداقل ۱۵ دقیقه برای هر پارت.
     * اگر بودجه برای همهٔ پارت‌ها کافی نباشد، تعداد پارت‌ها به اندازهٔ بودجه محدود می‌شود.
     */
    private function distributeWithinBucket(array $desired, int $budget): array
    {
        if (empty($desired) || $budget <= 0) {
            return [];
        }

        $maxParts = max(1, intdiv($budget, self::MIN_PART_MINUTES));
        $n        = min(count($desired), $maxParts);
        $chosen   = array_slice($desired, 0, $n);

        $base      = intdiv($budget, $n);
        $remainder = $budget - $base * $n;

        $out = [];
        foreach ($chosen as $idx => $d) {
            $d['minutes'] = $base + ($idx === $n - 1 ? $remainder : 0);
            $out[] = $d;
        }

        return $out;
    }

    private function createPart(WeeklyProgram $program, array $d, int $minutes, string $dateStr, int $dayIdx, int $order, string $grade): void
    {
        $partType = $d['part_type'] ?? ProgramPart::PART_TYPE_DESCRIPTIVE;

        // نوع درس (عمومی/تخصصی) بر اساس نگاشتِ درس‌ها
        $subjectId  = $d['cc_subject_id'] ?? null;
        $lessonType = ($subjectId && ($this->subjectTypeMap[$subjectId] ?? null) === 'general')
            ? ProgramPart::LESSON_TYPE_GENERAL
            : ProgramPart::LESSON_TYPE_SPECIALIZED;

        // تعداد تست برای پارت‌های تستی بر اساس اندازهٔ پارت انتخاب‌شده در هفتهٔ آزمایشی.
        $testCount = $partType === ProgramPart::PART_TYPE_TEST
            ? match (true) {
                $minutes >= 90 => 30,
                $minutes >= 60 => 20,
                $minutes >= 40 => 10,
                default        => max(5, (int) round($minutes / 1.5)),
            }
            : null;

        ProgramPart::create([
            'weekly_program_id' => $program->id,
            'lesson_name'       => $d['lesson_name'],
            'part_date'         => $dateStr,
            'day_of_week'       => $dayIdx,
            'part_order'        => $order,
            'description'       => $d['description'] ?? null,
            'duration_minutes'  => $minutes,
            'test_count'        => $testCount,
            'part_type'         => $partType,
            'source_type'       => $d['source_type'],
            'lesson_type'       => $lessonType,
            'cc_subject_id'     => $subjectId,
            'cc_chapter_id'     => $d['cc_chapter_id'] ?? null,
            'grade'             => $grade,
        ]);
    }

    /**
     * نگاشتِ دستهٔ طبقه‌بندیِ کاربر برای فصل‌ها و درس‌ها (A/B/C/D).
     * یک‌بار در ابتدای ساخت برنامه بارگذاری می‌شود.
     */
    private function loadCategoryMaps(int $userId): void
    {
        $this->chapterCategoryMap = [];
        $this->subjectCategoryMap = [];

        $rows = StudentClassification::where('user_id', $userId)->with('ratable')->get();
        foreach ($rows as $c) {
            $ratable = $c->ratable;
            $label   = StudentClassification::RATINGS[(int) $c->rating] ?? null;
            if (!$label) {
                continue;
            }
            if ($ratable instanceof \App\Models\CcChapter) {
                $this->chapterCategoryMap[$ratable->id] = $label;
            } elseif ($ratable instanceof \App\Models\CcSubject) {
                $this->subjectCategoryMap[$ratable->id] = $label;
            }
        }
    }

    /** دستهٔ طبقه‌بندی (A/B/C/D) برای یک فصل/درس؛ ابتدا فصل، سپس درس. */
    private function categoryOf(?int $chapterId, ?int $subjectId): ?string
    {
        if ($chapterId && isset($this->chapterCategoryMap[$chapterId])) {
            return $this->chapterCategoryMap[$chapterId];
        }
        if ($subjectId && isset($this->subjectCategoryMap[$subjectId])) {
            return $this->subjectCategoryMap[$subjectId];
        }
        return null;
    }

    /** نامِ فصل از روی شناسه. */
    private function chapterName(?int $chapterId): ?string
    {
        if (!$chapterId) {
            return null;
        }
        return \App\Models\CcChapter::find($chapterId)?->name;
    }
}
