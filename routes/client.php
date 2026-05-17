<?php

use App\Http\Controllers\FileDownloadController;
use App\Livewire\Client\AboutUs\Index as AboutUs;
use App\Livewire\Client\Auth\ForgotPassword as ForgotPassword;
use App\Livewire\Client\Auth\Login as authLogin;
use App\Livewire\Client\Onboarding\TrialWeekOnboarding;
use App\Livewire\Client\Blog\Weblog\Index as WeblogIndex;
use App\Livewire\Client\ContactUs\Index as ContactUs;
use App\Livewire\Client\Home\Index as HomeIndex;
use App\Livewire\Client\Download\Index as DownloadIndex;
use App\Livewire\Client\Payment\Callback as PaymentCallback;
use App\Livewire\Client\Profile\plan as ProfilePlan;
use App\Livewire\Client\Profile\Classification\Classify;
use App\Livewire\Client\Profile\Classification\ProjectList;
use App\Livewire\Client\Profile\Consultation\PreSessionWizard as ConsultationPreSessionWizard;
use App\Livewire\Client\Profile\Consultation\SessionList as ConsultationSessionList;
use App\Livewire\Client\Profile\Consultation\WeeklyProgramView as ConsultationWeeklyProgramView;
use App\Livewire\Client\Profile\Dashboard as ProfileDashboard;
use App\Livewire\Client\Profile\Consultation\ClassScheduleUpload as ConsultationClassScheduleUpload;
use App\Livewire\Client\Profile\Edit as ProfileEdit;
use App\Livewire\Client\Profile\TrialWeek\Guide as TrialWeekGuide;
use App\Livewire\Client\Profile\TrialWeek\SessionAnalysis as TrialWeekSessionAnalysis;
use App\Livewire\Client\Profile\Financial as ProfileFinancial;
use App\Livewire\Client\Profile\Installment\Installment as ProfileInstallment;
use App\Livewire\Client\Profile\Installment\InstallmentDetail as ProfileInstallmentDetail;
use App\Livewire\Client\Profile\Notification as ProfileNotification;
use App\Livewire\Client\Profile\Report as ProfileReport;
use App\Livewire\Client\Profile\ReportStudentStudy as ProfileReportStudentStudy;
use App\Livewire\Client\Profile\Star;
use App\Livewire\Client\Profile\StudySession;
use App\Livewire\Client\Profile\Ticket\Create as ProfileTicketCreate;
use App\Livewire\Client\Profile\Ticket\Index as ProfileTicketIndex;
use App\Livewire\Client\Profile\Ticket\Show as ProfileTicketShow;
use App\Livewire\Client\Profile\TypedExam\TypedExamList;
use App\Livewire\Client\Profile\TypedExam\TypedExamResult;
use App\Livewire\Client\Profile\TypedExam\TypedExamTest;
use App\Livewire\Client\ExamCountdown\Index as ExamCountdownIndex;
use App\Livewire\Client\Terms\Index as RuleIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Client\Profile\Wallet as ProfileWallet;
use App\Livewire\Client\PercentCalculator\Index as PercentCalculatorIndex;



Route::name('client.')->group(function () {
    Route::get('/download/{token}', [FileDownloadController::class, 'download'])
        ->name('secure.download');

    Route::get('/', HomeIndex::class)->name('home');
    Route::get('/application', DownloadIndex::class)->name('download');
    Route::redirect('/shop', '/')->name('shop');
    Route::redirect('/product/{p_code}/{slug?}', '/')->name('product');

    Route::get('/terms',RuleIndex::class)->name('terms');
    Route::get('/about-us',AboutUs::class)->name('about-us');
    Route::get('/contact-us',ContactUs::class)->name('contact-us');

    Route::get('/blog',WeblogIndex::class)->name('blog');
    Route::get('/konkur', ExamCountdownIndex::class)->name('exam-countdown');
    Route::get('/percentCalculator', PercentCalculatorIndex::class)->name('percent-calculator');


    // ثبت‌نام فقط از طریق هفته آزمایشی
    Route::get('/start', TrialWeekOnboarding::class)->name('onboarding')->middleware('guest');
    Route::redirect('/sign-up', '/start')->name('auth.signup');

    Route::middleware('guest')->group(function () {
        Route::get('/login', authLogin::class)->name('auth.login');
        Route::get('/forgot-password',ForgotPassword::class)->name('auth.forgotPassword');
    });

    // صفحه انتظار برای تخصیص پشتیبان (auth)
    Route::get('/profile/waiting-for-supporter',
        \App\Livewire\Client\Profile\TrialWeek\WaitingForSupporter::class)
        ->middleware('auth')
        ->name('profile.waiting-for-supporter');

    // B-2: صفحهٔ پرداخت اختصاصی (auth)
    Route::get('/purchase',
        \App\Livewire\Client\Purchase\Index::class)
        ->middleware('auth')
        ->name('purchase');

    Route::middleware('auth')->group(function () {
        Route::redirect('/shopping-cart', '/')->name('checkout.cart');
        Route::redirect('/shopping-cart-info', '/')->name('checkout.cart.info');
        Route::get('/logout', [authLogin::class,'clientLogout'])->name('logout');
        Route::get('/payment/callback',PaymentCallback::class)->name('payment.callback');


        Route::prefix('profile')->name('profile.')->middleware(['client.active', 'trial.step'])->group(function () {
            //Profile
            Route::get('/dashboard',ProfileDashboard::class)->name('dashboard');
            Route::get('/star',Star::class)->name('star');
            Route::get('/reportStudentStudy',ProfileReportStudentStudy::class)->name('reportStudentStudy');
            Route::get('/edit',ProfileEdit::class)->name('edit');
            Route::get('/financial',ProfileFinancial::class)->name('financial');
            Route::get('/installment',ProfileInstallment::class)->name('installment');
            Route::get('/installmentDetail',ProfileInstallmentDetail::class)->name('installmentDetail');
            Route::get('/plan',ProfilePlan::class)->name('plan');
            Route::get('/report',ProfileReport::class)->name('report');
            Route::get('/studySession',StudySession::class)->name('studySession');

//          Ticketing Route
            Route::get('/ticket',ProfileTicketIndex::class)->name('ticket');
            Route::get('/ticket/{ticket}/show',ProfileTicketShow::class)->name('ticket.show');
            Route::get('/ticket-create',ProfileTicketCreate::class)->name('ticket.create');

            //کیف پوال
            Route::get('/wallet',ProfileWallet::class)->name('wallet');
            // نوتیفیکیشن
            Route::get('/notification',ProfileNotification::class)->name('notification');

            // Typed Exam Routes (آزمون‌های تایپی)
            Route::get('/exams', TypedExamList::class)->name('typed-exam.list');
            Route::get('/exam/{assignmentId}/test', TypedExamTest::class)->name('typed-exam.test');
            Route::get('/exam/result/{attemptId}', TypedExamResult::class)->name('typed-exam.result');

            // Essay Exam Routes (آزمون‌های تشریحی مبحثی)
            Route::get('/essay-exam/{assignmentId}/test', \App\Livewire\Client\Profile\EssayExam\EssayExamTest::class)
                ->name('essay-exam.test');
            Route::get('/essay-exam/result/{attemptId}', \App\Livewire\Client\Profile\EssayExam\EssayExamResult::class)
                ->name('essay-exam.result');
            Route::get('/essay-exam/{assignmentId}/answer-sheet', [\App\Http\Controllers\EssayExamAnswerSheetController::class, 'studentDownload'])
                ->name('essay-exam.answer-sheet');

            // Classification Routes
            Route::get('/classification', ProjectList::class)->name('classification.projects');
            Route::get('/{project}/classify/{grade}', Classify::class)->name('classification.classify');
            // تعیین وقت و جابجایی جلسات
            Route::get('/appointment', \App\Livewire\Client\Profile\Appointment\Index::class)
                ->name('appointment');
            // Trial Week Routes (هفته آزمایشی)
            Route::prefix('trial')->name('trial.')->group(function () {
                Route::get('/guide', TrialWeekGuide::class)->name('guide');
                Route::get('/session-analysis', TrialWeekSessionAnalysis::class)->name('session-analysis');
            });

            // School Student Reports (گزارش دانش‌آموز مدرسه)
            Route::prefix('school-report')->name('school.report.')->group(function () {
                Route::get('/', \App\Livewire\Client\School\ReportIndex::class)->name('index');
                Route::get('/new', \App\Livewire\Client\School\ReportCreate::class)->name('create');
            });

            // Consultation Routes (جلسات مشاوره)
            Route::prefix('consultation')->name('consultation.')->group(function () {
                Route::get('/sessions', ConsultationSessionList::class)->name('sessions');
                Route::get('/session/{session}/pre-session', ConsultationPreSessionWizard::class)->name('pre-session');
                Route::get('/weekly-program/{program}', ConsultationWeeklyProgramView::class)->name('weekly-program');
                Route::get('/class-schedule', ConsultationClassScheduleUpload::class)->name('class-schedule');
            });

        });


    });
});
