<?php

use App\Http\Controllers\FileDownloadController;
use App\Livewire\Client\AboutUs\Index as AboutUs;
use App\Livewire\Client\Auth\ForgotPassword as ForgotPassword;
use App\Livewire\Client\Auth\Login as authLogin;
use App\Livewire\Client\Auth\LoginOtp;
use App\Livewire\Client\Auth\Signup as authSignup;
use App\Livewire\Client\Blog\ExamQuestion\Index as ExamQuestionIndex;
use App\Livewire\Client\Blog\Weblog\Index as WeblogIndex;
use App\Livewire\Client\Blog\Weblog\Show;
use App\Livewire\Client\Cart\Info as cartInfo;
use App\Livewire\Client\ContactUs\Index as ContactUs;
use App\Livewire\Client\Course\Index as CourseIndex;
use App\Livewire\Client\Profile\Classification\Classify;
use App\Livewire\Client\Profile\Classification\ProjectList;
use App\Livewire\Client\Home\Index as HomeIndex;

use App\Livewire\Client\Profile\Installment\Installment as ProfileInstallment;
use App\Livewire\Client\Profile\Installment\InstallmentDetail as ProfileInstallmentDetail;
use App\Livewire\Client\Profile\Notification as ProfileNotification ;
use App\Livewire\Client\Profile\ProfessionalTools\Index as ProfessionalToolsIndex;
use App\Livewire\Client\Profile\ProfessionalTools\PomodoroTimer as ProfessionalToolsPomodoroTimer;
use App\Livewire\Client\Profile\ProfessionalTools\StudySession;
use App\Livewire\Client\Profile\ReportStudentStudy as ProfileReportStudentStudy;
use App\Livewire\Client\Profile\Star;
use App\Livewire\Client\Profile\TypedExam\TypedExamList;
use App\Livewire\Client\Profile\TypedExam\TypedExamResult;
use App\Livewire\Client\Profile\TypedExam\TypedExamTest;
use App\Livewire\Client\Terms\Index as RuleIndex;
use App\Livewire\Client\Product\Index as ProductIndex;
use App\Livewire\Client\Cart\Index as CartIndex;
use App\Livewire\Client\Payment\Callback as PaymentCallback;
use App\Livewire\Client\Profile\Dashboard as ProfileDashboard;
use App\Livewire\Client\Profile\Edit as ProfileEdit;
use App\Livewire\Client\Profile\Financial as ProfileFinancial;
use App\Livewire\Client\Profile\Barnameh as ProfilePlan;
use App\Livewire\Client\Profile\PersonalInformation as ProfilePersonalInformation;
use App\Livewire\Client\Profile\Report as ProfileReport;
use App\Livewire\Client\Shop\Index as ShopIndex;
use App\Livewire\Client\Profile\Ticket\Index as ProfileTicketIndex;
use App\Livewire\Client\Profile\Ticket\Show as ProfileTicketShow;
use App\Livewire\Client\Profile\Ticket\Create as ProfileTicketCreate;

use App\Livewire\Client\Profile\Consultation\SessionList as ConsultationSessionList;

use App\Livewire\Client\Profile\Consultation\PreSessionWizard as ConsultationPreSessionWizard;

use App\Livewire\Client\Profile\Consultation\WeeklyProgramView as ConsultationWeeklyProgramView;

use Illuminate\Support\Facades\Route;

Route::name('client.')->group(function () {
    Route::get('/download/{token}', [FileDownloadController::class, 'download'])
        ->name('secure.download');

    Route::get('/', HomeIndex::class)->name('home');

    Route::get('/shop',ShopIndex::class)->name('shop');
    Route::get('/product/{p_code}/{slug?}', ProductIndex::class)->name('product');

    Route::get('/terms',RuleIndex::class)->name('terms');
    Route::get('/about-us',AboutUs::class)->name('about-us');
    Route::get('/contact-us',ContactUs::class)->name('contact-us');

    Route::get('/blog/all',WeblogIndex::class)->name('blog');
    Route::get('/blog/{blog_code}/{slug}', Show::class)->name('blog.show');
    Route::get('/course',CourseIndex::class)->name('course');
    Route::get('/blog/exam-question',ExamQuestionIndex::class)->name('blog.ExamQuestion');



    Route::middleware('guest')->group(function () {
        Route::get('/login', authLogin::class)->name('auth.login');
        Route::get('/sign-up', authSignup::class)->name('auth.signup');
        Route::get('/login-sms', LoginOtp::class)->name('auth.otp');
        Route::get('/forgot-password',ForgotPassword::class)->name('auth.forgotPassword');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/checkout/cart',CartIndex::class)->name('checkout.cart');
        Route::get('/checkout/cart/orderInfo',cartInfo::class)->name('checkout.cart.info');
        Route::get('/logout', [authLogin::class,'clientLogout'])->name('logout');
        Route::get('/payment/callback',PaymentCallback::class)->name('payment.callback');


        Route::prefix('profile')->name('profile.')->group(function () {
            //Profile
            Route::get('/dashboard',ProfileDashboard::class)->name('dashboard');
            Route::get('/star',Star::class)->name('star');
            Route::get('/reportStudentStudy',ProfileReportStudentStudy::class)->name('reportStudentStudy');
            Route::get('/edit',ProfileEdit::class)->name('edit');
            Route::get('/financial',ProfileFinancial::class)->name('financial');
            Route::get('/installment',ProfileInstallment::class)->name('installment');
            Route::get('/installmentDetail',ProfileInstallmentDetail::class)->name('installmentDetail');
            Route::get('/plan',ProfilePlan::class)->name('plan');
            Route::get('/personalInformation',ProfilePersonalInformation::class)->name('personal');
            Route::get('/report',ProfileReport::class)->name('report');
            Route::get('/ProfessionalTools',ProfessionalToolsIndex::class)->name('professionalTools.index');
            Route::get('/ProfessionalTools/pomodoro',ProfessionalToolsPomodoroTimer::class)->name('professionalTools.pomodoro');
            Route::get('/studySession',StudySession::class)->name('studySession');
//          Ticketing Route
            Route::get('/ticket',ProfileTicketIndex::class)->name('ticket');
            Route::get('/ticket/{ticket}/show',ProfileTicketShow::class)->name('ticket.show');
            Route::get('/ticket-create',ProfileTicketCreate::class)->name('ticket.create');


            Route::get('/notification',ProfileNotification::class)->name('notification');

            // Typed Exam Routes (آزمون‌های تایپی)

            Route::get('/exams', TypedExamList::class)->name('typed-exam.list');

            Route::get('/exam/{assignmentId}/test', TypedExamTest::class)->name('typed-exam.test');

            Route::get('/exam/result/{attemptId}', TypedExamResult::class)->name('typed-exam.result');


            // Classification Routes
            Route::get('/classification', ProjectList::class)->name('classification.projects');
            Route::get('/{project}/classify/{grade}', Classify::class)->name('classification.classify');

            // Consultation Routes (جلسات مشاوره)

            Route::prefix('consultation')->name('consultation.')->group(function () {

                Route::get('/sessions', ConsultationSessionList::class)->name('sessions');

                Route::get('/session/{session}/pre-session', ConsultationPreSessionWizard::class)->name('pre-session');

                Route::get('/weekly-program/{program}', ConsultationWeeklyProgramView::class)->name('weekly-program');

            });

        });


    });
});
