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

        // در مسیر جدید، آزمون‌ها قبل از ایجاد هفتهٔ آزمایشی تکمیل می‌شوند؛
        // اگر تکمیل شده باشند همین‌جا ثبت و لینک تست والدین ارسال می‌شود.
        if ($user->hasCompletedAllAssessments()) {
            $trial->update(['assessments_completed_at' => Carbon::now()]);
            app(ParentInvitationService::class)->sendForTrialWeek($trial);
        }

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
    // گِیت فاز ۲: حداقل یک والد باید تست‌های والدینی را تکمیل کرده باشد.
    public function assignSupporter(TrialWeek $trialWeek, Admin $supporter): void
    {
        if (! $trialWeek->hasAnyParentCompleted()) {
            throw new \LogicException('برای تخصیص پشتیبان، حداقل یک والد باید تست‌های والدینی را تکمیل کرده باشد.');
        }

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
            // برنامه به جلسهٔ آزمایشی پیوند می‌خورد و جلسه «برگزارشده»/زندهٔ امروز علامت می‌خورد تا
            // در /profile/plan و /profile/studySession و /profile/report (که روی result_status='held'
            // و جلسهٔ جاری فیلتر دارند) نمایش داده شود و دانش‌آموز بتواند گزارش بدهد و ساعت مطالعه ثبت کند.
            $session = $trialWeek->advisingSession;
            if ($session) {
                $session->update([
                    'result_status'   => AdvisingSession::RESULT_HELD,
                    'status'          => AdvisingSession::STATUS_COMPLETED,
                    'is_active'       => true,
                    'activation_date' => Carbon::now()->toDateString(),
                    'session_time'    => $session->session_time ?? Carbon::now()->format('H:i:s'),
                ]);
            }

            $program = WeeklyProgram::create([
                'student_id'          => $trialWeek->student_id,
                'advisor_id'          => null,
                'advising_session_id' => $session?->id,
                'start_date'          => Carbon::now()->toDateString(),
                'end_date'            => Carbon::now()->addDays(6)->toDateString(),
                'is_active'           => true,
            ]);

            $this->generateProgramParts($program, $trialWeek, $dailyHours);

            // آغاز رسمی هفتهٔ آزمایشی = لحظهٔ ساخت برنامه؛ مدت اعتبار ۸ روز.
            $trialWeek->update([
                'daily_study_hours' => $dailyHours,
                'status'            => TrialWeek::STATUS_PROGRAM_BUILT,
                'program_built_at'  => Carbon::now(),
                'expires_at'        => Carbon::now()->addDays(8),
            ]);

            // کارنامهٔ هوشمند برای نمایش در /profile/reportStudentStudy (تحلیل زنده در طول هفتهٔ آزمایشی).
            $this->generateSmartReportCard($trialWeek, $program);

            return $program;
        });
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
        $end   = ($trialWeek->expires_at ? Carbon::parse($trialWeek->expires_at) : $start->copy()->addDays(7))->endOfDay();

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

    const MIN_PART_MINUTES = 15;  // حداقل ۱۵ دقیقه برای هر پارت
    const MIN_DAILY_MINUTES = 60; // حداقل ۱ ساعت مطالعه در روز

    // ───────── سهم پایهٔ هر اولویت از ساعت مطالعهٔ روزانه (مجموع = ۱) ─────────
    const SHARE_SCHEDULE = 0.50; // اولویت ۱: برنامهٔ کلاسی مدرسه
    const SHARE_TOPIC_B  = 0.25; // اولویت ۲: مباحث B (رتبهٔ ۳)
    const SHARE_TOPIC_A  = 0.25; // اولویت ۳: مباحث A (رتبهٔ ۴)

    // انواع پارت‌های «برنامهٔ کلاسی مدرسه» که برای هر درسِ آن روز ساخته می‌شوند
    const SCHEDULE_PART_TYPES = [
        ProgramPart::SOURCE_DAILY_READING => 'روزخوانی',
        ProgramPart::SOURCE_PRE_READING   => 'پیش‌خوانی',
        ProgramPart::SOURCE_HOMEWORK      => 'تکلیف',
        ProgramPart::SOURCE_CLASS_QA      => 'پرسش و پاسخ کلاسی',
    ];

    /**
     * ساخت پارت‌های برنامهٔ هفتگی بر اساس الگوریتم درصدیِ اولویت‌محور:
     *   اولویت ۱ (۵۰٪): برنامهٔ کلاسی مدرسه (روزخوانی/پیش‌خوانی/تکلیف/پرسش‌وپاسخ هر درسِ روز)
     *   اولویت ۲ (۲۵٪): مطالعهٔ مباحث B (رتبهٔ ۳ طبقه‌بندی)
     *   اولویت ۳ (۲۵٪): مطالعهٔ مباحث A (رتبهٔ ۴ طبقه‌بندی)
     *
     * بازتوزیع دینامیک: اگر یک سطل برای آن روز محتوا نداشته باشد (مثلاً فارغ‌التحصیل
     * بدون برنامهٔ کلاسی، یا روزی بدون کلاس)، سهمش به‌نسبتِ سهمِ پایه بین سطل‌های باقی‌مانده
     * پخش می‌شود تا مجموع همیشه ۱۰۰٪ بماند (دو ۲۵٪ به دو ۵۰٪ تبدیل می‌شوند).
     */
    private function generateProgramParts(WeeklyProgram $program, TrialWeek $trialWeek, int $dailyHours): void
    {
        $grade = (int) $trialWeek->grade;
        $gradeForPart = match (true) {
            $grade >= 10 && $grade <= 12        => (string) $grade,
            $grade === TrialWeek::GRADE_GRADUATE => '12', // فارغ‌التحصیل → مطالب پایهٔ ۱۲
            default                             => '10',  // پایهٔ ۹ → از مطالب پایهٔ ۱۰ شروع می‌کند
        };
        if (!in_array($gradeForPart, ['10', '11', '12'], true)) {
            $gradeForPart = '10';
        }

        // حداقل ۱ ساعت روزانه رعایت شود
        $dailyMinutes = max($dailyHours * 60, self::MIN_DAILY_MINUTES);

        // مباحث طبقه‌بندی به تفکیک رتبه (A = ۴ ، B = ۳)
        $topicsA = $this->classificationTopics($trialWeek->user_id, 4); // مباحث A
        $topicsB = $this->classificationTopics($trialWeek->user_id, 3); // مباحث B

        // برنامهٔ کلاسیِ نهایی‌شدهٔ مدرسه (فقط برای کسانی که مدرسه می‌روند)
        $schedule = null;
        if ($trialWeek->needsClassSchedule()) {
            $schedule = ClassSchedule::where('student_id', $trialWeek->student_id)
                ->where('is_finalized', true)
                ->with('parts.ccSubject')
                ->latest()
                ->first();
        }

        for ($dayIdx = 0; $dayIdx < 7; $dayIdx++) {
            $date     = Carbon::now()->addDays($dayIdx);
            $dateStr  = $date->toDateString();
            $jWeekday = jdate($date)->getDayOfWeek(); // 0=شنبه .. 6=جمعه

            // اولویت ۱: درس‌های کلاسیِ همین روز (بدون تکرار درس)
            $scheduleSubjects = [];
            if ($schedule) {
                foreach ($schedule->parts->where('day_of_week', $jWeekday)->sortBy('part_order') as $cp) {
                    $scheduleSubjects[$cp->cc_subject_id] = $cp->lesson_name;
                }
            }

            // فهرست «پارت‌های مطلوب» هر سطل (هنوز بدون دقیقه)
            $bucketSchedule = $this->buildScheduleDesired($scheduleSubjects);
            $bucketB        = $this->buildTopicDesired($topicsB, 'مباحث B');
            $bucketA        = $this->buildTopicDesired($topicsA, 'مباحث A');

            // سطل‌های دارای محتوا + سهم پایهٔ آن‌ها
            $shares  = [];
            $buckets = [];
            if (!empty($bucketSchedule)) { $shares['schedule'] = self::SHARE_SCHEDULE; $buckets['schedule'] = $bucketSchedule; }
            if (!empty($bucketB))        { $shares['b']        = self::SHARE_TOPIC_B;  $buckets['b']        = $bucketB; }
            if (!empty($bucketA))        { $shares['a']        = self::SHARE_TOPIC_A;  $buckets['a']        = $bucketA; }

            // اگر هیچ سطلی محتوا نداشت → یک پارت پیش‌فرض برای کل روز
            if (empty($shares)) {
                $this->createPart($program, [
                    'lesson_name'   => 'مطالعه روزانه',
                    'source_type'   => ProgramPart::SOURCE_DAILY_READING,
                    'description'   => null,
                    'cc_subject_id' => null,
                ], $dailyMinutes, $dateStr, $dayIdx, 1, $gradeForPart);
                continue;
            }

            // بازتوزیع دینامیک سهم‌ها → دقیقهٔ صحیح که جمعشان دقیقاً = ساعتِ انتخابی
            $budgets = $this->allocateBudgets($shares, $dailyMinutes);

            $parts = [];
            foreach ($budgets as $key => $budget) {
                foreach ($this->distributeWithinBucket($buckets[$key], $budget) as $p) {
                    $parts[] = $p;
                }
            }

            // اطمینان از برابریِ دقیقِ مجموع روز با ساعتِ انتخابی (باقیمانده به آخرین پارت)
            $sum = array_sum(array_column($parts, 'minutes'));
            if (!empty($parts) && $sum !== $dailyMinutes) {
                $parts[count($parts) - 1]['minutes'] += ($dailyMinutes - $sum);
            }

            $order = 1;
            foreach ($parts as $p) {
                $this->createPart($program, $p, $p['minutes'], $dateStr, $dayIdx, $order++, $gradeForPart);
            }
        }
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
            } elseif ($ratable instanceof \App\Models\CcSubject) {
                $name    = $ratable->name;
                $chapter = null;
                $subjId  = $ratable->id;
            } else {
                continue;
            }
            $topics[get_class($ratable) . ':' . $ratable->id] = [
                'name'          => $name,
                'chapter'       => $chapter,
                'cc_subject_id' => $subjId,
            ];
        }

        return array_values($topics);
    }

    /**
     * اولویت ۱: برای هر درسِ کلاسیِ روز، چهار نوع پارت (روزخوانی/پیش‌خوانی/تکلیف/پرسش‌وپاسخ).
     * ترتیب «نوع‌محور» است تا اگر بودجه کم بود، ابتدا روزخوانیِ همهٔ درس‌ها پوشش داده شود.
     */
    private function buildScheduleDesired(array $scheduleSubjects): array
    {
        if (empty($scheduleSubjects)) {
            return [];
        }

        $desired = [];
        foreach (self::SCHEDULE_PART_TYPES as $sourceType => $label) {
            foreach ($scheduleSubjects as $subjectId => $subjectName) {
                $desired[] = [
                    'lesson_name'   => $subjectName,
                    'source_type'   => $sourceType,
                    'description'   => $label . ' - ' . $subjectName,
                    'cc_subject_id' => $subjectId ?: null,
                ];
            }
        }

        return $desired;
    }

    /** اولویت ۲/۳: یک پارت طبقه‌بندی برای هر مبحثِ رتبه‌بندی‌شده. */
    private function buildTopicDesired(array $topics, string $label): array
    {
        $desired = [];
        foreach ($topics as $t) {
            $desired[] = [
                'lesson_name'   => $t['name'],
                'source_type'   => ProgramPart::SOURCE_CLASSIFICATION,
                'description'   => $label . ($t['chapter'] ? ' - ' . $t['chapter'] : ''),
                'cc_subject_id' => $t['cc_subject_id'],
            ];
        }

        return $desired;
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
        ProgramPart::create([
            'weekly_program_id' => $program->id,
            'lesson_name'       => $d['lesson_name'],
            'part_date'         => $dateStr,
            'day_of_week'       => $dayIdx,
            'part_order'        => $order,
            'description'       => $d['description'] ?? null,
            'duration_minutes'  => $minutes,
            'part_type'         => ProgramPart::PART_TYPE_DESCRIPTIVE,
            'source_type'       => $d['source_type'],
            'lesson_type'       => ProgramPart::LESSON_TYPE_SPECIALIZED,
            'cc_subject_id'     => $d['cc_subject_id'] ?? null,
            'grade'             => $grade,
        ]);
    }
}
