<?php

use App\Http\Controllers\FileDownloadController;
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
use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\Student\SmartReportCard\Index as SmartReportCardIndex;

Route::name('admin.')->group(function () {

    Route::get('/sign-in', AuthIndex::class)->name('sign-in')->middleware('guest:admin');
    Route::get('/logout', [AuthIndex::class, 'logout'])->name('logout')->middleware('auth:admin');

    Route::middleware('auth:admin')->group(function () {

        Route::get('/download/{token}', [FileDownloadController::class, 'download'])
            ->name('secure.download');

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

        // پشتیبان جذب سایت
        Route::prefix('acquisition-supporter')->name('acquisition-supporter.')->group(function () {
            // C-5 — داشبورد تجمیعی (آمار)
            Route::get('/dashboard', \App\Livewire\Admin\AcquisitionSupporter\StatsDashboard\Index::class)
                ->name('dashboard');

            // قدیمی — لیست تمام trial weeks با فیلتر (بقایای صفحه قدیمی)
            Route::get('/all-trials', \App\Livewire\Admin\AcquisitionSupporter\Dashboard::class)
                ->name('all-trials');

            // C-1 — دانش‌آموزان من
            Route::get('/my-students', \App\Livewire\Admin\AcquisitionSupporter\MyStudents\Index::class)
                ->name('my-students');

            // C-2 — تماس اولیه
            Route::get('/primary-call', \App\Livewire\Admin\AcquisitionSupporter\PrimaryCall\Index::class)
                ->name('primary-call');

            // C-3 — تماس ثانویه
            Route::get('/secondary-call', \App\Livewire\Admin\AcquisitionSupporter\SecondaryCall\Index::class)
                ->name('secondary-call');

            // C-4 — تماس اکسترا
            Route::get('/extra-call', \App\Livewire\Admin\AcquisitionSupporter\ExtraCall\Index::class)
                ->name('extra-call');

            // جزئیات دانش‌آموز (فرم ثبت تماس قدیمی برای backward compat)
            Route::get('/student/{id}', \App\Livewire\Admin\AcquisitionSupporter\StudentDetail::class)
                ->name('student');
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

        // پنل مشاور — درخواست‌های جابجایی مربوط به خودش
        Route::get('/consultant/reschedule',
            \App\Livewire\Admin\Consultant\Reschedule\Index::class)
            ->name('consultant.reschedule');
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


        // اتاق مشاوره

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
        Route::get('/smart-report-card/{student}', SmartReportCardIndex::class)
            ->name('student.smart-report-card');

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
