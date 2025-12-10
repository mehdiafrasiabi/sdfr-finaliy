<?php

use App\Http\Controllers\FileDownloadController;
use App\Livewire\Admin\Blog\Create as CreateBlog;
use App\Livewire\Admin\Blog\Index as BlogIndex;
use App\Livewire\Admin\Profile\Index as profileIndex;
use App\Livewire\Admin\ReportMissing\Index as ReportMissingIndex;
use App\Livewire\Admin\Student\Consultation\CreateAdvisingSession as ConsultationCreateAdvisingSession;

use App\Livewire\Admin\Student\Consultation\Index as StudentConsultation;
use App\Livewire\Admin\Student\Consultation\WeeklyProgramUpload as ConsultationWeeklyProgram;

use App\Livewire\Admin\Notification\Create as NotificationCreate;
use App\Livewire\Admin\ReportStudentStudy\Index as ReportStudentStudy;
use App\Livewire\Admin\SendToSuperAdmin\Index as SendToSuperAdminContactDocumentation;

use App\Livewire\Admin\Student\MeetGoogle;
use App\Livewire\Admin\Student\Plan\Detail as StudentPlanDetail;
use App\Livewire\Admin\Student\Plan\Index as StudentPlanIndex;
use App\Livewire\Admin\Student\ReportDaily as StudentReportDaily;
use App\Livewire\Admin\Student\ReportStatus\Index as StudentReportStatusIndex;
use App\Livewire\Admin\Student\ReportStatus\Detail as StudentReportStatusDetail;
use App\Livewire\Admin\Student\StudySession\Index as StudentStudySessionIndex;
use App\Livewire\Admin\Student\StudySession\Show as StudentStudySessionShow;
use App\Livewire\Admin\Todo\Index as TodoIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard\Index as DashboardIndex;
use App\Livewire\Admin\Setting\ContactUs\Index as ContactUsIndex;
use App\Livewire\Admin\Student\Index as StudentIndex;
use App\Livewire\Admin\Student\ReportCalling\Index as ReportCallingIndex;
use App\Livewire\Admin\Student\ReportCalling\Detail as ReportCallingDetail;
use  App\Livewire\Admin\Student\ReportDailyActivities\Index as ReportDailyActivitiesIndex;
use  App\Livewire\Admin\Student\ReportDailyActivities\Detail as ReportDailyActivitiesDetail;
use App\Livewire\Admin\Auth\Index as AuthIndex;
use App\Livewire\Admin\Blog\CkUpload;


Route::name('admin.')->group(function () {

    Route::get('/sign-in', AuthIndex::class)->name('sign-in')->middleware('guest:admin');
    Route::get('/logout', [AuthIndex::class, 'logout'])->name('logout')->middleware('auth:admin');

    Route::middleware('auth:admin')->group(function () {

        Route::get('/download/{token}', [FileDownloadController::class, 'download'])
            ->name('secure.download');

        //Dashboard
        Route::get('/dashboard', DashboardIndex::class)->name('dashboard.index');
        Route::get('/profile', profileIndex::class)->name('profile');

        //Setting Website
        Route::get('/contact-us', ContactUsIndex::class)->name('contact-us');

        //Student
        Route::get('/student', StudentIndex::class)->name('student.index');
        Route::get('/studentReportDay', StudentReportDaily::class)->name('reportStudentDay');
        Route::get('/student/{student}/meetGoogle', MeetGoogle::class)->name('student.meetGoogle');

        Route::get('/sendFile/contactDocumentation', SendToSuperAdminContactDocumentation::class)->name('sendFile.contactDocumentation');
        Route::get('/sendFile/reportStudentStudy', ReportStudentStudy::class)->name('sendFile.ReportStudentStudy');


        Route::get('/notification', NotificationCreate::class)->name('student.notification');

        Route::get('/studentPlan', StudentPlanIndex::class)->name('student.plan.index');
        Route::get('/studentPlan/{student}/plan', StudentPlanDetail::class)->name('student.plan.detail');

        Route::get('/studentReportStatus', StudentReportStatusIndex::class)->name('student.reportStudent.index');
        Route::get('/studentReportStatus/{student}/ReportStatus', StudentReportStatusDetail::class)->name('student.reportStudent.detail');

        Route::get('/studentReportCalling', ReportCallingIndex::class)->name('student.reportCalling.index');
        Route::get('/studentReportCalling/{student}/ReportCalling', ReportCallingDetail::class)->name('student.reportCalling.detail');

        Route::get('/studentReportDailyActivities', ReportDailyActivitiesIndex::class)->name('student.reportDailyActivities.index');
        Route::get('/studentReportDailyActivities/{student}/ReportDailyActivities', ReportDailyActivitiesDetail::class)->name('student.reportDailyActivities.detail');


        Route::get('/blog/index', BlogIndex::class)->name('blog.index');
        Route::get('/blog/create', CreateBlog::class)->name('blog.create');
        Route::post('/admin/blog/{blog}/ckeditor/upload', [CkUpload::class, 'upload'])->name('blog.ckeUpload');

        Route::get('/studentStudySession', StudentStudySessionIndex::class)->name('student.studySession.index');
        Route::get('/studentStudySession/{student}/study', StudentStudySessionShow::class)->name('student.studySession.detail');

        Route::get('/advising-sessions', StudentConsultation::class)->name('advising-sessions');
        Route::get('/advising-sessions/{student}/create', ConsultationCreateAdvisingSession::class)->name('student.advising-sessions.create');
        Route::get('/advising-sessions/{student}/weekly-program/{session?}', ConsultationWeeklyProgram::class)->name('student.weekly-program');

        Route::get('/todo', TodoIndex::class)->name('todo');

        Route::get('/report-not-send', ReportMissingIndex::class)->name('reportMissing');

        // Typed Exam Routes (آزمون‌های تایپی)

        Route::get('/typed-exams', \App\Livewire\Admin\TypedExam\ExamIndex::class)->name('typed-exams.index');

        Route::get('/typed-exams/{examId}/assignment', \App\Livewire\Admin\TypedExam\ExamAssignment::class)->name('typed-exams.assignment');

        Route::get('/typed-exams/{examId}/stats', \App\Livewire\Admin\TypedExam\ExamStats::class)->name('typed-exams.stats');

        Route::get('/typed-exams/{examId}/student-result/{attemptId}', \App\Livewire\Admin\TypedExam\StudentResult::class)->name('typed-exams.student-result');


        Route::prefix('classification')->name('classification.')->group(function () {

            Route::get('/', \App\Livewire\Admin\Classification\Dashboard::class)->name('dashboard');

            Route::get('/{project}/students', \App\Livewire\Admin\Classification\Students::class)->name('students');

            Route::get('/{project}/students/{user}/detail', \App\Livewire\Admin\Classification\StudentDetail::class)->name('detail');

        });

    });

});
