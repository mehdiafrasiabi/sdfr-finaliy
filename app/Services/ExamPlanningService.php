<?php

namespace App\Services;

use App\Models\AdvisingSession;
use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\ExamPlanningSetting;
use App\Models\ProgramPart;
use App\Models\StudentExamSchedule;
use App\Models\StudentExamScheduleDay;
use App\Models\StudentExamStudyAllocation;
use App\Models\TrialWeek;
use App\Models\User;
use App\Models\WeeklyProgram;
use App\Models\WeeklyProgramExamDay;
use App\Models\WeeklyProgramRestDay;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LogicException;

class ExamPlanningService
{
    public const ACCESS_PAID = 'paid';
    public const ACCESS_TRIAL = 'trial';

    private const ALLOCATION_STEP_MINUTES = 30;
    private const PART_MINUTES = [90, 60, 30];

    public function resolveAcademicProfile(User $user): ?array
    {
        $student = $user->student;
        if (! $student || $user->isSchoolStudent()) {
            return null;
        }

        $info = $user->personalInformation;
        $trial = $user->trialWeek;

        $grade = null;
        $field = null;

        if ($info) {
            $grade = $info->is_graduate
                ? TrialWeek::GRADE_GRADUATE
                : ($info->grade !== null ? (int) $info->grade : null);
            $field = $info->field;
        }

        if (! $grade && $trial) {
            $grade = (int) $trial->grade;
            $field = $trial->field;
        }

        if (! $grade) {
            return null;
        }

        if ((int) $grade === 9) {
            $field = null;
        }

        return [
            'user' => $user,
            'student' => $student,
            'trial' => $trial,
            'grade' => (int) $grade,
            'field' => $field,
            'curriculum_grade' => (int) $grade === TrialWeek::GRADE_GRADUATE ? 12 : max((int) $grade, 9),
        ];
    }

    public function resolveExamAccess(User $user): array
    {
        $profile = $this->resolveAcademicProfile($user);
        if (! $profile) {
            return ['mode' => null, 'setting' => null, 'profile' => null];
        }

        $setting = $this->resolveActiveSettingForProfile($profile);
        if (! $setting) {
            return ['mode' => null, 'setting' => null, 'profile' => $profile];
        }

        $student = $profile['student'];
        $trial = $profile['trial'];
        $paidAccess = ! $student->is_trial && $student->hasActivePaidAccess();
        $trialAccess = ! $paidAccess
            && $trial
            && (! $trial->expires_at || $trial->expires_at->isFuture());

        return [
            'mode' => $paidAccess ? self::ACCESS_PAID : ($trialAccess ? self::ACCESS_TRIAL : null),
            'setting' => $setting,
            'profile' => $profile,
        ];
    }

    public function resolveActiveSettingForUser(User $user): ?ExamPlanningSetting
    {
        $profile = $this->resolveAcademicProfile($user);

        return $profile ? $this->resolveActiveSettingForProfile($profile) : null;
    }

    public function resolveActiveSettingForGradeField(int $grade, ?string $field): ?ExamPlanningSetting
    {
        $grade = (int) $grade;
        $field = $grade === 9 ? null : $field;

        return ExamPlanningSetting::query()
            ->active()
            ->windowOpen(now())
            ->where('grade', $grade)
            ->when(
                $grade === 9,
                fn ($query) => $query->whereNull('field'),
                fn ($query) => $query->where('field', $field)
            )
            ->orderByDesc('activation_ends_at')
            ->orderByDesc('id')
            ->first();
    }

    public function resolveActiveSettingForProfile(array $profile): ?ExamPlanningSetting
    {
        return $this->resolveActiveSettingForGradeField((int) $profile['grade'], $profile['field'] ?? null);
    }

    public function shouldExposePaidModule(User $user): bool
    {
        return $this->resolveExamAccess($user)['mode'] === self::ACCESS_PAID;
    }

    public function shouldExposeTrialModule(User $user): bool
    {
        return $this->resolveExamAccess($user)['mode'] === self::ACCESS_TRIAL;
    }

    /**
     * دانش‌آموز آزمایشی که برنامه‌ی امتحانی‌اش ساخته شده است، نباید
     * به بخش‌های جانبی طبقه‌بندی/کارنامه هوشمند/جلسات دسترسی نمایشی داشته باشد.
     */
    public function shouldHideTrialExamProgramSections(User $user): bool
    {
        $student = $user->student;
        $trial = $user->trialWeek;
        $hasBuiltExamProgram = $user->examSchedules()
            ->whereNotNull('weekly_program_id')
            ->whereNotNull('program_built_at')
            ->exists();

        return (bool) (
            $student
            && $student->is_trial
            && ! $student->hasActivePaidAccess()
            && $trial
            && $hasBuiltExamProgram
        );
    }

    public function prepareScheduleForUser(User $user): ?StudentExamSchedule
    {
        $access = $this->resolveExamAccess($user);
        $setting = $access['setting'];
        $profile = $access['profile'];

        if (! $setting || ! $profile) {
            return null;
        }

        $schedule = StudentExamSchedule::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'exam_planning_setting_id' => $setting->id,
            ],
            [
                'student_id' => $profile['student']->id,
                'source_type' => StudentExamSchedule::SOURCE_STUDENT,
                'max_daily_study_hours' => (int) $setting->max_daily_study_hours,
            ]
        );

        if ((int) $schedule->student_id !== (int) $profile['student']->id) {
            $schedule->student_id = $profile['student']->id;
        }

        if ((int) $schedule->max_daily_study_hours !== (int) $setting->max_daily_study_hours) {
            $schedule->max_daily_study_hours = (int) $setting->max_daily_study_hours;
        }

        if ($setting->managerCalendarReady()) {
            $this->syncManagerCalendar($schedule, $setting);
        } elseif ($schedule->isDirty()) {
            $schedule->save();
        }

        return $schedule->fresh(['days.subject', 'allocations', 'setting.days.subject']);
    }

    public function saveStudentCalendar(StudentExamSchedule $schedule, string $startDate, string $endDate): StudentExamSchedule
    {
        $schedule->update([
            'source_type' => StudentExamSchedule::SOURCE_STUDENT,
            'exam_starts_at' => $startDate,
            'exam_ends_at' => $endDate,
            'submitted_at' => null,
        ]);

        return $schedule->fresh(['days.subject', 'allocations', 'setting.days.subject']);
    }

    public function addStudentExamDay(StudentExamSchedule $schedule, string $examDate, int $subjectId): StudentExamScheduleDay
    {
        if (! $schedule->exam_starts_at || ! $schedule->exam_ends_at) {
            throw new LogicException('ابتدا بازه امتحانات را ثبت کنید.');
        }

        $examCarbon = Carbon::parse($examDate);
        if ($examCarbon->lt(Carbon::parse($schedule->exam_starts_at)) || $examCarbon->gt(Carbon::parse($schedule->exam_ends_at))) {
            throw new LogicException('تاریخ انتخاب‌شده خارج از بازه امتحانات ثبت‌شده است.');
        }

        if ($schedule->days()->where('cc_subject_id', $subjectId)->exists()) {
            throw new LogicException('برای این درس قبلاً تاریخ امتحان ثبت شده است.');
        }

        if ($schedule->days()->whereDate('exam_date', $examDate)->exists()) {
            throw new LogicException('برای این روز قبلاً یک امتحان ثبت شده است. هر روز فقط می‌تواند یک درس داشته باشد.');
        }

        $day = $schedule->days()->create([
            'exam_date' => $examDate,
            'cc_subject_id' => $subjectId,
        ]);

        $schedule->forceFill(['submitted_at' => null])->save();

        return $day;
    }

    public function removeStudentExamDay(StudentExamSchedule $schedule, int $dayId): void
    {
        $day = $schedule->days()->findOrFail($dayId);
        $subjectId = (int) $day->cc_subject_id;

        DB::transaction(function () use ($day, $schedule, $subjectId) {
            $day->delete();

            $schedule->allocations()
                ->where('cc_subject_id', $subjectId)
                ->delete();

            $schedule->update([
                'submitted_at' => null,
            ]);
        });
    }

    public function finalizeStudentCalendar(StudentExamSchedule $schedule): StudentExamSchedule
    {
        $schedule->loadMissing('days.subject');

        if ($schedule->source_type === StudentExamSchedule::SOURCE_MANAGER) {
            return $schedule->fresh(['days.subject', 'allocations', 'setting.days.subject']);
        }

        if (! $schedule->exam_starts_at || ! $schedule->exam_ends_at) {
            throw new LogicException('ابتدا بازه امتحانات را ثبت کنید.');
        }

        if (! $schedule->days()->exists()) {
            throw new LogicException('حداقل یک امتحان باید ثبت شده باشد.');
        }

        $schedule->update([
            'submitted_at' => now(),
        ]);

        return $schedule->fresh(['days.subject', 'allocations', 'setting.days.subject']);
    }

    public function reopenStudentCalendar(StudentExamSchedule $schedule): StudentExamSchedule
    {
        if ($schedule->source_type === StudentExamSchedule::SOURCE_MANAGER) {
            throw new LogicException('تقویمی که از طرف مدیر ثبت شده، از اینجا قابل ویرایش نیست.');
        }

        $updates = ['submitted_at' => null];
        $lastExamDate = $schedule->days()->max('exam_date');

        if ($schedule->exam_starts_at && $schedule->exam_ends_at && $lastExamDate) {
            $start = Carbon::parse($schedule->exam_starts_at);
            $end = Carbon::parse($schedule->exam_ends_at);
            $lastExam = Carbon::parse($lastExamDate);

            if ($end->lte($lastExam)) {
                $updates['exam_ends_at'] = $lastExam
                    ->copy()
                    ->addDays(6)
                    ->min($start->copy()->addDays(39))
                    ->toDateString();
            }
        }

        $schedule->update($updates);

        return $schedule->fresh(['days.subject', 'allocations', 'setting.days.subject']);
    }

    public function resetStudentCalendar(StudentExamSchedule $schedule): StudentExamSchedule
    {
        DB::transaction(function () use ($schedule) {
            $schedule->days()->delete();
            $schedule->allocations()->delete();

            $schedule->update([
                'source_type' => StudentExamSchedule::SOURCE_STUDENT,
                'exam_starts_at' => null,
                'exam_ends_at' => null,
                'submitted_at' => null,
                'program_built_at' => null,
                'access_expires_at' => null,
            ]);
        });

        return $schedule->fresh(['days.subject', 'allocations', 'setting.days.subject']);
    }

    public function curriculumForUser(User $user): array
    {
        $profile = $this->resolveAcademicProfile($user);

        return $profile ? $this->curriculumForProfile($profile) : $this->emptyCurriculum();
    }

    public function curriculumForSetting(ExamPlanningSetting $setting): array
    {
        $curriculumGrade = (int) $setting->grade === TrialWeek::GRADE_GRADUATE ? 12 : (int) $setting->grade;
        $fieldId = $setting->field ? CcField::where('slug', $setting->field)->value('id') : null;

        return $this->curriculumForGradeAndField($curriculumGrade, $fieldId);
    }

    public function curriculumForProfile(array $profile): array
    {
        $fieldId = $profile['field'] ? CcField::where('slug', $profile['field'])->value('id') : null;

        return $this->curriculumForGradeAndField((int) $profile['curriculum_grade'], $fieldId);
    }

    public function buildCapacityData(StudentExamSchedule $schedule): array
    {
        $schedule->loadMissing(['days.subject', 'allocations.ratable']);

        $days = $schedule->days
            ->sortBy(fn ($day) => $day->exam_date->toDateString())
            ->values();

        $allocationTotals = $schedule->allocations
            ->groupBy('cc_subject_id')
            ->map(fn (Collection $items) => (int) $items->sum('planned_minutes'))
            ->all();

        $segments = [];
        $subjects = [];
        $previousExamDate = null;
        $today = Carbon::today();
        $dailyMinutes = (int) $schedule->max_daily_study_hours * 60;

        foreach ($days->groupBy(fn ($day) => $day->exam_date->toDateString()) as $examDate => $group) {
            $examCarbon = Carbon::parse($examDate);
            $segmentStart = $previousExamDate
                ? Carbon::parse($previousExamDate)->addDay()
                : $today->copy();
            if ($segmentStart->lt($today)) {
                $segmentStart = $today->copy();
            }
            $segmentEnd = $examCarbon->copy()->subDay();
            $dayCount = $segmentEnd->lt($segmentStart) ? 0 : $segmentStart->diffInDays($segmentEnd) + 1;
            $capacityMinutes = $dayCount * $dailyMinutes;
            $segmentSubjectIds = $group->pluck('cc_subject_id')->map(fn ($id) => (int) $id)->values();
            $enteredMinutes = (int) $segmentSubjectIds->sum(fn ($subjectId) => $allocationTotals[$subjectId] ?? 0);
            $segmentKey = 'segment_' . $examCarbon->format('Ymd');

            $segments[$segmentKey] = [
                'key' => $segmentKey,
                'exam_date' => $examCarbon,
                'starts_at' => $segmentStart,
                'ends_at' => $segmentEnd,
                'day_count' => $dayCount,
                'capacity_minutes' => $capacityMinutes,
                'entered_minutes' => $enteredMinutes,
                'remaining_minutes' => max(0, $capacityMinutes - $enteredMinutes),
                'subject_ids' => $segmentSubjectIds->all(),
            ];

            foreach ($group as $day) {
                $subjectId = (int) $day->cc_subject_id;
                $subjects[$subjectId] = [
                    'segment_key' => $segmentKey,
                    'exam_date' => $examCarbon,
                    'capacity_minutes' => $capacityMinutes,
                    'subject_entered_minutes' => (int) ($allocationTotals[$subjectId] ?? 0),
                    'segment_entered_minutes' => $enteredMinutes,
                    'remaining_minutes' => max(0, $capacityMinutes - $enteredMinutes),
                    'day_count' => $dayCount,
                ];
            }

            $previousExamDate = $examDate;
        }

        return [
            'segments' => $segments,
            'subjects' => $subjects,
        ];
    }

    public function missingStudyChapters(StudentExamSchedule $schedule): array
    {
        $schedule->loadMissing(['days.subject', 'allocations.ratable']);

        $subjects = $schedule->days
            ->sortBy(fn ($day) => $day->exam_date?->toDateString())
            ->map(fn ($day) => $day->subject)
            ->filter()
            ->unique('id')
            ->values();

        $missing = [];

        foreach ($subjects as $subject) {
            $chapters = $subject->chapters()->active()->ordered()->get();

            if ($chapters->isEmpty()) {
                continue;
            }

            $hasWholeAllocation = $schedule->allocations
                ->where('cc_subject_id', $subject->id)
                ->contains(fn ($allocation) => $allocation->ratable_type === CcSubject::class && (int) $allocation->planned_minutes > 0);

            if ($hasWholeAllocation) {
                continue;
            }

            $chapterMinutes = [];

            foreach ($schedule->allocations->where('cc_subject_id', $subject->id) as $allocation) {
                $plannedMinutes = (int) $allocation->planned_minutes;

                if ($plannedMinutes <= 0) {
                    continue;
                }

                if ($allocation->ratable_type === CcChapter::class) {
                    $chapterMinutes[(int) $allocation->ratable_id] = ($chapterMinutes[(int) $allocation->ratable_id] ?? 0) + $plannedMinutes;
                    continue;
                }

                if ($allocation->ratable_type === CcSubject::class) {
                    foreach ($this->splitMinutesAcrossChapters($plannedMinutes, $chapters) as $chapterAllocation) {
                        $chapterId = (int) $chapterAllocation['cc_chapter_id'];
                        $chapterMinutes[$chapterId] = ($chapterMinutes[$chapterId] ?? 0) + (int) $chapterAllocation['remaining_minutes'];
                    }
                }
            }

            $missingChapters = $chapters
                ->filter(fn (CcChapter $chapter) => (int) ($chapterMinutes[$chapter->id] ?? 0) <= 0)
                ->pluck('name')
                ->values()
                ->all();

            if (! empty($missingChapters)) {
                $missing[] = [
                    'subject' => $subject->name,
                    'chapters' => $missingChapters,
                ];
            }
        }

        return $missing;
    }

    public function upsertAllocation(
        StudentExamSchedule $schedule,
        string $ratableType,
        int $ratableId,
        int $subjectId,
        int $minutes,
        bool $isPriority = false
    ): StudentExamStudyAllocation {
        $minutes = max(0, (int) $minutes);
        $roundedMinutes = (int) (round($minutes / self::ALLOCATION_STEP_MINUTES) * self::ALLOCATION_STEP_MINUTES);
        if ($ratableType === CcChapter::class) {
            $roundedMinutes = max(self::ALLOCATION_STEP_MINUTES, $roundedMinutes);
        }
        $currentAllocation = StudentExamStudyAllocation::query()
            ->where('student_exam_schedule_id', $schedule->id)
            ->where('ratable_type', $ratableType)
            ->where('ratable_id', $ratableId)
            ->first();

        $capacityData = $this->buildCapacityData($schedule->fresh(['days.subject', 'allocations.ratable']));
        $subjectCapacity = $capacityData['subjects'][$subjectId] ?? null;

        if (! $subjectCapacity) {
            throw new LogicException('برای این درس هنوز تاریخ امتحان ثبت نشده است.');
        }

        $currentMinutes = (int) ($currentAllocation?->planned_minutes ?? 0);
        $segmentOtherMinutes = (int) $subjectCapacity['segment_entered_minutes'] - $currentMinutes;
        $maxAllowedMinutes = max(0, (int) $subjectCapacity['capacity_minutes'] - $segmentOtherMinutes);

        if ($roundedMinutes > $currentMinutes && $roundedMinutes > $maxAllowedMinutes) {
            throw new LogicException('ظرفیت این بازه امتحانی کامل شده است و امکان ثبت ساعت بیشتر وجود ندارد.');
        }

        return StudentExamStudyAllocation::query()->updateOrCreate(
            [
                'student_exam_schedule_id' => $schedule->id,
                'ratable_type' => $ratableType,
                'ratable_id' => $ratableId,
            ],
            [
                'cc_subject_id' => $subjectId,
                'planned_minutes' => $roundedMinutes,
                'is_priority_subject' => $isPriority,
            ]
        );
    }

    public function removeAllocation(StudentExamSchedule $schedule, string $ratableType, int $ratableId): void
    {
        $schedule->allocations()
            ->where('ratable_type', $ratableType)
            ->where('ratable_id', $ratableId)
            ->delete();
    }

    public function setPrioritySubject(StudentExamSchedule $schedule, int $subjectId, bool $enabled): void
    {
        $subjectAllocation = StudentExamStudyAllocation::query()->firstOrNew([
            'student_exam_schedule_id' => $schedule->id,
            'ratable_type' => CcSubject::class,
            'ratable_id' => $subjectId,
        ]);

        $subjectAllocation->cc_subject_id = $subjectId;
        $subjectAllocation->planned_minutes = (int) ($subjectAllocation->planned_minutes ?? 0);
        $subjectAllocation->is_priority_subject = $enabled;

        if (! $enabled && (int) $subjectAllocation->planned_minutes === 0) {
            if ($subjectAllocation->exists) {
                $subjectAllocation->delete();
            }

            return;
        }

        $subjectAllocation->save();
    }

    public function setWholeSubjectAllocation(StudentExamSchedule $schedule, int $subjectId, int $minutes): void
    {
        $schedule->allocations()
            ->where('cc_subject_id', $subjectId)
            ->where('ratable_type', CcChapter::class)
            ->delete();

        $existingSubjectAllocation = StudentExamStudyAllocation::query()
            ->where('student_exam_schedule_id', $schedule->id)
            ->where('ratable_type', CcSubject::class)
            ->where('ratable_id', $subjectId)
            ->first();

        $this->upsertAllocation(
            $schedule,
            CcSubject::class,
            $subjectId,
            $subjectId,
            $minutes,
            (bool) ($existingSubjectAllocation?->is_priority_subject ?? false)
        );
    }

    public function switchSubjectToChapterMode(StudentExamSchedule $schedule, int $subjectId): void
    {
        $subjectAllocation = StudentExamStudyAllocation::query()
            ->where('student_exam_schedule_id', $schedule->id)
            ->where('ratable_type', CcSubject::class)
            ->where('ratable_id', $subjectId)
            ->first();

        if (! $subjectAllocation) {
            return;
        }

        if ($subjectAllocation->is_priority_subject) {
            $subjectAllocation->planned_minutes = 0;
            $subjectAllocation->save();
            return;
        }

        $subjectAllocation->delete();
    }

    public function buildProgram(StudentExamSchedule $schedule): WeeklyProgram
    {
        $schedule->loadMissing([
            'user.student.advisor',
            'user.personalInformation',
            'user.trialWeek',
            'setting',
            'weeklyProgram',
            'days.subject.grade.educationLevel',
            'allocations.ratable',
        ]);

        if ($schedule->weeklyProgram) {
            return $schedule->weeklyProgram;
        }

        $capacityData = $this->buildCapacityData($schedule);
        $this->assertBuildable($schedule, $capacityData);

        $program = DB::transaction(function () use ($schedule, $capacityData) {
            $user = $schedule->user;
            $student = $schedule->student;
            $trial = $user->trialWeek;
            $profile = $this->resolveAcademicProfile($user);
            $advisorId = $student->advisor_id;
            $session = $this->resolveProgramSession($user, $trial, $student, $advisorId);

            WeeklyProgram::query()
                ->where('student_id', $student->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $lastExamDate = $schedule->days()->max('exam_date');
            $program = WeeklyProgram::create([
                'student_id' => $student->id,
                'advisor_id' => $advisorId,
                'advising_session_id' => $session->id,
                'start_date' => Carbon::today()->toDateString(),
                'end_date' => Carbon::parse($lastExamDate)->toDateString(),
                'is_active' => true,
            ]);

            $this->createExamProgramDays($program, $schedule);
            $this->createExamProgramParts($program, $schedule, $profile, $capacityData);

            $accessExpiresAt = Carbon::parse($lastExamDate)->addDay()->endOfDay();

            $schedule->update([
                'weekly_program_id' => $program->id,
                'program_built_at' => now(),
                'access_expires_at' => $accessExpiresAt,
            ]);

            if ($trial && ! $student->hasActivePaidAccess()) {
                $trialProgramBuiltAt = $trial->program_built_at ?: now();

                $trial->update([
                    'advising_session_id' => $session->id,
                    'daily_study_hours' => $schedule->max_daily_study_hours,
                    'status' => TrialWeek::STATUS_PROGRAM_BUILT,
                    'program_built_at' => $trialProgramBuiltAt,
                    'expires_at' => $accessExpiresAt,
                ]);

                app(TrialWeekService::class)->generateSmartReportCard($trial, $program);
            }

            return $program;
        });

        app(TrialLifecycleSmsService::class)->trySendExamProgramStarted(
            $schedule->fresh(['user', 'student'])
        );

        return $program;
    }

    public function subjectOptionsForUser(User $user): array
    {
        $curriculum = $this->curriculumForUser($user);

        return collect($curriculum['specialized_subjects'])
            ->concat($curriculum['general_subjects'])
            ->map(fn (array $subject) => [
                'id' => $subject['id'],
                'name' => $subject['name'],
                'type' => $subject['type'],
            ])
            ->values()
            ->all();
    }

    private function syncManagerCalendar(StudentExamSchedule $schedule, ExamPlanningSetting $setting): void
    {
        $schedule->source_type = StudentExamSchedule::SOURCE_MANAGER;
        $schedule->max_daily_study_hours = (int) $setting->max_daily_study_hours;
        $schedule->exam_starts_at = $setting->exam_starts_at;
        $schedule->exam_ends_at = $setting->exam_ends_at;
        $schedule->submitted_at = now();
        $schedule->save();

        $existingIds = $schedule->days()->pluck('id', 'cc_subject_id');
        $keptSubjects = [];

        foreach ($setting->days as $day) {
            $keptSubjects[] = (int) $day->cc_subject_id;

            StudentExamScheduleDay::query()->updateOrCreate(
                [
                    'student_exam_schedule_id' => $schedule->id,
                    'cc_subject_id' => $day->cc_subject_id,
                ],
                [
                    'exam_date' => $day->exam_date,
                ]
            );
        }

        $schedule->days()
            ->whereNotIn('cc_subject_id', $keptSubjects)
            ->delete();

        if (! empty($keptSubjects)) {
            $schedule->allocations()
                ->whereNotIn('cc_subject_id', $keptSubjects)
                ->delete();
        }
    }

    private function curriculumForGradeAndField(int $gradeNumber, ?int $fieldId): array
    {
        $gradeQuery = CcGrade::query()
            ->where('grade_number', $gradeNumber)
            ->where('is_active', true);

        $grade = $fieldId
            ? (clone $gradeQuery)->where('cc_field_id', $fieldId)->first()
            : null;
        $grade = $grade ?: $gradeQuery->first();

        if (! $grade) {
            return $this->emptyCurriculum();
        }

        $generalSubjects = CcSubject::query()
            ->where('cc_grade_id', $grade->id)
            ->where('type', 'general')
            ->with(['chapters' => fn ($query) => $query->active()->ordered()])
            ->ordered()
            ->get()
            ->map(fn (CcSubject $subject) => [
                'id' => $subject->id,
                'name' => $subject->name,
                'type' => 'general',
                'chapters' => $this->chapterOptions($subject),
            ])
            ->values()
            ->all();

        $specializedSubjects = CcSubject::query()
            ->where('cc_grade_id', $grade->id)
            ->where('type', 'specialized')
            ->where(function ($query) use ($fieldId) {
                $query->whereNull('cc_field_id');

                if ($fieldId) {
                    $query->orWhere('cc_field_id', $fieldId);
                }
            })
            ->with(['chapters' => fn ($query) => $query->active()->ordered()])
            ->ordered()
            ->get()
            ->map(fn (CcSubject $subject) => [
                'id' => $subject->id,
                'name' => $subject->name,
                'type' => 'specialized',
                'chapters' => $this->chapterOptions($subject),
            ])
            ->values()
            ->all();

        return [
            'grade' => $grade,
            'general_subjects' => $generalSubjects,
            'specialized_subjects' => $specializedSubjects,
        ];
    }

    private function emptyCurriculum(): array
    {
        return [
            'grade' => null,
            'general_subjects' => [],
            'specialized_subjects' => [],
        ];
    }

    private function chapterOptions(CcSubject $subject): array
    {
        return $subject->chapters
            ->map(fn (CcChapter $chapter) => [
                'id' => $chapter->id,
                'name' => $chapter->name,
                'order' => (int) $chapter->order,
            ])
            ->values()
            ->all();
    }

    private function assertBuildable(StudentExamSchedule $schedule, array $capacityData): void
    {
        if (! $schedule->hasCalendar()) {
            throw new LogicException('ابتدا باید تقویم امتحانی را کامل کنید.');
        }

        if (
            $schedule->source_type === StudentExamSchedule::SOURCE_STUDENT
            && ! $schedule->submitted_at
        ) {
            throw new LogicException('ابتدا باید تقویم امتحاناتت را ثبت نهایی کنی.');
        }

        if (! $schedule->days()->exists()) {
            throw new LogicException('حداقل یک امتحان باید ثبت شده باشد.');
        }

        $today = Carbon::today();
        $firstExamDate = $schedule->days
            ->sortBy(fn ($day) => $day->exam_date?->toDateString())
            ->first()?->exam_date;

        if ($firstExamDate && $firstExamDate->lt($today->copy()->addDay())) {
            throw new LogicException('اولین امتحان باید حداقل از فردا به بعد باشد.');
        }

        foreach ($capacityData['segments'] as $segment) {
            if ($segment['capacity_minutes'] <= 0) {
                throw new LogicException('برای یکی از بازه‌های امتحانی هیچ روز مطالعه‌ای باقی نمانده است.');
            }

            if ($segment['entered_minutes'] > $segment['capacity_minutes']) {
                throw new LogicException('جمع ساعت‌های ثبت‌شده برای یکی از بازه‌های امتحانی از ظرفیت قابل مطالعه بیشتر است.');
            }

            if (count($segment['subject_ids']) * 90 > ((int) $schedule->max_daily_study_hours * 60)) {
                throw new LogicException('تعداد امتحان‌های یک روز آن‌قدر زیاد است که پارت اجباری ۹۰ دقیقه‌ای شب قبل در ظرفیت روزانه جا نمی‌شود.');
            }
        }

        $subjectIds = $schedule->days->pluck('cc_subject_id')->map(fn ($id) => (int) $id)->unique();
        $allocations = $schedule->allocations
            ->groupBy('cc_subject_id')
            ->map(fn (Collection $items) => (int) $items->sum('planned_minutes'));

        foreach ($subjectIds as $subjectId) {
            if (($allocations[$subjectId] ?? 0) <= 0) {
                $subject = $schedule->days->firstWhere('cc_subject_id', $subjectId)?->subject?->name ?? 'این درس';
                throw new LogicException("برای «{$subject}» هنوز ساعت مطالعه ثبت نشده است.");
            }
        }

        $missingChapters = $this->missingStudyChapters($schedule);

        if (! empty($missingChapters)) {
            $messages = collect($missingChapters)
                ->map(function (array $item) {
                    return 'برای «' . $item['subject'] . '» فصل‌های «' . implode('»، «', $item['chapters']) . '» هنوز ساعت مطالعه ثبت نشده است.';
                })
                ->all();

            throw new LogicException(implode(' ', $messages));
        }
    }

    private function resolveProgramSession(User $user, ?TrialWeek $trial, $student, ?int $advisorId): AdvisingSession
    {
        if ($trial && ! $student->hasActivePaidAccess()) {
            if ($trial->status === TrialWeek::STATUS_PENDING && ! $trial->acquisition_supporter_id) {
                app(TrialWeekService::class)->autoAssignAcquisitionConsultant($trial);
                $trial->refresh();
            }

            if ($trial->advisingSession) {
                $trial->advisingSession->update([
                    'result_status' => AdvisingSession::RESULT_HELD,
                    'status' => AdvisingSession::STATUS_COMPLETED,
                    'is_active' => true,
                    'activation_date' => Carbon::today()->toDateString(),
                    'session_time' => $trial->advisingSession->session_time ?? now()->format('H:i:s'),
                ]);

                return $trial->advisingSession->fresh();
            }
        }

        $session = AdvisingSession::query()
            ->where('student_id', $student->id)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->orderByDesc('activation_date')
            ->orderByDesc('id')
            ->first();

        if ($session) {
            return $session;
        }

        return AdvisingSession::create([
            'student_id' => $student->id,
            'advisor_id' => $advisorId,
            'title' => 'برنامه امتحانات دانش‌آموز',
            'activation_date' => Carbon::today()->toDateString(),
            'status' => AdvisingSession::STATUS_COMPLETED,
            'location_type' => AdvisingSession::LOCATION_ONLINE,
            'result_status' => AdvisingSession::RESULT_HELD,
            'is_active' => true,
            'session_time' => now()->format('H:i:s'),
        ]);
    }

    private function createExamProgramDays(WeeklyProgram $program, StudentExamSchedule $schedule): void
    {
        $start = Carbon::parse($program->start_date);

        $examDates = $schedule->days
            ->map(fn ($day) => $day->exam_date?->toDateString())
            ->filter()
            ->unique()
            ->sort()
            ->values();
        $lastExamDate = $examDates->last();

        foreach ($examDates as $examDate) {
            $index = $start->diffInDays(Carbon::parse($examDate));

            WeeklyProgramExamDay::create([
                'weekly_program_id' => $program->id,
                'day_index' => $index,
            ]);

            if ($examDate === $lastExamDate) {
                WeeklyProgramRestDay::create([
                    'weekly_program_id' => $program->id,
                    'day_index' => $index,
                ]);
            }
        }
    }

    private function createExamProgramParts(
        WeeklyProgram $program,
        StudentExamSchedule $schedule,
        ?array $profile,
        array $capacityData
    ): void {
        $startDate = Carbon::parse($program->start_date);
        $dailyMinutes = (int) $schedule->max_daily_study_hours * 60;
        $scheduledSubjectIds = $this->scheduledSubjectIds($schedule);
        $allocationPool = $this->buildAllocationPool($schedule, $scheduledSubjectIds);
        $prioritySubjects = $this->buildPriorityFillers($schedule, $scheduledSubjectIds);
        $partOrderMap = [];
        $priorityCursorMap = [];
        $subjectLabels = $schedule->days->pluck('subject.name', 'cc_subject_id');
        $examDatesBySubject = $schedule->days
            ->mapWithKeys(fn ($day) => [(int) $day->cc_subject_id => $day->exam_date?->toDateString()]);

        foreach ($capacityData['segments'] as $segment) {
            $segmentStart = $segment['starts_at'];
            $segmentEnd = $segment['ends_at'];

            if ($segmentEnd->lt($segmentStart)) {
                continue;
            }

            $examDate = $segment['exam_date'];
            $subjectIds = collect($segment['subject_ids'])
                ->map(fn ($id) => (int) $id)
                ->filter(fn (int $subjectId) => $scheduledSubjectIds->contains($subjectId))
                ->values()
                ->all();

            for ($date = $segmentStart->copy(); $date->lte($segmentEnd); $date->addDay()) {
                $dayRemaining = $dailyMinutes;
                $dayIndex = $startDate->diffInDays($date);
                $nightBeforeExam = $date->isSameDay($examDate->copy()->subDay());

                if ($nightBeforeExam) {
                    foreach ($subjectIds as $subjectId) {
                        $subjectName = $subjectLabels[$subjectId] ?? 'درس';

                        $this->createProgramPart(
                            $program,
                            $date,
                            $partOrderMap,
                            90,
                            $subjectId,
                            null,
                            'حل نمونه سوال تشریحی - ' . $subjectName,
                            ProgramPart::PART_MODE_WHOLE_BOOK,
                            $profile,
                            $dayIndex
                        );

                        $dayRemaining -= 90;
                    }
                }

                if ($dayRemaining < 0) {
                    throw new LogicException('ظرفیت روز قبل از امتحان برای پارت اجباری کافی نیست.');
                }

                if ($dayRemaining > 0) {
                    $dayRemaining = $this->fillDayFromSubjects(
                        $program,
                        $date,
                        $partOrderMap,
                        $dayRemaining,
                        $subjectIds,
                        $allocationPool,
                        $nightBeforeExam ? $subjectIds : [],
                        $profile,
                        $dayIndex
                    );
                }

                if ($dayRemaining > 0) {
                    $allowedPriority = collect($nightBeforeExam ? $subjectIds : $prioritySubjects['subject_ids'])
                        ->filter(function (int $subjectId) use ($date, $examDatesBySubject) {
                            $examDate = $examDatesBySubject[$subjectId] ?? null;

                            return $examDate && Carbon::parse($examDate)->gt($date);
                        })
                        ->values()
                        ->all();

                    $dayRemaining = $this->fillDayWithPriorityReview(
                        $program,
                        $date,
                        $partOrderMap,
                        $dayRemaining,
                        $allowedPriority,
                        $prioritySubjects['fillers'],
                        $priorityCursorMap,
                        $profile,
                        $dayIndex
                    );
                }
            }

            foreach ($subjectIds as $subjectId) {
                foreach ($allocationPool[$subjectId] ?? [] as $item) {
                    if (($item['remaining_minutes'] ?? 0) > 0) {
                        $subjectName = $subjectLabels[$subjectId] ?? 'درس';
                        throw new LogicException("هنوز بخشی از ساعت‌های «{$subjectName}» در برنامه جا نشده است.");
                    }
                }
            }
        }
    }

    private function buildAllocationPool(StudentExamSchedule $schedule, Collection $scheduledSubjectIds): array
    {
        $schedule->loadMissing('allocations.ratable');
        $pool = [];

        foreach ($schedule->allocations as $allocation) {
            if ((int) $allocation->planned_minutes <= 0) {
                continue;
            }

            $subjectId = (int) ($allocation->cc_subject_id ?: 0);
            if ($subjectId <= 0) {
                continue;
            }

            if (! $scheduledSubjectIds->contains($subjectId)) {
                continue;
            }

            $ratable = $allocation->ratable;
            $description = null;
            $partMode = ProgramPart::PART_MODE_NORMAL;
            $chapterId = null;
            $chapterOrder = 0;

            if ($ratable instanceof CcChapter) {
                $chapterId = $ratable->id;
                $chapterOrder = (int) $ratable->order;
                $description = 'مطالعه ' . $ratable->name;
            } elseif ($ratable instanceof CcSubject) {
                $chapters = $ratable->chapters()->active()->ordered()->get();

                if ($chapters->isEmpty()) {
                    throw new LogicException("برای «{$ratable->name}» فصل فعالی پیدا نشد و امکان ساخت پارت بدون فصل وجود ندارد.");
                }

                foreach ($this->splitMinutesAcrossChapters((int) $allocation->planned_minutes, $chapters) as $chapterAllocation) {
                    $pool[$subjectId][] = $chapterAllocation;
                }

                continue;
            } else {
                continue;
            }

            $pool[$subjectId][] = [
                'cc_subject_id' => $subjectId,
                'cc_chapter_id' => $chapterId,
                'chapter_order' => $chapterOrder,
                'description' => $description,
                'part_mode' => $partMode,
                'remaining_minutes' => (int) $allocation->planned_minutes,
            ];
        }

        foreach ($pool as &$items) {
            usort($items, fn (array $first, array $second) => [
                (int) ($first['chapter_order'] ?? 0),
                (int) ($first['cc_chapter_id'] ?? 0),
            ] <=> [
                (int) ($second['chapter_order'] ?? 0),
                (int) ($second['cc_chapter_id'] ?? 0),
            ]);
        }
        unset($items);

        return $pool;
    }

    private function normalizeWholeBookAllocations(StudentExamSchedule $schedule): void
    {
        $wholeBookAllocations = $schedule->allocations()
            ->where('ratable_type', CcSubject::class)
            ->where('planned_minutes', '>', 0)
            ->get();

        foreach ($wholeBookAllocations as $allocation) {
            $subject = CcSubject::find($allocation->ratable_id);
            if (! $subject) {
                continue;
            }

            $chapters = $subject->chapters()->active()->ordered()->get();
            if ($chapters->isEmpty()) {
                continue;
            }

            foreach ($this->splitMinutesAcrossChapters((int) $allocation->planned_minutes, $chapters) as $chapterAllocation) {
                $chapterStudy = StudentExamStudyAllocation::query()->firstOrNew([
                    'student_exam_schedule_id' => $schedule->id,
                    'ratable_type' => CcChapter::class,
                    'ratable_id' => $chapterAllocation['cc_chapter_id'],
                ]);

                $chapterStudy->cc_subject_id = $chapterAllocation['cc_subject_id'];
                $chapterStudy->planned_minutes = (int) ($chapterStudy->planned_minutes ?? 0)
                    + (int) $chapterAllocation['remaining_minutes'];
                $chapterStudy->is_priority_subject = false;
                $chapterStudy->save();
            }

            if ($allocation->is_priority_subject) {
                $allocation->planned_minutes = 0;
                $allocation->save();
            } else {
                $allocation->delete();
            }
        }
    }

    private function splitMinutesAcrossChapters(int $minutes, Collection $chapters): array
    {
        $units = intdiv($minutes, self::ALLOCATION_STEP_MINUTES);
        $chapterMinutes = [];
        $chapterCount = $chapters->count();

        for ($unit = 0; $unit < $units; $unit++) {
            $chapter = $chapters[$unit % $chapterCount];
            $chapterMinutes[$chapter->id] = ($chapterMinutes[$chapter->id] ?? 0) + self::ALLOCATION_STEP_MINUTES;
        }

        return $chapters
            ->filter(fn (CcChapter $chapter) => ($chapterMinutes[$chapter->id] ?? 0) > 0)
            ->map(fn (CcChapter $chapter) => [
                'cc_subject_id' => (int) $chapter->cc_subject_id,
                'cc_chapter_id' => $chapter->id,
                'chapter_order' => (int) $chapter->order,
                'description' => 'مطالعه ' . $chapter->name,
                'part_mode' => ProgramPart::PART_MODE_NORMAL,
                'remaining_minutes' => (int) $chapterMinutes[$chapter->id],
            ])
            ->values()
            ->all();
    }

    private function buildPriorityFillers(StudentExamSchedule $schedule, Collection $scheduledSubjectIds): array
    {
        $schedule->loadMissing(['allocations.ratable', 'days.subject.chapters']);

        $subjectIds = $schedule->allocations
            ->where('is_priority_subject', true)
            ->pluck('cc_subject_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $subjectId) => $scheduledSubjectIds->contains($subjectId))
            ->unique()
            ->values()
            ->all();

        $fillers = [];

        foreach ($schedule->days->pluck('subject', 'cc_subject_id') as $subjectId => $subject) {
            $subjectId = (int) $subjectId;
            $chapters = $subject->chapters()->active()->ordered()->get();

            if ($chapters->isEmpty()) {
                continue;
            }

            $fillers[$subjectId] = $chapters->map(fn (CcChapter $chapter) => [
                'cc_subject_id' => $subjectId,
                'cc_chapter_id' => $chapter->id,
                'description' => 'مرور تقویتی ' . $chapter->name,
                'part_mode' => ProgramPart::PART_MODE_REVIEW,
            ])->values()->all();
        }

        return [
            'subject_ids' => $subjectIds,
            'fillers' => $fillers,
        ];
    }

    private function scheduledSubjectIds(StudentExamSchedule $schedule): Collection
    {
        $schedule->loadMissing('days');

        return $schedule->days
            ->pluck('cc_subject_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
    }

    private function fillDayFromSubjects(
        WeeklyProgram $program,
        Carbon $date,
        array &$partOrderMap,
        int $dayRemaining,
        array $subjectIds,
        array &$allocationPool,
        array $strictSubjectIds,
        ?array $profile,
        int $dayIndex
    ): int {
        $rotation = collect($subjectIds)->values();
        $cursor = 0;

        while ($dayRemaining >= self::ALLOCATION_STEP_MINUTES && $rotation->isNotEmpty()) {
            $subjectId = (int) $rotation[$cursor % $rotation->count()];
            $cursor++;
            if (! isset($allocationPool[$subjectId])) {
                if (! empty($strictSubjectIds)) {
                    if ($cursor > ($rotation->count() * 3)) {
                        break;
                    }
                    continue;
                }

                $rotation = $rotation->reject(fn ($id) => (int) $id === $subjectId)->values();
                $cursor = 0;
                continue;
            }

            $items = &$allocationPool[$subjectId];

            if (empty($items)) {
                if (! empty($strictSubjectIds)) {
                    if ($cursor > ($rotation->count() * 3)) {
                        break;
                    }
                    continue;
                }

                $rotation = $rotation->reject(fn ($id) => (int) $id === $subjectId)->values();
                $cursor = 0;
                continue;
            }

            $consumed = false;

            foreach ($items as $index => &$item) {
                $chunk = $this->pickPartSize(min($dayRemaining, (int) $item['remaining_minutes']));
                if ($chunk <= 0) {
                    continue;
                }

                $this->createProgramPart(
                    $program,
                    $date,
                    $partOrderMap,
                    $chunk,
                    $subjectId,
                    $item['cc_chapter_id'],
                    $item['description'],
                    $item['part_mode'],
                    $profile,
                    $dayIndex
                );

                $item['remaining_minutes'] -= $chunk;
                $dayRemaining -= $chunk;
                $consumed = true;

                if ($item['remaining_minutes'] <= 0) {
                    unset($items[$index]);
                    $items = array_values($items);
                }

                break;
            }

            if (! $consumed) {
                if (! empty($strictSubjectIds)) {
                    break;
                }

                $rotation = $rotation->reject(fn ($id) => (int) $id === $subjectId)->values();
                $cursor = 0;
            }
        }

        return $dayRemaining;
    }

    private function fillDayWithPriorityReview(
        WeeklyProgram $program,
        Carbon $date,
        array &$partOrderMap,
        int $dayRemaining,
        array $subjectIds,
        array $fillers,
        array &$priorityCursorMap,
        ?array $profile,
        int $dayIndex
    ): int {
        if (empty($subjectIds)) {
            return $dayRemaining;
        }

        $cursor = 0;

        while ($dayRemaining >= self::ALLOCATION_STEP_MINUTES && ! empty($subjectIds)) {
            $subjectId = (int) $subjectIds[$cursor % count($subjectIds)];
            $subjectFillers = $fillers[$subjectId] ?? [];
            $cursor++;

            if (empty($subjectFillers)) {
                if ($cursor > (count($subjectIds) * 2)) {
                    break;
                }
                continue;
            }

            $fillerIndex = (int) ($priorityCursorMap[$subjectId] ?? 0);
            $fill = $subjectFillers[$fillerIndex % count($subjectFillers)];
            $chunk = $this->pickPartSize($dayRemaining);

            if ($chunk <= 0) {
                break;
            }

            $priorityCursorMap[$subjectId] = $fillerIndex + 1;

            $this->createProgramPart(
                $program,
                $date,
                $partOrderMap,
                $chunk,
                $subjectId,
                $fill['cc_chapter_id'],
                $fill['description'],
                $fill['part_mode'],
                $profile,
                $dayIndex
            );

            $dayRemaining -= $chunk;
        }

        return $dayRemaining;
    }

    private function createProgramPart(
        WeeklyProgram $program,
        Carbon $date,
        array &$partOrderMap,
        int $minutes,
        int $subjectId,
        ?int $chapterId,
        string $description,
        string $partMode,
        ?array $profile,
        int $dayIndex
    ): void {
        $subject = CcSubject::with(['grade.educationLevel', 'field'])->find($subjectId);
        if (! $subject) {
            throw new LogicException('یکی از درس‌های انتخاب‌شده در طبقه‌بندی دروس پیدا نشد.');
        }

        $chapter = null;
        if ($chapterId) {
            $chapter = CcChapter::query()
                ->whereKey($chapterId)
                ->where('cc_subject_id', $subject->id)
                ->first();
        }

        if ($partMode !== ProgramPart::PART_MODE_WHOLE_BOOK && ! $chapter) {
            throw new LogicException("برای «{$subject->name}» فصل معتبر پیدا نشد و امکان ساخت پارت امتحانی بدون فصل وجود ندارد.");
        }

        $dateKey = $date->toDateString();
        $partOrderMap[$dateKey] = ($partOrderMap[$dateKey] ?? 0) + 1;

        ProgramPart::create([
            'weekly_program_id' => $program->id,
            'education_level_id' => $subject->grade?->education_level_id,
            'cc_grade_id' => $subject->cc_grade_id,
            'cc_field_id' => $subject->cc_field_id ?: $subject->grade?->cc_field_id,
            'cc_subject_id' => $subject->id,
            'cc_chapter_id' => $chapterId,
            'lesson_name' => $subject->name,
            'part_date' => $date->toDateString(),
            'day_of_week' => $dayIndex,
            'part_order' => $partOrderMap[$dateKey],
            'description' => $description,
            'duration_minutes' => $minutes,
            'test_count' => null,
            'part_type' => ProgramPart::PART_TYPE_DESCRIPTIVE,
            'part_mode' => $partMode,
            'review_chapters' => $partMode === ProgramPart::PART_MODE_REVIEW && $chapter
                ? [['id' => $chapter->id, 'name' => $chapter->name]]
                : null,
            'source_type' => ProgramPart::SOURCE_EXAM,
            'lesson_type' => $subject->type === 'general'
                ? ProgramPart::LESSON_TYPE_GENERAL
                : ProgramPart::LESSON_TYPE_SPECIALIZED,
            'grade' => (string) ($profile['curriculum_grade'] ?? $subject->grade?->grade_number ?? ''),
        ]);
    }

    private function pickPartSize(int $minutes): int
    {
        foreach (self::PART_MINUTES as $partMinutes) {
            if ($minutes >= $partMinutes) {
                return $partMinutes;
            }
        }

        return 0;
    }
}
