<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AdvisingSession;
use App\Models\AdvisingPreSession;
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
            $subjectPriorities = $this->calculateSubjectPriorities($trialWeek->user_id);

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

            $this->generateProgramParts($program, $subjectPriorities, $dailyHours, $trialWeek->grade);

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

    private function calculateSubjectPriorities(int $userId): array
    {
        $classifications = StudentClassification::where('user_id', $userId)
            ->with('ratable')
            ->get();

        $subjects = [];
        foreach ($classifications as $c) {
            $ratable = $c->ratable;
            if ($ratable instanceof \App\Models\CcChapter) {
                $name = $ratable->subject?->name ?? 'سایر';
            } elseif ($ratable instanceof \App\Models\CcSubject) {
                $name = $ratable->name;
            } else {
                $name = 'سایر';
            }
            if (!isset($subjects[$name])) {
                $subjects[$name] = ['count' => 0, 'sum' => 0];
            }
            $subjects[$name]['count']++;
            $subjects[$name]['sum'] += $c->rating;
        }

        if (empty($subjects)) {
            return ['مطالعه عمومی' => 1.0];
        }

        $priorities = [];
        foreach ($subjects as $name => $data) {
            $avg = $data['count'] > 0 ? $data['sum'] / $data['count'] : 2.5;
            // درس‌های ضعیف‌تر اولویت بیشتر (مقیاس 1..4)
            $priorities[$name] = max(0.5, 5 - $avg);
        }

        $total = array_sum($priorities);
        foreach ($priorities as $name => $weight) {
            $priorities[$name] = $weight / $total;
        }

        arsort($priorities);
        return array_slice($priorities, 0, 6, true);
    }

    const MIN_PART_MINUTES = 15;  // حداقل ۱۵ دقیقه برای هر پارت
    const MIN_DAILY_MINUTES = 60; // حداقل ۱ ساعت مطالعه در روز

    private function generateProgramParts(WeeklyProgram $program, array $priorities, int $dailyHours, int $grade): void
    {
        $gradeForPart = match (true) {
            $grade >= 10 && $grade <= 12          => (string) $grade,
            $grade == TrialWeek::GRADE_GRADUATE    => '12', // فارغ‌التحصیل → مطالب پایه ۱۲
            default                                => '10', // پایه ۹ → از مطالب پایه ۱۰ شروع می‌کند
        };

        $subjectList = array_keys($priorities);
        $weightList  = array_values($priorities);
        $count = count($subjectList);

        // حداقل ۱ ساعت روزانه رعایت شود
        $dailyMinutes = max($dailyHours * 60, self::MIN_DAILY_MINUTES);

        for ($dayIdx = 0; $dayIdx < 7; $dayIdx++) {
            $date = Carbon::now()->addDays($dayIdx)->toDateString();
            $remainingMinutes = $dailyMinutes;
            $order = 1;
            $partsCreated = 0;

            foreach ($subjectList as $i => $subjectName) {
                $isLast = ($i === $count - 1);
                $minutes = $isLast
                    ? $remainingMinutes
                    : (int) round($weightList[$i] * $dailyMinutes);

                $remainingMinutes -= $minutes;

                // حداقل ۱۵ دقیقه برای هر پارت — اگر کمتر است به پارت بعدی اضافه شود
                if ($minutes < self::MIN_PART_MINUTES) {
                    // باقی‌مانده را به آخرین پارت اضافه می‌کنیم
                    $remainingMinutes += $minutes;
                    continue;
                }

                ProgramPart::create([
                    'weekly_program_id' => $program->id,
                    'lesson_name'       => $subjectName,
                    'part_date'         => $date,
                    'day_of_week'       => $dayIdx,
                    'part_order'        => $order++,
                    'duration_minutes'  => $minutes,
                    'part_type'         => 'descriptive',
                    'source_type'       => ProgramPart::SOURCE_DAILY_READING,
                    'lesson_type'       => 'specialized',
                    'grade'             => in_array($gradeForPart, ['10','11','12']) ? $gradeForPart : '10',
                ]);
                $partsCreated++;
            }

            // اگر هیچ پارتی ایجاد نشد ولی زمان باقی داشتیم، یک پارت پیش‌فرض بساز
            if ($partsCreated === 0 && $dailyMinutes >= self::MIN_PART_MINUTES) {
                ProgramPart::create([
                    'weekly_program_id' => $program->id,
                    'lesson_name'       => 'مطالعه روزانه',
                    'part_date'         => $date,
                    'day_of_week'       => $dayIdx,
                    'part_order'        => 1,
                    'duration_minutes'  => $dailyMinutes,
                    'part_type'         => 'descriptive',
                    'source_type'       => ProgramPart::SOURCE_DAILY_READING,
                    'lesson_type'       => 'specialized',
                    'grade'             => in_array($gradeForPart, ['10','11','12']) ? $gradeForPart : '10',
                ]);
            }
        }
    }
}
