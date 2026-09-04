<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdvisingSession;
use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\CcTopic;
use App\Models\ClassificationProject;
use App\Models\DailyReport;
use App\Models\DailyReportDetail;
use App\Models\DailyReportFeedback;
use App\Models\EducationLevel;
use App\Models\EmergencyCall;
use App\Models\EssayExam;
use App\Models\EssayExamAssignment;
use App\Models\EssayExamAttempt;
use App\Models\EssayExamQuestion;
use App\Models\ProgramPart;
use App\Models\Question;
use App\Models\School;
use App\Models\SchoolParentContact;
use App\Models\SchoolStaff;
use App\Models\SchoolStudentGrade;
use App\Models\Student;
use App\Models\StudentClassification;
use App\Models\StudyPartSession;
use App\Models\Subject;
use App\Models\TypedExam;
use App\Models\TypedExamAssignment;
use App\Models\TypedExamAttempt;
use App\Models\TypedExamAttemptAnswer;
use App\Models\User;
use App\Models\WeeklyProgram;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Morilog\Jalali\Jalalian;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * دیتای دموی کامل برای پنل مدیر مدرسه در پنل ادمین.
 *
 * اجرا:
 *   php artisan db:seed --class=SchoolManagerDemoSeeder
 *
 * ورود مدیر مدرسه:
 *   email: school.manager@test.local
 *   password: password
 */
class SchoolManagerDemoSeeder extends Seeder
{
    private const DEMO_PREFIX = '[دمو مدیر مدرسه] ';
    private const SCHOOL_CODE = 'DEMO-SM-001';
    private const MANAGER_EMAIL = 'school.manager@test.local';
    private const ADVISOR_EMAIL = 'school.advisor@test.local';
    private const STUDENT_EMAIL_DOMAIN = '@school-demo.test';

    private array $subjectNames = [
        'math' => ['حسابان', 'فیزیک', 'شیمی', 'هندسه'],
        'experimental' => ['زیست شناسی', 'شیمی', 'فیزیک', 'ریاضی'],
        'human' => ['علوم و فنون', 'عربی', 'فلسفه و منطق', 'جامعه شناسی'],
    ];

    public function run(): void
    {
        DB::transaction(function () {
            $this->cleanupOldDemo();

            $this->ensureSchoolManagerRole();

            $school = $this->createSchool();
            $advisor = $this->createAdvisor();
            $manager = $this->createManager($school);
            $school->advisors()->syncWithoutDetaching([$advisor->id]);

            $curriculum = $this->ensureCurriculum();
            $students = $this->createStudents($school, $advisor);

            $currentMonth = $this->jalaliMonthContext(0);
            $previousMonth = $this->jalaliMonthContext(1);

            $this->createAdvisingData($students, $advisor, $currentMonth);
            $this->createProgramsAndReports($students, $advisor, $currentMonth, $curriculum);
            $this->createGrades($students, $manager, $currentMonth, $previousMonth, $curriculum);
            $this->createParentContacts($students, $advisor, $currentMonth);
            $this->createEmergencyCalls($students, $advisor, $manager, $currentMonth);
            $this->createClassificationData($students, $currentMonth, $curriculum);
            $this->createExamData($students, $advisor, $currentMonth, $curriculum);
        });

        $this->command?->info('✅ دیتای دموی پنل مدیر مدرسه ساخته شد.');
        $this->command?->info('ورود: email=school.manager@test.local | password=password');
        $this->command?->info('مسیر شروع: /admin/school-manager/dashboard');
    }

    private function ensureSchoolManagerRole(): void
    {
        $permissions = [
            'admin.students.view',
            'admin.daily-activities.view',
            'admin.study-session.view',
            'admin.school-manager.dashboard.view',
            'admin.school-manager.academic-status.view',
            'admin.school-manager.advising.view',
            'admin.school-manager.grades.manage',
            'admin.school-manager.student.progress.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }

        Role::firstOrCreate(['name' => 'school-manager', 'guard_name' => 'admin'])
            ->givePermissionTo($permissions);
    }

    private function cleanupOldDemo(): void
    {
        $userIds = User::withTrashed()
            ->where('email', 'like', 'school.demo.%' . self::STUDENT_EMAIL_DOMAIN)
            ->pluck('id');
        $studentIds = Student::whereIn('user_id', $userIds)->pluck('id');

        if ($studentIds->isNotEmpty()) {
            $weeklyProgramIds = WeeklyProgram::whereIn('student_id', $studentIds)->pluck('id');
            $programPartIds = ProgramPart::withTrashed()->whereIn('weekly_program_id', $weeklyProgramIds)->pluck('id');
            $dailyReportIds = DailyReport::whereIn('student_id', $studentIds)->pluck('id');
            $typedAssignmentIds = TypedExamAssignment::withTrashed()->whereIn('student_id', $studentIds)->pluck('id');
            $typedAttemptIds = TypedExamAttempt::whereIn('assignment_id', $typedAssignmentIds)->pluck('id');
            $essayAssignmentIds = EssayExamAssignment::withTrashed()->whereIn('student_id', $studentIds)->pluck('id');

            TypedExamAttemptAnswer::whereIn('attempt_id', $typedAttemptIds)->delete();
            TypedExamAttempt::whereIn('id', $typedAttemptIds)->delete();
            TypedExamAssignment::withTrashed()->whereIn('id', $typedAssignmentIds)->forceDelete();

            EssayExamAttempt::whereIn('assignment_id', $essayAssignmentIds)->delete();
            EssayExamAssignment::withTrashed()->whereIn('id', $essayAssignmentIds)->forceDelete();

            DailyReportDetail::whereIn('daily_report_id', $dailyReportIds)->delete();
            DailyReportFeedback::whereIn('daily_report_id', $dailyReportIds)->delete();
            DailyReport::whereIn('id', $dailyReportIds)->delete();

            StudyPartSession::whereIn('student_id', $studentIds)->delete();
            ProgramPart::withTrashed()->whereIn('id', $programPartIds)->forceDelete();
            WeeklyProgram::withTrashed()->whereIn('id', $weeklyProgramIds)->forceDelete();
            AdvisingSession::withTrashed()->whereIn('student_id', $studentIds)->forceDelete();
            SchoolParentContact::whereIn('student_id', $studentIds)->delete();
            EmergencyCall::whereIn('student_id', $studentIds)->delete();
            SchoolStudentGrade::whereIn('student_id', $studentIds)->delete();
            StudentClassification::whereIn('user_id', $userIds)->delete();
            Student::whereIn('id', $studentIds)->delete();
            User::withTrashed()->whereIn('id', $userIds)->forceDelete();
        }

        TypedExam::withTrashed()->where('title', 'like', self::DEMO_PREFIX . '%')->forceDelete();
        EssayExam::withTrashed()->where('title', 'like', self::DEMO_PREFIX . '%')->forceDelete();
        ClassificationProject::where('name', 'like', self::DEMO_PREFIX . '%')->delete();
        Question::withTrashed()->where('code', 'like', 'SM%')->forceDelete();

        $school = School::withTrashed()->where('code', self::SCHOOL_CODE)->first();
        if ($school) {
            SchoolStaff::where('school_id', $school->id)->delete();
            DB::table('school_advisor')->where('school_id', $school->id)->delete();
            $school->forceDelete();
        }

        $adminIds = Admin::withTrashed()
            ->whereIn('email', [self::MANAGER_EMAIL, self::ADVISOR_EMAIL])
            ->pluck('id');

        if ($adminIds->isNotEmpty()) {
            DB::table('model_has_roles')
                ->where('model_type', Admin::class)
                ->whereIn('model_id', $adminIds)
                ->delete();
            DB::table('model_has_permissions')
                ->where('model_type', Admin::class)
                ->whereIn('model_id', $adminIds)
                ->delete();
            Admin::withTrashed()->whereIn('id', $adminIds)->forceDelete();
        }
    }

    private function createSchool(): School
    {
        $attrs = [
            'name' => self::DEMO_PREFIX . 'دبیرستان آینده سازان',
            'code' => self::SCHOOL_CODE,
            'address' => 'تهران، خیابان آزادی، کوچه آموزش، پلاک ۱۲',
            'public_phone' => '02166554433',
        ];

        if (Schema::hasColumn('schools', 'type')) {
            $attrs['type'] = 'غیرانتفاعی';
        }

        $school = School::create($attrs);

        SchoolStaff::create([
            'school_id' => $school->id,
            'role' => 'manager',
            'name' => 'مهندس رضا کیانی',
            'phone' => '09124440001',
        ]);

        SchoolStaff::create([
            'school_id' => $school->id,
            'role' => 'deputy',
            'name' => 'خانم الهام سلیمانی',
            'phone' => '09124440002',
        ]);

        return $school;
    }

    private function createAdvisor(): Admin
    {
        $role = Role::firstOrCreate(['name' => 'مشاور تحصیلی', 'guard_name' => 'admin']);

        $advisor = Admin::create([
            'name' => self::DEMO_PREFIX . 'مشاور تحصیلی',
            'email' => self::ADVISOR_EMAIL,
            'mobile' => '09124440003',
            'password' => Hash::make('password'),
            'national_code' => '0012444003',
            'address' => 'تهران، دفتر مشاوره آموزشی',
            'postal_code' => '1111111111',
        ]);

        $advisor->syncRoles([$role->name]);

        return $advisor;
    }

    private function createManager(School $school): Admin
    {
        $manager = Admin::create([
            'name' => self::DEMO_PREFIX . 'مدیر مدرسه',
            'email' => self::MANAGER_EMAIL,
            'mobile' => '09124440004',
            'password' => Hash::make('password'),
            'national_code' => '0012444004',
            'address' => 'تهران، دبیرستان آینده سازان',
            'postal_code' => '2222222222',
            'school_id' => $school->id,
        ]);

        $manager->syncRoles(['school-manager']);

        return $manager;
    }

    private function ensureCurriculum(): array
    {
        $level = EducationLevel::firstOrCreate(
            ['slug' => 'high-school-demo'],
            ['name' => 'متوسطه دوم دمو', 'order' => 99, 'is_active' => true]
        );

        $result = [];

        foreach ($this->subjectNames as $fieldSlug => $subjectNames) {
            $field = CcField::firstOrCreate(
                ['slug' => $fieldSlug],
                ['name' => $this->fieldLabel($fieldSlug), 'order' => count($result) + 1, 'is_active' => true]
            );

            foreach ([10, 11, 12] as $gradeNumber) {
                $grade = CcGrade::firstOrCreate(
                    [
                        'education_level_id' => $level->id,
                        'grade_number' => $gradeNumber,
                        'cc_field_id' => $field->id,
                    ],
                    [
                        'name' => 'پایه ' . $gradeNumber . ' ' . $this->fieldLabel($fieldSlug),
                        'order' => $gradeNumber,
                        'is_active' => true,
                    ]
                );

                foreach ($subjectNames as $index => $subjectName) {
                    $subject = CcSubject::firstOrCreate(
                        ['cc_grade_id' => $grade->id, 'name' => $subjectName],
                        [
                            'cc_field_id' => $field->id,
                            'type' => $index === 0 ? 'general' : 'specialized',
                            'order' => $index + 1,
                        ]
                    );

                    $chapter = CcChapter::firstOrCreate(
                        ['cc_subject_id' => $subject->id, 'name' => 'فصل اول'],
                        ['order' => 1, 'is_active' => true]
                    );

                    $topic = CcTopic::firstOrCreate(
                        ['cc_chapter_id' => $chapter->id, 'name' => 'مبحث پایه'],
                        ['order' => 1, 'is_active' => true]
                    );

                    $result[$fieldSlug][$gradeNumber][] = compact('field', 'grade', 'subject', 'chapter', 'topic');
                }
            }
        }

        return $result;
    }

    private function createStudents(School $school, Admin $advisor)
    {
        $names = [
            ['name' => 'آراد محمدی', 'grade' => '10', 'field' => 'math', 'star' => 'B'],
            ['name' => 'رها احمدی', 'grade' => '10', 'field' => 'experimental', 'star' => 'A'],
            ['name' => 'نیما کریمی', 'grade' => '11', 'field' => 'math', 'star' => 'C'],
            ['name' => 'هستی رضایی', 'grade' => '11', 'field' => 'human', 'star' => 'B'],
            ['name' => 'پارسا نوری', 'grade' => '12', 'field' => 'experimental', 'star' => 'A'],
            ['name' => 'سارا حسینی', 'grade' => '12', 'field' => 'math', 'star' => 'D'],
            ['name' => 'کیان صادقی', 'grade' => '11', 'field' => 'experimental', 'star' => 'B'],
            ['name' => 'نگار موسوی', 'grade' => '10', 'field' => 'human', 'star' => 'C'],
        ];

        return collect($names)->map(function (array $row, int $index) use ($school, $advisor) {
            $user = User::create([
                'name' => self::DEMO_PREFIX . $row['name'],
                'email' => 'school.demo.' . ($index + 1) . self::STUDENT_EMAIL_DOMAIN,
                'mobile' => '0935' . str_pad((string) ($index + 1), 7, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
            ]);

            return Student::create([
                'user_id' => $user->id,
                'school_id' => $school->id,
                'advisor_id' => $advisor->id,
                'national_code' => '008' . str_pad((string) ($index + 1), 7, '0', STR_PAD_LEFT),
                'father_mobile' => '0913' . str_pad((string) ($index + 100), 7, '0', STR_PAD_LEFT),
                'mother_mobile' => '0914' . str_pad((string) ($index + 100), 7, '0', STR_PAD_LEFT),
                'grade' => $row['grade'],
                'field' => $row['field'],
                'educational_pursuer' => $index % 2 === 0 ? 'father' : 'mother',
                'star' => $row['star'],
                'is_trial' => false,
                'access_ends_at' => now()->addMonths(6),
            ]);
        });
    }

    private function createAdvisingData($students, Admin $advisor, array $month): void
    {
        foreach ($students as $studentIndex => $student) {
            for ($i = 0; $i < 4; $i++) {
                $status = match (($studentIndex + $i) % 6) {
                    0 => AdvisingSession::RESULT_STUDENT_ABSENT,
                    1 => AdvisingSession::RESULT_ADVISOR_ABSENT,
                    default => AdvisingSession::RESULT_HELD,
                };

                AdvisingSession::create([
                    'title' => self::DEMO_PREFIX . 'جلسه مشاوره ' . ($i + 1),
                    'description' => 'جلسه ماهانه برای بررسی برنامه، گزارش روزانه و آزمون ها',
                    'activation_date' => $month['start']->copy()->addDays($i * 6 + ($studentIndex % 3))->toDateString(),
                    'session_time' => sprintf('%02d:00:00', 9 + ($i % 4)),
                    'student_id' => $student->id,
                    'advisor_id' => $advisor->id,
                    'status' => AdvisingSession::STATUS_COMPLETED,
                    'result_status' => $status,
                    'is_active' => false,
                    'finalized' => true,
                    'location_type' => $i % 2 === 0 ? AdvisingSession::LOCATION_ONLINE : AdvisingSession::LOCATION_IN_PERSON,
                ]);
            }
        }
    }

    private function createProgramsAndReports($students, Admin $advisor, array $month, array $curriculum): void
    {
        foreach ($students as $studentIndex => $student) {
            $session = AdvisingSession::where('student_id', $student->id)->oldest('activation_date')->first();
            $items = $curriculum[$student->field][(int) $student->grade] ?? collect($curriculum)->flatten(1)->first();

            $programData = [
                'student_id' => $student->id,
                'advisor_id' => $advisor->id,
                'advising_session_id' => $session?->id,
                'start_date' => $month['start']->toDateString(),
                'end_date' => $month['start']->copy()->addDays(6)->toDateString(),
                'is_active' => true,
            ];

            if (Schema::hasColumn('weekly_programs', 'advisor_name')) {
                $programData['advisor_name'] = $advisor->name;
            }

            if (Schema::hasColumn('weekly_programs', 'supporter_name')) {
                $programData['supporter_name'] = 'پشتیبان مدرسه';
            }

            $program = WeeklyProgram::create($programData);

            foreach (range(0, 6) as $day) {
                $item = $items[$day % count($items)];
                $partDate = $month['start']->copy()->addDays($day);

                $part = ProgramPart::create([
                    'weekly_program_id' => $program->id,
                    'cc_grade_id' => $item['grade']->id,
                    'cc_field_id' => $item['field']->id,
                    'cc_subject_id' => $item['subject']->id,
                    'cc_chapter_id' => $item['chapter']->id,
                    'cc_topic_id' => $item['topic']->id,
                    'lesson_name' => $item['subject']->name,
                    'part_date' => $partDate->toDateString(),
                    'day_of_week' => $day,
                    'part_order' => 1,
                    'description' => 'مطالعه و حل تمرین از ' . $item['chapter']->name,
                    'duration_minutes' => 75 + (($studentIndex + $day) % 4) * 15,
                    'test_count' => 12 + (($studentIndex + $day) % 5) * 4,
                    'part_type' => $day % 3 === 0 ? 'test' : 'descriptive',
                    'lesson_type' => $item['subject']->type,
                    'source_type' => 'normal',
                    'part_mode' => 'normal',
                    'grade' => (string) $student->grade,
                    'grade_label' => 'پایه ' . $student->grade,
                ]);

                $startedAt = $partDate->copy()->setTime(17, 0)->addMinutes($studentIndex * 3);
                $duration = (45 + (($studentIndex + $day) % 5) * 18) * 60;
                StudyPartSession::create([
                    'student_id' => $student->id,
                    'program_part_id' => $part->id,
                    'weekly_program_id' => $program->id,
                    'started_at' => $startedAt,
                    'ended_at' => $startedAt->copy()->addSeconds($duration),
                    'duration_seconds' => $duration,
                    'planned_seconds' => $part->duration_minutes * 60,
                    'is_completed' => true,
                    'completed_at' => $startedAt->copy()->addSeconds($duration),
                ]);
            }

            $reportDays = 18 + ($studentIndex % 8);
            for ($day = 0; $day < $reportDays; $day++) {
                $date = $month['start']->copy()->addDays($day);
                $report = DailyReport::create([
                    'student_id' => $student->id,
                    'admin_id' => $advisor->id,
                    'session_id' => $session?->id,
                    'weekly_program_id' => $program->id,
                    'report_date' => $date->toDateString(),
                    'day_of_week' => $day % 7,
                    'is_compensatory' => false,
                ]);

                DailyReportDetail::create([
                    'daily_report_id' => $report->id,
                    'phone_hours' => ($studentIndex + $day) % 5,
                    'description' => 'گزارش روزانه دمو برای بررسی پیوستگی مطالعه',
                    'rating' => min(10, 6 + (($studentIndex + $day) % 5)),
                    'status' => 'approved',
                ]);

                DailyReportFeedback::create([
                    'daily_report_id' => $report->id,
                    'advisor_comment' => 'گزارش بررسی شد؛ روند مطالعه قابل قبول است.',
                    'advisor_commented_at' => $date->copy()->setTime(21, 0),
                ]);
            }
        }
    }

    private function createGrades($students, Admin $manager, array $currentMonth, array $previousMonth, array $curriculum): void
    {
        foreach ($students as $studentIndex => $student) {
            $subjects = array_slice($curriculum[$student->field][(int) $student->grade], 0, 4);

            foreach ($subjects as $subjectIndex => $item) {
                $currentBase = 13.5 + (($studentIndex + $subjectIndex) % 6);
                $previousBase = max(10, $currentBase - (($studentIndex % 3) - 1));

                foreach ([[$currentMonth, $currentBase], [$previousMonth, $previousBase]] as [$month, $baseScore]) {
                    SchoolStudentGrade::create([
                        'student_id' => $student->id,
                        'cc_subject_id' => $item['subject']->id,
                        'cc_chapter_id' => $item['chapter']->id,
                        'recorded_by_admin_id' => $manager->id,
                        'score' => $baseScore,
                        'scale' => '20',
                        'class_activity' => min(20, $baseScore + 0.75),
                        'exam' => min(20, $baseScore + 1.25),
                        'note' => 'نمره دموی ماهانه',
                        'teacher_comment' => $baseScore >= 16 ? 'تسلط خوب و مشارکت فعال' : 'نیازمند تمرین منظم تر',
                        'recorded_at' => $month['start']->copy()->addDays(12 + $subjectIndex)->toDateString(),
                        'jalali_month' => $month['key'],
                    ]);
                }
            }
        }
    }

    private function createParentContacts($students, Admin $advisor, array $month): void
    {
        foreach ($students as $studentIndex => $student) {
            $count = 2 + ($studentIndex % 4);
            for ($i = 0; $i < $count; $i++) {
                SchoolParentContact::create([
                    'student_id' => $student->id,
                    'admin_id' => $advisor->id,
                    'contacted_with' => $i % 2 === 0 ? 'father' : 'mother',
                    'notes' => $i % 2 === 0
                        ? 'هماهنگی وضعیت تکالیف و گزارش روزانه'
                        : 'گفت وگو درباره افت و خیز نمرات ماهانه',
                    'contacted_at' => $month['start']->copy()->addDays(3 + ($i * 5))->setTime(12 + $i, 20),
                ]);
            }
        }
    }

    private function createEmergencyCalls($students, Admin $advisor, Admin $manager, array $month): void
    {
        foreach ($students->take(4) as $index => $student) {
            EmergencyCall::create([
                'student_id' => $student->id,
                'admin_id' => $advisor->id,
                'reason' => $index % 2 === 0
                    ? 'افت ناگهانی ساعت مطالعه و نیاز به پیگیری خانواده'
                    : 'عدم ارسال گزارش روزانه در چند روز متوالی',
                'status' => $index === 3 ? EmergencyCall::STATUS_RESOLVED : EmergencyCall::STATUS_PENDING,
                'called_at' => $month['start']->copy()->addDays(8 + $index)->setTime(16, 0),
                'manager_note' => $index === 3 ? 'با خانواده تماس گرفته شد و برنامه جبرانی تنظیم شد.' : null,
                'resolved_by' => $index === 3 ? $manager->id : null,
                'resolved_at' => $index === 3 ? $month['start']->copy()->addDays(10)->setTime(10, 30) : null,
            ]);
        }
    }

    private function createClassificationData($students, array $month, array $curriculum): void
    {
        if (! Schema::hasColumn('student_classifications', 'ratable_type')) {
            return;
        }

        $previous = ClassificationProject::create([
            'name' => self::DEMO_PREFIX . 'طبقه بندی قبلی',
            'description' => 'دوره قبلی برای نمایش روند رشد و افت در پنل مدیر مدرسه',
            'start_at' => $month['start']->copy()->subMonth(),
            'end_at' => $month['start']->copy()->subDays(1),
            'is_active' => false,
        ]);

        $current = ClassificationProject::create([
            'name' => self::DEMO_PREFIX . 'طبقه بندی فعلی',
            'description' => 'دوره فعلی دمو برای تحلیل دروس ضعیف و پیشرفت',
            'start_at' => $month['start'],
            'end_at' => $month['end'],
            'is_active' => true,
        ]);

        foreach ($students as $studentIndex => $student) {
            $subjects = array_slice($curriculum[$student->field][(int) $student->grade], 0, 4);
            foreach ($subjects as $subjectIndex => $item) {
                $previousRating = max(1, min(4, 2 + (($studentIndex + $subjectIndex) % 3)));
                $currentRating = max(1, min(4, $previousRating + (($studentIndex + $subjectIndex) % 3 === 0 ? 1 : -1)));

                foreach ([[$previous, $previousRating], [$current, $currentRating]] as [$project, $rating]) {
                    StudentClassification::create([
                        'user_id' => $student->user_id,
                        'classification_project_id' => $project->id,
                        'ratable_type' => CcSubject::class,
                        'ratable_id' => $item['subject']->id,
                        'rating' => $rating,
                    ]);
                }
            }
        }
    }

    private function createExamData($students, Admin $advisor, array $month, array $curriculum): void
    {
        $legacySubject = Subject::firstOrCreate(
            ['slug' => 'school-manager-demo'],
            ['name' => 'درس دموی مدیر مدرسه', 'is_active' => true]
        );

        $firstItem = collect($curriculum)->flatten(2)->first();
        $questions = collect(range(1, 6))->map(function (int $i) use ($legacySubject, $firstItem) {
            return Question::create([
                'code' => 'SM' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'subject_id' => $legacySubject->id,
                'cc_chapter_id' => $firstItem['chapter']->id,
                'cc_topic_id' => $firstItem['topic']->id,
                'difficulty' => ['easy', 'medium', 'hard'][$i % 3],
                'correct_option' => ($i % 4) + 1,
            ]);
        });

        $typedExam = TypedExam::create([
            'title' => self::DEMO_PREFIX . 'آزمون تستی ماهانه',
            'cc_field_id' => $firstItem['field']->id,
            'cc_topic_id' => $firstItem['topic']->id,
            'academic_year' => $month['year'] . '-' . ($month['year'] + 1),
            'difficulty' => 'medium',
            'is_random_selection' => false,
            'is_published' => true,
        ]);

        $typedExam->questions()->sync($questions->mapWithKeys(fn($q, $i) => [$q->id => ['order' => $i + 1]])->all());

        $essayExam = EssayExam::create([
            'admin_id' => $advisor->id,
            'cc_topic_id' => $firstItem['topic']->id,
            'title' => self::DEMO_PREFIX . 'آزمون تشریحی فصل اول',
            'question_pdf_path' => null,
            'answer_pdf_path' => null,
            'total_score' => 20,
        ]);

        foreach ([1 => 5, 2 => 5, 3 => 4, 4 => 6] as $number => $score) {
            EssayExamQuestion::create([
                'essay_exam_id' => $essayExam->id,
                'question_number' => $number,
                'score' => $score,
                'row_height' => 110,
            ]);
        }

        foreach ($students as $studentIndex => $student) {
            $assignment = TypedExamAssignment::create([
                'typed_exam_id' => $typedExam->id,
                'student_id' => $student->id,
                'admin_id' => $advisor->id,
                'status' => 'completed',
                'result_visibility' => 'immediately',
                'answer_key_visibility' => 'immediately',
            ]);

            $correct = 3 + ($studentIndex % 3);
            $attempt = TypedExamAttempt::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'started_at' => $month['start']->copy()->addDays(14)->setTime(10, 0),
                'submitted_at' => $month['start']->copy()->addDays(14)->setTime(10, 55),
                'is_finished' => true,
                'score' => round($correct / $questions->count() * 100, 2),
                'analysis_status' => 'approved',
            ]);

            foreach ($questions as $questionIndex => $question) {
                $isCorrect = $questionIndex < $correct;
                TypedExamAttemptAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_option' => $isCorrect ? $question->correct_option : (($question->correct_option % 4) + 1),
                    'is_correct' => $isCorrect,
                    'answered_at' => $attempt->started_at->copy()->addMinutes(5 + $questionIndex * 6),
                ]);
            }

            $essayAssignment = EssayExamAssignment::create([
                'essay_exam_id' => $essayExam->id,
                'student_id' => $student->id,
                'admin_id' => $advisor->id,
                'status' => $studentIndex % 5 === 0 ? 'submitted' : 'graded',
            ]);

            EssayExamAttempt::create([
                'assignment_id' => $essayAssignment->id,
                'started_at' => $month['start']->copy()->addDays(18)->setTime(9, 0),
                'submitted_at' => $month['start']->copy()->addDays(18)->setTime(10, 20),
                'total_score' => $studentIndex % 5 === 0 ? null : 12 + ($studentIndex % 8),
                'status' => $studentIndex % 5 === 0 ? 'submitted' : 'graded',
                'consultant_message' => 'پاسخ ها بررسی شد و نکات اصلی در جلسه بعد مرور می شود.',
            ]);
        }
    }

    private function jalaliMonthContext(int $monthsAgo): array
    {
        $date = now()->subMonthsNoOverflow($monthsAgo);
        $jalali = Jalalian::fromCarbon($date);
        $year = (int) $jalali->getYear();
        $month = (int) $jalali->getMonth();
        $range = \App\Models\SmartReportCard::jalaliMonthRange($year, $month);

        return [
            'year' => $year,
            'month' => $month,
            'key' => sprintf('%04d-%02d', $year, $month),
            'start' => Carbon::parse($range['start']),
            'end' => Carbon::parse($range['end']),
        ];
    }

    private function fieldLabel(string $slug): string
    {
        return match ($slug) {
            'math' => 'ریاضی',
            'experimental' => 'تجربی',
            'human' => 'انسانی',
            default => 'بدون رشته',
        };
    }
}
