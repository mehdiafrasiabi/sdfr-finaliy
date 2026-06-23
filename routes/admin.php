<?php

use App\Livewire\Admin\Auth\Index as AuthIndex;
use App\Livewire\Admin\Dashboard\Index as DashboardIndex;
use App\Livewire\Admin\Notification\Create as NotificationCreate;
use App\Livewire\Admin\Student\Consultation\CreateAdvisingSession as ConsultationCreateAdvisingSession;
use App\Livewire\Admin\Student\Consultation\Index as StudentConsultation;
use App\Livewire\Admin\Student\Consultation\WeeklyProgramUpload as ConsultationWeeklyProgram;
use App\Livewire\Admin\Student\Index as StudentIndex;
use App\Livewire\Admin\Student\ReportDailyActivities\Detail as ReportDailyActivitiesDetail;
use App\Livewire\Admin\Student\ReportDailyActivities\Index as ReportDailyActivitiesIndex;
use App\Livewire\Admin\Student\Reports\ReportDaily as StudentReportDaily;
use App\Livewire\Admin\Student\Reports\ReportMissing as ReportMissingIndex;
use App\Livewire\Admin\Student\StudySession\Index as StudentStudySessionIndex;
use App\Livewire\Admin\Student\StudySession\Show as StudentStudySessionShow;
use App\Livewire\Admin\Ticket\Index as TicketIndex;
use App\Livewire\Admin\Ticket\Show as TicketShow;
use App\Livewire\Admin\AdminUser\Index as AdminUserIndex;
use App\Livewire\Admin\AdminUser\WorkSchedule as AdminUserWorkSchedule;
use App\Livewire\Admin\ContactDocumentation\Index as ContactDocumentationIndex;
use App\Livewire\Admin\Student\SmartReportCard\Index as SmartReportCardIndex;
use App\Livewire\Admin\Student\SmartReportCard\Show as SmartReportCardShow;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Student\SmartReportCard\View as SmartReportCardView;

Route::name('admin.')->group(function () {

    Route::get('/sign-in', AuthIndex::class)->name('sign-in')->middleware('guest:admin');
    Route::get('/logout', [AuthIndex::class, 'logout'])->name('logout')->middleware('auth:admin');

    Route::middleware('auth:admin')->group(function () {

        // داشبورد - نیاز به دسترسی مشاهده داشبورد

        Route::get('/dashboard', DashboardIndex::class)->name('dashboard.index')
            ->middleware('admin.permission:admin.dashboard.view');


        // مدیریت دانش‌آموزان

        Route::get('/student', StudentIndex::class)->name('student.index')
            ->middleware('admin.permission:admin.students.view');

        Route::get('/studentReportDay', StudentReportDaily::class)->name('reportStudentDay')
            ->middleware('admin.permission:admin.daily-report.view');

        // مدیریت ادمین‌ها و برنامه کاری آن‌ها
        Route::get('/admin-users', AdminUserIndex::class)->name('admin-user.index');
        Route::get('/admin-users/{admin}/work-schedule', AdminUserWorkSchedule::class)
            ->name('admin-user.work-schedule');

        // لیست مشاوران و پشتیبانان
        Route::get('/consultants', \App\Livewire\Admin\Consultant\Index::class)
            ->name('consultants.index');
        Route::get('/supporters', \App\Livewire\Admin\Supporter\Index::class)
            ->name('supporters.index');

        // پشتیبان آزمایشی — مدیریت هفته آزمایشی
        Route::get('/trial-week', \App\Livewire\Admin\TrialWeek\Index::class)
            ->name('trial-week.index');

        // مشاور جذب یک هفته آزمایشی (جریان یکپارچهٔ جدید — جایگزین acquisition-supporter)
        Route::prefix('trial-acquisition')->name('trial-acquisition.')->group(function () {
            Route::get('/', \App\Livewire\Admin\TrialAcquisition\Index::class)->name('index');
            Route::get('/dashboard', \App\Livewire\Admin\TrialAcquisition\Dashboard::class)->name('dashboard');
        });

        // مدیر مدرسه (نقش school-manager) — قابلیت‌های پنل اختصاصی
        Route::prefix('school-manager')->name('school-manager.')->group(function () {
            Route::get('/dashboard', \App\Livewire\Admin\SchoolManager\Dashboard::class)
                ->name('dashboard')->middleware('admin.permission:admin.school-manager.dashboard.view');
            Route::get('/academic-status', \App\Livewire\Admin\SchoolManager\AcademicStatus::class)
                ->name('academic-status')->middleware('admin.permission:admin.school-manager.academic-status.view');
            Route::get('/advising', \App\Livewire\Admin\SchoolManager\AdvisingStats::class)
                ->name('advising')->middleware('admin.permission:admin.school-manager.advising.view');
            Route::get('/grades', \App\Livewire\Admin\SchoolManager\GradeEntry::class)
                ->name('grades')->middleware('admin.permission:admin.school-manager.grades.manage');
            Route::get('/report-cards', \App\Livewire\Admin\SchoolManager\ReportCards::class)
                ->name('report-cards')->middleware('admin.permission:admin.school-manager.academic-status.view');
            Route::get('/classification-insights', \App\Livewire\Admin\SchoolManager\ClassificationInsights::class)
                ->name('classification-insights')->middleware('admin.permission:admin.school-manager.academic-status.view');
            Route::get('/exam-stats', \App\Livewire\Admin\SchoolManager\ExamStats::class)
                ->name('exam-stats')->middleware('admin.permission:admin.school-manager.academic-status.view');
            Route::get('/call-report', \App\Livewire\Admin\SchoolManager\CallReport::class)
                ->name('call-report')->middleware('admin.permission:admin.school-manager.academic-status.view');
            Route::get('/students/{student}/progress', \App\Livewire\Admin\SchoolManager\StudentProgress::class)
                ->name('student.progress')->middleware('admin.permission:admin.school-manager.student.progress.view');
        });

        // مدیر آموزشی — درخواست‌های تعیین وقت و جابجایی
        Route::get('/educational-manager/appointments',
            \App\Livewire\Admin\EducationalManager\Appointment\Index::class)
            ->name('educational-manager.appointments');
        Route::get('/educational-manager/reschedule',
            \App\Livewire\Admin\EducationalManager\Reschedule\Index::class)
            ->name('educational-manager.reschedule');

        // مدیر آموزشی — دانش‌آموزان جدید (آزمایشی و خرید کرده)
        Route::get('/educational-manager/new-trial-students',
            \App\Livewire\Admin\EducationalManager\NewTrialStudents\Index::class)
            ->name('educational-manager.new-trial-students');
        Route::get('/educational-manager/new-purchased-students',
            \App\Livewire\Admin\EducationalManager\NewPurchasedStudents\Index::class)
            ->name('educational-manager.new-purchased-students');

        // مدیر آموزشی — جذب تلفنی
        Route::prefix('educational-manager/phone-acquisition')
            ->name('educational-manager.phone-acquisition.')
            ->group(function () {
                Route::get('/dashboard',
                    \App\Livewire\Admin\EducationalManager\PhoneAcquisition\Dashboard::class)
                    ->name('dashboard');
                Route::get('/leads',
                    \App\Livewire\Admin\EducationalManager\PhoneAcquisition\Leads\Index::class)
                    ->name('leads');
                Route::get('/assign',
                    \App\Livewire\Admin\EducationalManager\PhoneAcquisition\Leads\Assign::class)
                    ->name('assign');
                Route::get('/history',
                    \App\Livewire\Admin\EducationalManager\PhoneAcquisition\Leads\History::class)
                    ->name('history');
                Route::get('/history/{lead}',
                    \App\Livewire\Admin\EducationalManager\PhoneAcquisition\Leads\Show::class)
                    ->name('history.show');
                Route::get('/goals',
                    \App\Livewire\Admin\EducationalManager\PhoneAcquisition\Goals\Index::class)
                    ->name('goals');
                Route::get('/receipts',
                    \App\Livewire\Admin\EducationalManager\PhoneAcquisition\Receipts\Index::class)
                    ->name('receipts');
            });

        // مشاور جذب تلفنی — پنل مشاور
        Route::prefix('phone-acquisition')
            ->name('phone-acquisition.')
            ->group(function () {
                Route::get('/queue',
                    \App\Livewire\Admin\PhoneAcquisition\Queue\Index::class)
                    ->name('queue');
                Route::get('/follow-ups',
                    \App\Livewire\Admin\PhoneAcquisition\FollowUps\Index::class)
                    ->name('follow-ups');
                Route::get('/receipts',
                    \App\Livewire\Admin\PhoneAcquisition\Receipts\Index::class)
                    ->name('receipts');
            });

        // پنل مشاور — درخواست‌های جابجایی مربوط به خودش
        Route::get('/consultant/reschedule',
            \App\Livewire\Admin\Consultant\Reschedule\Index::class)
            ->name('consultant.reschedule');

        // پنل مشاور — ثبت نمرات کارنامهٔ ماهانه برای دانش‌آموزان تحت مشاوره
        Route::get('/consultant/grades',
            \App\Livewire\Admin\Consultant\GradeEntry::class)
            ->name('consultant.grades');

        // پنل مشاور — ثبت تماس اورژانسی برای پیگیری مدیر مدرسه
        Route::get('/consultant/emergency-calls',
            \App\Livewire\Admin\Consultant\EmergencyCalls::class)
            ->name('consultant.emergency-calls');
        // اعلان‌ها

        Route::get('/notification', NotificationCreate::class)->name('student.notification')
            ->middleware('admin.permission:admin.notification.send');

        // گزارش فعالیت روزانه

        Route::get('/studentReportDailyActivities', ReportDailyActivitiesIndex::class)->name('student.reportDailyActivities.index')
            ->middleware('admin.permission:admin.daily-activities.view');

        Route::get('/studentReportDailyActivities/{student}/ReportDailyActivities', ReportDailyActivitiesDetail::class)->name('student.reportDailyActivities.detail')
            ->middleware('admin.permission:admin.daily-activities.view');


        // جلسات مطالعه

        Route::get('/studentStudySession', StudentStudySessionIndex::class)->name('student.studySession.index')
            ->middleware('admin.permission:admin.study-session.view');

        Route::get('/studentStudySession/{student}/study', StudentStudySessionShow::class)->name('student.studySession.detail')
            ->middleware('admin.permission:admin.study-session.view');

        Route::get('/smart-report-card', SmartReportCardIndex::class)
            ->name('student.smartReportCard.index');

        Route::get('/smart-report-card/{student}', SmartReportCardShow::class)
            ->name('student.smartReportCard.detail');
        // اتاق مشاوره

        Route::get('/smart-report-card/{student}/view/{year}/{month}', SmartReportCardView::class)
            ->name('student.smartReportCard.view');

        Route::get('/advising-sessions', StudentConsultation::class)->name('advising-sessions')
            ->middleware('admin.permission:admin.advising-session.view');

        Route::get('/advising-sessions/{student}/create', ConsultationCreateAdvisingSession::class)->name('student.advising-sessions.create')
            ->middleware('admin.permission:admin.advising-session.create');

        Route::get('/advising-sessions/{student}/weekly-program/{session?}', ConsultationWeeklyProgram::class)->name('student.weekly-program')
            ->middleware('admin.permission:admin.weekly-program.upload');
        // مستندات تماس
        Route::get('/contact-documentation', ContactDocumentationIndex::class)->name('contact-documentation.index')
            ->middleware('admin.permission:admin.contact-documentation.view');
        // گزارش‌های ارسال نشده
        // کارنامه هوشمند

        Route::get('/report-not-send', ReportMissingIndex::class)->name('reportMissing')
            ->middleware('admin.permission:admin.report-missing.view');
        // آزمون‌های تایپی
        Route::get('/typed-exams', \App\Livewire\Admin\TypedExam\ExamIndex::class)->name('typed-exams.index')
            ->middleware('admin.permission:admin.typed-exams.view');
        Route::get('/typed-exams/{examId}/assignment', \App\Livewire\Admin\TypedExam\ExamAssignment::class)->name('typed-exams.assignment')
            ->middleware('admin.permission:admin.typed-exams.assign');
        Route::get('/typed-exams/{examId}/stats', \App\Livewire\Admin\TypedExam\ExamStats::class)->name('typed-exams.stats')
            ->middleware('admin.permission:admin.typed-exams.stats');
        Route::get('/typed-exams/{examId}/student-result/{attemptId}', \App\Livewire\Admin\TypedExam\StudentResult::class)->name('typed-exams.student-result')
            ->middleware('admin.permission:admin.typed-exams.results');
        // طبقه‌بندی آموزشی

        // آزمون‌های تشریحی (مبحثی)
        Route::prefix('essay-exams')->name('essay-exams.')->group(function () {
            Route::get('/', \App\Livewire\Admin\EssayExam\ExamList::class)->name('index');
            Route::get('/create', \App\Livewire\Admin\EssayExam\ExamWizard::class)->name('create');
            Route::get('/{examId}/edit', \App\Livewire\Admin\EssayExam\ExamWizard::class)->name('edit');
            Route::get('/{examId}/assign', \App\Livewire\Admin\EssayExam\ExamAssign::class)->name('assign');
            Route::get('/{examId}/assignments', \App\Livewire\Admin\EssayExam\AssignmentList::class)->name('assignments');
            Route::get('/attempt/{attemptId}/review', \App\Livewire\Admin\EssayExam\AttemptReview::class)->name('attempt.review');
            Route::get('/{examId}/answer-sheet', [\App\Http\Controllers\EssayExamAnswerSheetController::class, 'download'])
                ->name('answer-sheet');
        });

        // تیکت‌ها و پشتیبانی
        Route::get('/tickets', TicketIndex::class)->name('ticket.index');
//            ->middleware('admin.permission:admin.tickets.view');
        Route::get('/tickets/{ticket}', TicketShow::class)->name('ticket.show');
//            ->middleware('admin.permission:admin.tickets.view');
        Route::prefix('classification')->name('classification.')->middleware('admin.permission:admin.classification.view')->group(function () {
            Route::get('/', \App\Livewire\Admin\Classification\Dashboard::class)->name('dashboard');
            Route::get('/{project}/students', \App\Livewire\Admin\Classification\Students::class)->name('students');
            Route::get('/{project}/students/{user}/detail', \App\Livewire\Admin\Classification\StudentDetail::class)->name('detail');
        });

    });

});
