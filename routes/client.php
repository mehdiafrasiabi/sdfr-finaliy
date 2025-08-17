<?php
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
use App\Livewire\Client\Profile\Exam\ExamResult;
use App\Livewire\Client\Home\Index as HomeIndex;
use App\Livewire\Client\Profile\Exam\ExamList;
use App\Livewire\Client\Profile\Exam\ExamTest;
use App\Livewire\Client\Profile\Installment\Installment as ProfileInstallment;
use App\Livewire\Client\Profile\Installment\InstallmentDetail as ProfileInstallmentDetail;
use App\Livewire\Client\Profile\MeetGoogle as ProfileMeetGoogle;
use App\Livewire\Client\Profile\Notification as ProfileNotification ;
use App\Livewire\Client\Profile\ProfessionalTools\Index as ProfessionalToolsIndex;
use App\Livewire\Client\Profile\ProfessionalTools\PomodoroTimer as ProfessionalToolsPomodoroTimer;
use App\Livewire\Client\Profile\ProfessionalTools\StarredChecklist\Index as ProfessionalToolsStarredChecklistIndex;
use App\Livewire\Client\Profile\ProfessionalTools\StarredChecklist\TopicStar as TopicStar;
use App\Livewire\Client\Profile\ProfessionalTools\StudySession;
use App\Livewire\Client\Profile\ReportStudentStudy as ProfileReportStudentStudy;
use App\Livewire\Client\Profile\Star;
use App\Livewire\Client\Terms\Index as RuleIndex;
use App\Livewire\Client\Product\Index as ProductIndex;
use App\Livewire\Client\Cart\Index as CartIndex;
use App\Livewire\Client\Payment\callback as PaymentCallback;
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
use Illuminate\Support\Facades\Route;

Route::name('client.')->group(function () {
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

        //Profile
        Route::get('/profile-dashboard',ProfileDashboard::class)->name('profile.dashboard');
        Route::get('/profile-star',Star::class)->name('profile.star');

        Route::get('/ProfessionalTools/pomodoro',ProfessionalToolsPomodoroTimer::class)->name('profile.professionalTools.pomodoro');
        Route::get('/ProfessionalTools/studySession',StudySession::class)->name('profile.professionalTools.studySession');
        Route::get('/ProfessionalTools/starredChecklist',ProfessionalToolsStarredChecklistIndex::class)->name('profile.professionalTools.starredChecklist');
        Route::get('/ProfessionalTools/starredChecklist/detail',TopicStar::class)->name('profile.professionalTools.TopicStar');

        Route::get('/ProfessionalTools',ProfessionalToolsIndex::class)->name('profile.professionalTools.index');

        Route::get('/profile-meetGoogle',ProfileMeetGoogle::class)->name('profile.meetGoogle');

        Route::get('/profile-reportStudentStudy',ProfileReportStudentStudy::class)->name('profile.reportStudentStudy');

        Route::get('/profile-edit',ProfileEdit::class)->name('profile.edit');
        Route::get('/profile-financial',ProfileFinancial::class)->name('profile.financial');
        Route::get('/profile-installment',ProfileInstallment::class)->name('profile.installment');
        Route::get('/profile-installmentDetail',ProfileInstallmentDetail::class)->name('profile.installmentDetail');

        Route::get('/profile-plan',ProfilePlan::class)->name('profile.plan');

        Route::get('/profile-personalInformation',ProfilePersonalInformation::class)->name('profile.personal');

        Route::get('/profile-report',ProfileReport::class)->name('profile.report');

        Route::get('/profile/ticket',ProfileTicketIndex::class)->name('profile.ticket');
        Route::get('/profile/ticket/show/{ticket}',ProfileTicketShow::class)->name('profile.ticket.show');
        Route::get('/profile/profile-ticketCreate',ProfileTicketCreate::class)->name('profile.ticket.create');

        Route::get('/profile/exam',ExamList::class)->name('profile.exam.list');
        Route::get('/profile/exam/{exam}/result',ExamResult::class)->name('profile.exam.result');
        Route::get('/student/exams/{exam}/test', ExamTest::class)->name('profile.exam.test');

        Route::get('/profile/notification',ProfileNotification::class)->name('profile.notification');

    });
});
