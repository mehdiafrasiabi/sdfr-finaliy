<?php

use App\Models\AdvisingSession;
use App\Models\ClassificationProject;
use App\Models\Payment;
use App\Models\StudentExamSchedule;
use App\Models\StudentClassification;
use App\Models\StudentClassificationSubmission;
use App\Models\TrialWeek;
use App\Models\WeeklyProgram;
use App\Services\TrialWeekService;
use App\Services\TrialLifecycleSmsService;
use App\Models\CcChapter;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\CcTopic;
use Database\Seeders\SchoolManagerDemoSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('advising-sessions:mark-advisor-absent', function () {
    $count = AdvisingSession::markExpiredSessionsAsAdvisorAbsent();
    $this->info("Marked {$count} expired advising session(s) as advisor_absent.");
})->purpose('Auto-close expired advising sessions as advisor absent');

Artisan::command('sms:send-lifecycle-notifications', function () {
    $sms = app(TrialLifecycleSmsService::class);

    $started = 0;
    TrialWeek::query()
        ->whereNull('trial_started_sms_sent_at')
        ->with('user')
        ->chunkById(100, function ($trials) use ($sms, &$started) {
            foreach ($trials as $trial) {
                if ($sms->trySendTrialStarted($trial)) {
                    $started++;
                }
            }
        });

    $examProgramStarted = 0;
    StudentExamSchedule::query()
        ->whereNotNull('weekly_program_id')
        ->whereNotNull('program_built_at')
        ->whereNull('exam_program_started_sms_sent_at')
        ->whereHas('student', fn ($query) => $query->where('is_trial', true))
        ->with(['user', 'student'])
        ->chunkById(100, function ($schedules) use ($sms, &$examProgramStarted) {
            foreach ($schedules as $schedule) {
                if ($sms->trySendExamProgramStarted($schedule)) {
                    $examProgramStarted++;
                }
            }
        });

    $examProgramEnded = 0;
    StudentExamSchedule::query()
        ->whereNotNull('weekly_program_id')
        ->whereNotNull('program_built_at')
        ->whereNotNull('access_expires_at')
        ->where('access_expires_at', '<=', now())
        ->whereNull('exam_program_ended_sms_sent_at')
        ->whereHas('student', fn ($query) => $query->where('is_trial', true))
        ->with(['user.personalInformation', 'student'])
        ->chunkById(100, function ($schedules) use ($sms, &$examProgramEnded) {
            foreach ($schedules as $schedule) {
                if ($sms->trySendExamProgramEnded($schedule)) {
                    $examProgramEnded++;
                }
            }
        });

    $ended = 0;
    TrialWeek::query()
        ->where('status', TrialWeek::STATUS_PROGRAM_BUILT)
        ->whereNotNull('expires_at')
        ->where('expires_at', '<=', now())
        ->whereNull('trial_ended_sms_sent_at')
        ->whereHas('student', fn ($query) => $query->where('is_trial', true))
        ->whereDoesntHave('user.examSchedules', function ($query) {
            $query->whereNotNull('weekly_program_id')
                ->whereNotNull('program_built_at');
        })
        ->with(['user.personalInformation', 'student'])
        ->chunkById(100, function ($trials) use ($sms, &$ended) {
            foreach ($trials as $trial) {
                if ($sms->trySendTrialEnded($trial)) {
                    $ended++;
                }
            }
        });

    $purchases = 0;
    Payment::query()
        ->where('status', 'completed')
        ->whereNull('purchase_completed_sms_sent_at')
        ->where(function ($query) {
            $query->whereNull('purpose')
                ->orWhereIn('purpose', [
                    Payment::PURPOSE_COURSE_FULL,
                    Payment::PURPOSE_INSTALLMENT_INITIAL,
                ]);
        })
        ->with('user')
        ->chunkById(100, function ($payments) use ($sms, &$purchases) {
            foreach ($payments as $payment) {
                if ($sms->trySendPurchaseCompleted($payment)) {
                    $purchases++;
                }
            }
        });

    $this->info("Lifecycle SMS sent: started={$started}, exam_program_started={$examProgramStarted}, exam_program_ended={$examProgramEnded}, ended={$ended}, purchases={$purchases}.");
})->purpose('Send and retry required lifecycle SMS notifications');

Artisan::command('trial-week:convert-exam-users-to-trial {--user= : Convert only one user id} {--dry-run : Show matching users without changing data}', function () {
    $userId = $this->option('user');
    $dryRun = (bool) $this->option('dry-run');

    $query = StudentExamSchedule::query()
        ->whereHas('setting', fn ($settingQuery) => $settingQuery->availableForExamOnboarding())
        ->whereHas('student', fn ($studentQuery) => $studentQuery->where('is_trial', true))
        ->whereHas('user.trialWeek')
        ->whereNull('converted_to_trial_at')
        ->when($userId, fn ($scheduleQuery) => $scheduleQuery->where('user_id', (int) $userId))
        ->with(['user.trialWeek', 'student']);

    $matched = 0;
    $converted = 0;
    $skippedPaid = 0;
    $skippedMissing = 0;

    $query->orderBy('id')->chunkById(100, function ($schedules) use ($dryRun, &$matched, &$converted, &$skippedPaid, &$skippedMissing) {
        foreach ($schedules as $schedule) {
            $matched++;

            $student = $schedule->student;
            $trial = $schedule->user?->trialWeek;

            if (! $student || ! $trial) {
                $skippedMissing++;
                $this->warn("Skipped schedule #{$schedule->id}: missing student or trial week.");
                continue;
            }

            if ($student->hasActivePaidAccess()) {
                $skippedPaid++;
                $this->warn("Skipped user #{$schedule->user_id}: active paid access exists.");
                continue;
            }

            $this->line(sprintf(
                '%s user #%d, schedule #%d, exam status=%s',
                $dryRun ? 'Would convert' : 'Converting',
                $schedule->user_id,
                $schedule->id,
                $trial->status
            ));

            if ($dryRun) {
                continue;
            }

            $trialId = DB::transaction(function () use ($schedule) {
                $lockedSchedule = StudentExamSchedule::query()
                    ->whereKey($schedule->id)
                    ->lockForUpdate()
                    ->first();

                if (! $lockedSchedule) {
                    return null;
                }

                $student = $lockedSchedule->student()->lockForUpdate()->first();
                $trial = TrialWeek::query()
                    ->where('user_id', $lockedSchedule->user_id)
                    ->where('student_id', $lockedSchedule->student_id)
                    ->latest('id')
                    ->lockForUpdate()
                    ->first();

                if (! $student || ! $trial || $student->hasActivePaidAccess()) {
                    return null;
                }

                if ($lockedSchedule->weekly_program_id) {
                    WeeklyProgram::query()
                        ->whereKey($lockedSchedule->weekly_program_id)
                        ->where('student_id', $student->id)
                        ->update(['is_active' => false]);
                }

                $lockedSchedule->update([
                    'weekly_program_id' => null,
                    'program_built_at' => null,
                    'access_expires_at' => null,
                    'converted_to_trial_at' => now(),
                ]);

                $trialProjectId = ClassificationProject::query()
                    ->where('is_trial', true)
                    ->where('is_active', true)
                    ->value('id');

                if ($trialProjectId) {
                    StudentClassification::query()
                        ->where('user_id', $lockedSchedule->user_id)
                        ->where('classification_project_id', $trialProjectId)
                        ->delete();

                    StudentClassificationSubmission::query()
                        ->where('user_id', $lockedSchedule->user_id)
                        ->where('classification_project_id', $trialProjectId)
                        ->delete();
                }

                $student->forceFill(['is_trial' => true])->save();

                $trial->update([
                    'advising_session_id' => null,
                    'daily_study_hours' => null,
                    'status' => TrialWeek::STATUS_PENDING,
                    'expires_at' => null,
                    'supporter_assigned_at' => null,
                    'classification_locked_at' => null,
                    'pre_session_completed_at' => null,
                    'program_built_at' => null,
                    'dashboard_notice_acknowledged_at' => null,
                    'trial_ended_sms_sent_at' => null,
                    'acq_disinterest_status' => null,
                    'acq_disinterest_reason' => null,
                    'acq_disinterest_at' => null,
                ]);

                return $trial->id;
            });

            if ($trialId) {
                app(TrialWeekService::class)->autoAssignAcquisitionConsultant(
                    TrialWeek::query()->findOrFail($trialId)
                );

                $converted++;
            }
        }
    });

    $this->info("Matched={$matched}, converted={$converted}, skipped_paid={$skippedPaid}, skipped_missing={$skippedMissing}.");
})->purpose('Convert exam-program trial users back to the ordinary one-week trial flow');

Schedule::command('advising-sessions:mark-advisor-absent')->everyFiveMinutes();
Schedule::command('sms:send-lifecycle-notifications')->hourly()->withoutOverlapping();

// TEMP: دستور یک‌بارمصرف برای حذف کامل دیتای دموی «مدیر مدرسه» روی سرور.
// همان متد cleanupOldDemo داخل SchoolManagerDemoSeeder را صدا می‌زند تا فقط
// رکوردهای دمو حذف شوند. بعد از اجرا، این بلوک را همراه با فایل
// SchoolManagerDemoSeeder.php حذف کنید.
Artisan::command('demo:cleanup-school-manager', function () {
    if (! class_exists(SchoolManagerDemoSeeder::class)) {
        $this->error('SchoolManagerDemoSeeder یافت نشد.');

        return 1;
    }

    DB::transaction(function () {
        $seeder = new SchoolManagerDemoSeeder();
        $method = new ReflectionMethod($seeder, 'cleanupOldDemo');
        $method->setAccessible(true);
        $method->invoke($seeder);
    });

    $this->info('داده‌های دموی «مدیر مدرسه» با موفقیت حذف شد.');

    return 0;
})->purpose('TEMP: Remove all School Manager demo/test data (run once, then delete this command + the seeder file)');

// TEMP: گزارش تشخیصی برای پیدا کردن درس‌های تکراریِ دموی «مدیر مدرسه» که
// مستقیم زیر پایه‌های واقعی درست شده‌اند (بدون سطح تحصیلی جدا). شناسایی از
// روی اثرانگشت دقیق seeder انجام می‌شود: فصل با نام دقیق «فصل اول» + مبحث با
// نام دقیق «مبحث پایه» + نام درس دقیقاً یکی از نام‌های دموی زیر. فقط گزارش
// می‌دهد و هیچ تغییری در دیتابیس نمی‌دهد.
Artisan::command('demo:report-curriculum', function () {
    $demoSubjectNames = ['حسابان', 'فیزیک', 'شیمی', 'هندسه', 'زیست شناسی', 'ریاضی', 'علوم و فنون', 'عربی', 'فلسفه و منطق', 'جامعه شناسی'];

    $normalize = function (?string $s): string {
        return str_replace(["\xE2\x80\x8C", ' ', "\xC2\xA0"], '', (string) $s);
    };

    $demoChapters = CcChapter::where('name', 'فصل اول')->get();
    $this->info('فصل‌های با نام دقیق «فصل اول»: ' . $demoChapters->count());

    $candidateSubjectIds = $demoChapters->pluck('cc_subject_id')->unique();
    $candidateSubjects = CcSubject::whereIn('id', $candidateSubjectIds)->get();

    $demoSubjects = $candidateSubjects->filter(fn ($s) => in_array($s->name, $demoSubjectNames, true));
    $this->info('درس‌های مشکوک دمو (نام دقیق مطابق + فصل «فصل اول»): ' . $demoSubjects->count());

    $demoSubjectIds = $demoSubjects->pluck('id');
    $demoChapters = $demoChapters->whereIn('cc_subject_id', $demoSubjectIds);
    $demoChapterIds = $demoChapters->pluck('id');

    $demoTopics = CcTopic::whereIn('cc_chapter_id', $demoChapterIds)->where('name', 'مبحث پایه')->get();
    $demoTopicIds = $demoTopics->pluck('id');

    $this->info('cc_subjects دمو: ' . $demoSubjectIds->implode(', '));
    $this->info('cc_chapters دمو: ' . $demoChapterIds->implode(', '));
    $this->info('cc_topics دمو: ' . $demoTopicIds->implode(', '));

    $this->newLine();
    $this->info('=== پیشنهاد جایگزین واقعی برای هر درسِ دمو (پایه واقعیِ هم‌رشته/هم‌پایه، نام مشابه با شماره) ===');

    $demoGradeIds = $demoSubjects->pluck('cc_grade_id')->unique();
    $this->info('cc_grades دمو (بدون سطح تحصیلی معتبر، مستقیم زیر پایه‌های واقعی جا نمی‌شوند): ' . $demoGradeIds->implode(', '));

    foreach ($demoSubjects as $subject) {
        $grade = CcGrade::find($subject->cc_grade_id);

        $realGrades = CcGrade::where('cc_field_id', $subject->cc_field_id)
            ->where('grade_number', $grade?->grade_number)
            ->whereNotIn('id', $demoGradeIds)
            ->get();

        $base = $normalize($subject->name);
        $matches = collect();
        $matchedRealGradeId = null;

        foreach ($realGrades as $realGrade) {
            $siblings = CcSubject::where('cc_grade_id', $realGrade->id)->get(['id', 'name']);
            $found = $siblings->filter(function ($sib) use ($normalize, $base) {
                $n = $normalize($sib->name);

                return $n !== $base && str_starts_with($n, $base);
            });
            if ($found->isNotEmpty()) {
                $matches = $matches->merge($found);
                $matchedRealGradeId = $realGrade->id;
            }
        }

        $matchLabel = $matches->isEmpty()
            ? 'NO MATCH'
            : $matches->map(fn ($m) => "#{$m->id}:{$m->name}")->implode(' | ');

        $this->line(sprintf(
            'demo_subject#%d [%s] demo_grade=%d (%s) field=%s grade_number=%s -> real_grade=%s -> %s',
            $subject->id,
            $subject->name,
            $subject->cc_grade_id,
            $grade?->name,
            $subject->cc_field_id,
            $grade?->grade_number,
            $matchedRealGradeId ?? 'NONE',
            $matchLabel
        ));
    }

    $this->newLine();
    $this->info('=== ارجاعات ستون‌های cc_* در سایر جدول‌ها به رکوردهای دمو ===');

    $dbName = DB::getDatabaseName();
    $columns = DB::select(
        "SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = ?
           AND COLUMN_NAME IN ('cc_field_id','cc_grade_id','cc_subject_id','cc_chapter_id','cc_topic_id')",
        [$dbName]
    );

    $idSets = [
        'cc_grade_id' => $demoGradeIds,
        'cc_subject_id' => $demoSubjectIds,
        'cc_chapter_id' => $demoChapterIds,
        'cc_topic_id' => $demoTopicIds,
    ];

    $curriculumTables = ['cc_grades', 'cc_subjects', 'cc_chapters', 'cc_topics', 'cc_fields'];

    foreach ($columns as $col) {
        if (in_array($col->TABLE_NAME, $curriculumTables, true)) {
            continue;
        }

        $ids = $idSets[$col->COLUMN_NAME] ?? null;
        if (! $ids || $ids->isEmpty()) {
            continue;
        }

        $rows = DB::table($col->TABLE_NAME)->whereIn($col->COLUMN_NAME, $ids)->limit(50)->get();
        if ($rows->isNotEmpty()) {
            $this->warn("{$col->TABLE_NAME}.{$col->COLUMN_NAME}: {$rows->count()} ردیف به دیتای دمو اشاره دارند");
            foreach ($rows as $row) {
                $this->line('    ' . json_encode($row, JSON_UNESCAPED_UNICODE));
            }
        }
    }

    if (! $demoSubjectIds->isEmpty()) {
        $count = DB::table('student_classifications')
            ->where('ratable_type', CcSubject::class)
            ->whereIn('ratable_id', $demoSubjectIds)
            ->count();
        if ($count > 0) {
            $this->warn("student_classifications.ratable_id (CcSubject): {$count} ردیف به دیتای دمو اشاره دارند");
        }
    }

    return 0;
})->purpose('TEMP: Report duplicate school-manager-demo curriculum subjects/chapters/topics and any real references to them');

// TEMP: اصلاح و حذف ریشه‌ای دروسِ تکراریِ دموی «مدیر مدرسه». ابتدا هر ارجاع
// واقعی (برنامه هفتگی، کلاس‌بندی، آزمون تشریحی) را به معادل واقعیِ شماره‌دار
// ریدایرکت می‌کند، سپس در صورت تمیز بودن کامل، خودِ پایه/درس/فصل/مبحثِ دمو را
// برای همیشه حذف می‌کند (کد delete واقعی، نه soft delete).
Artisan::command('demo:fix-curriculum', function () {
    $demoSubjectNames = ['حسابان', 'فیزیک', 'شیمی', 'هندسه', 'زیست شناسی', 'ریاضی', 'علوم و فنون', 'عربی', 'فلسفه و منطق', 'جامعه شناسی'];

    $normalize = function (?string $s): string {
        return str_replace(["\xE2\x80\x8C", ' ', "\xC2\xA0"], '', (string) $s);
    };

    $demoChapters = CcChapter::where('name', 'فصل اول')->get();
    $candidateSubjectIds = $demoChapters->pluck('cc_subject_id')->unique();
    $candidateSubjects = CcSubject::whereIn('id', $candidateSubjectIds)->get();
    $demoSubjects = $candidateSubjects->filter(fn ($s) => in_array($s->name, $demoSubjectNames, true));
    $demoSubjectIds = $demoSubjects->pluck('id');
    $demoGradeIds = $demoSubjects->pluck('cc_grade_id')->unique();

    $demoChapters = $demoChapters->whereIn('cc_subject_id', $demoSubjectIds);
    $demoChapterIds = $demoChapters->pluck('id');
    $demoTopics = CcTopic::whereIn('cc_chapter_id', $demoChapterIds)->where('name', 'مبحث پایه')->get();
    $demoTopicIds = $demoTopics->pluck('id');

    if ($demoSubjects->isEmpty()) {
        $this->info('هیچ درسِ دموی مشکوکی پیدا نشد. کاری برای انجام نیست.');

        return 0;
    }

    // --- نگاشت درسِ دمو -> درسِ واقعیِ شماره‌دار هم‌پایه/هم‌رشته ---
    $subjectMap = [];
    $unmatchedSubjectIds = [];

    foreach ($demoSubjects as $subject) {
        $grade = CcGrade::find($subject->cc_grade_id);

        $realGrades = CcGrade::where('cc_field_id', $subject->cc_field_id)
            ->where('grade_number', $grade?->grade_number)
            ->whereNotIn('id', $demoGradeIds)
            ->get();

        $base = $normalize($subject->name);
        $found = null;

        foreach ($realGrades as $rg) {
            $siblings = CcSubject::where('cc_grade_id', $rg->id)->get(['id', 'name']);
            $matches = $siblings->filter(function ($sib) use ($normalize, $base) {
                $n = $normalize($sib->name);

                return $n !== $base && str_starts_with($n, $base);
            });
            if ($matches->count() === 1) {
                $found = $matches->first()->id;
                break;
            }
        }

        if ($found) {
            $subjectMap[$subject->id] = $found;
        } else {
            $unmatchedSubjectIds[] = $subject->id;
        }
    }

    $this->info('نگاشت پیدا شد: ' . count($subjectMap) . ' | بدون معادل واقعی: ' . implode(',', $unmatchedSubjectIds));

    // --- نگاشت پایه‌ی دمو -> پایه‌ی واقعیِ هم‌رشته/هم‌شماره ---
    $gradeMap = [];
    foreach ($demoGradeIds as $gid) {
        $g = CcGrade::find($gid);
        $real = CcGrade::where('cc_field_id', $g->cc_field_id)
            ->where('grade_number', $g->grade_number)
            ->whereNotIn('id', $demoGradeIds)
            ->first();
        if ($real) {
            $gradeMap[$gid] = $real->id;
        }
    }

    // --- ایمنی: اگر درسِ بدون‌معادل هنوز جایی واقعی استفاده شده، متوقف شو ---
    if (! empty($unmatchedSubjectIds)) {
        $stillUsed = DB::table('class_schedule_parts')->whereIn('cc_subject_id', $unmatchedSubjectIds)->count()
            + DB::table('program_parts')->whereIn('cc_subject_id', $unmatchedSubjectIds)->count();
        if ($stillUsed > 0) {
            $this->error('برخی درس‌های دمو بدون معادل واقعی، هنوز در جدول‌های واقعی استفاده شده‌اند. متوقف شد؛ نیاز به بررسی دستی دارد.');

            return 1;
        }
    }

    DB::transaction(function () use ($subjectMap, $gradeMap, $demoTopics, $demoChapters, $demoSubjects, $demoGradeIds, $normalize) {
        // 1) class_schedule_parts.cc_subject_id
        foreach ($subjectMap as $oldId => $newId) {
            $oldSubject = $demoSubjects->firstWhere('id', $oldId);
            $newSubject = CcSubject::find($newId);

            $rows = DB::table('class_schedule_parts')->where('cc_subject_id', $oldId)->get();
            foreach ($rows as $row) {
                DB::table('class_schedule_parts')->where('id', $row->id)->update([
                    'cc_subject_id' => $newId,
                    'lesson_name' => $newSubject->name,
                    'updated_at' => now(),
                ]);
                $this->line("class_schedule_parts#{$row->id}: cc_subject_id {$oldId}->{$newId}, lesson_name '{$row->lesson_name}'->'{$newSubject->name}'");
            }
        }

        // 2) program_parts.cc_subject_id / cc_grade_id / education_level_id
        foreach ($subjectMap as $oldId => $newId) {
            $oldSubject = $demoSubjects->firstWhere('id', $oldId);
            $newSubject = CcSubject::find($newId);
            $newGradeId = $gradeMap[$oldSubject->cc_grade_id] ?? null;
            $newGrade = $newGradeId ? CcGrade::find($newGradeId) : null;

            $rows = DB::table('program_parts')->where('cc_subject_id', $oldId)->get();
            foreach ($rows as $row) {
                $update = [
                    'cc_subject_id' => $newId,
                    'lesson_name' => str_replace($oldSubject->name, $newSubject->name, $row->lesson_name),
                    'description' => $row->description !== null
                        ? str_replace($oldSubject->name, $newSubject->name, $row->description)
                        : $row->description,
                    'updated_at' => now(),
                ];
                if ($newGrade) {
                    $update['cc_grade_id'] = $newGrade->id;
                    $update['education_level_id'] = $newGrade->education_level_id;
                }
                DB::table('program_parts')->where('id', $row->id)->update($update);
                $this->line("program_parts#{$row->id}: cc_subject_id {$oldId}->{$newId}" . ($newGrade ? ", cc_grade_id {$row->cc_grade_id}->{$newGrade->id}, education_level_id {$row->education_level_id}->{$newGrade->education_level_id}" : ''));
            }
        }

        // 3) essay_exams.cc_topic_id
        $topicById = $demoTopics->keyBy('id');
        $chapterById = $demoChapters->keyBy('id');

        $rows = DB::table('essay_exams')->whereIn('cc_topic_id', $demoTopics->pluck('id'))->get();
        foreach ($rows as $row) {
            $demoTopic = $topicById->get($row->cc_topic_id);
            $demoChapter = $demoTopic ? $chapterById->get($demoTopic->cc_chapter_id) : null;
            $oldSubjectId = $demoChapter?->cc_subject_id;
            $newSubjectId = $oldSubjectId ? ($subjectMap[$oldSubjectId] ?? null) : null;

            if (! $newSubjectId) {
                $this->warn("essay_exams#{$row->id}: نتوانستم درس معادل را برای topic دمو #{$row->cc_topic_id} پیدا کنم؛ رد شد.");
                continue;
            }

            $realChapter = CcChapter::where('cc_subject_id', $newSubjectId)->orderBy('order')->orderBy('id')->first();
            $realTopic = $realChapter ? CcTopic::where('cc_chapter_id', $realChapter->id)->orderBy('order')->orderBy('id')->first() : null;

            if (! $realTopic) {
                $this->warn("essay_exams#{$row->id}: درس معادل واقعی #{$newSubjectId} هیچ فصل/مبحثی ندارد؛ رد شد.");
                continue;
            }

            DB::table('essay_exams')->where('id', $row->id)->update([
                'cc_topic_id' => $realTopic->id,
                'updated_at' => now(),
            ]);
            $this->line("essay_exams#{$row->id}: cc_topic_id {$row->cc_topic_id}->{$realTopic->id}");
        }

        // 4) بررسی نهایی قبل از حذف: هیچ ارجاع واقعی نباید باقی مانده باشد
        $demoSubjectIds = $demoSubjects->pluck('id');
        $demoChapterIds = $demoChapters->pluck('id');
        $demoTopicIds = $demoTopics->pluck('id');

        $remaining = DB::table('class_schedule_parts')->whereIn('cc_subject_id', $demoSubjectIds)->count()
            + DB::table('program_parts')->whereIn('cc_subject_id', $demoSubjectIds)->count()
            + DB::table('program_parts')->whereIn('cc_grade_id', $demoGradeIds)->count()
            + DB::table('essay_exams')->whereIn('cc_topic_id', $demoTopicIds)->count()
            + DB::table('student_classifications')->where('ratable_type', CcSubject::class)->whereIn('ratable_id', $demoSubjectIds)->count();

        if ($remaining > 0) {
            throw new \RuntimeException("هنوز {$remaining} ارجاع واقعی به دیتای دمو باقی مانده؛ حذف نهایی متوقف شد.");
        }

        // 5) حذف نهایی: حذف پایه‌های دمو، که با cascade خودِ دیتابیس درس/فصل/مبحث را هم حذف می‌کند
        CcGrade::whereIn('id', $demoGradeIds)->delete();
    });

    $this->info('اصلاح و حذف دروس/فصل/مبحث/پایه‌های دموی «مدیر مدرسه» با موفقیت انجام شد.');

    return 0;
})->purpose('TEMP: Fix real references then permanently delete the school-manager-demo curriculum duplicates');

