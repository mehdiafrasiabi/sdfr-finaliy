<?php

use App\Http\Controllers\FileDownloadController;
use App\Livewire\Admin\Auth\Index as AuthIndex;
use App\Livewire\Admin\Dashboard\Index as DashboardIndex;
use App\Livewire\Admin\Notification\Create as NotificationCreate;
use App\Livewire\Admin\Student\Consultation\CreateAdvisingSession as ConsultationCreateAdvisingSession;
use App\Livewire\Admin\Student\Consultation\Index as StudentConsultation;
use App\Livewire\Admin\Student\Consultation\WeeklyProgramUpload as ConsultationWeeklyProgram;
use App\Livewire\Admin\Student\Index as StudentIndex;
use App\Livewire\Admin\Student\Plan\Detail as StudentPlanDetail;
use App\Livewire\Admin\Student\Plan\Index as StudentPlanIndex;
use App\Livewire\Admin\Student\ReportDailyActivities\Detail as ReportDailyActivitiesDetail;
use App\Livewire\Admin\Student\ReportDailyActivities\Index as ReportDailyActivitiesIndex;
use App\Livewire\Admin\Student\Reports\DailyReportManagement;
use App\Livewire\Admin\Student\Reports\ReportDaily as StudentReportDaily;
use App\Livewire\Admin\Student\Reports\ReportMissing as ReportMissingIndex;
use App\Livewire\Admin\Student\ReportStatus\Detail as StudentReportStatusDetail;
use App\Livewire\Admin\Student\ReportStatus\Index as StudentReportStatusIndex;
use App\Livewire\Admin\Student\StudySession\Index as StudentStudySessionIndex;
use App\Livewire\Admin\Student\StudySession\Show as StudentStudySessionShow;
use Illuminate\Support\Facades\Route;


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



        // گزارش‌های ارسال نشده

        Route::get('/report-not-send', ReportMissingIndex::class)->name('reportMissing')
            ->middleware('admin.permission:admin.report-missing.view');

        Route::get('/daily-reports', DailyReportManagement::class)
            ->middleware('admin.permission:admin.daily-report.view');


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

        Route::prefix('classification')->name('classification.')->middleware('admin.permission:admin.classification.view')->group(function () {

            Route::get('/', \App\Livewire\Admin\Classification\Dashboard::class)->name('dashboard');

            Route::get('/{project}/students', \App\Livewire\Admin\Classification\Students::class)->name('students');

            Route::get('/{project}/students/{user}/detail', \App\Livewire\Admin\Classification\StudentDetail::class)->name('detail');

        });

    });

});
