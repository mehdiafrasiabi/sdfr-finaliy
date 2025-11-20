<?php

use App\Livewire\Manager\AdminManage\Index as AdminManage;
use App\Livewire\Manager\AssignStudents\Index as AssignStudents;
use App\Livewire\Manager\Blog\Blog\Index;
use App\Livewire\Manager\Blog\ExampleQuestion;
use App\Livewire\Manager\Coupon\Index as CouponIndex;
use App\Livewire\Manager\Dashboard\Analytics;

use App\Livewire\Manager\Advisors\Index as AdvisorIndex;
use App\Livewire\Manager\Dashboard\Crm;
use App\Livewire\Manager\Exam\ExamForm as ExamForm;
use App\Livewire\Manager\Exam\Index as ExamIndex;
use App\Livewire\Manager\Map\Country as MapCountry;
use App\Livewire\Manager\Map\State as MapState;
use App\Livewire\Manager\Map\City as MapCity;
use App\Livewire\Manager\Payment\Index as PaymentIndex;
use App\Livewire\Manager\Category\Index as CategoryIndex;
use App\Livewire\Manager\Category\Feature as CategoryFeature;
use App\Livewire\Manager\ReceivedDocuments\ContactDocuments;
use App\Livewire\Manager\ReceivedDocuments\ReportStudentStudy;
use App\Livewire\Manager\Setting\ContactUs\Index as SettingContactUs;
use App\Livewire\Manager\Setting\Home\TopStudent as SettingHomeTopStudent;
use App\Livewire\Manager\Setting\Home\SchoolSdfr as SettingHomeSchoolSdfr;
use App\Livewire\Manager\Story\Index as StoryIndex;
use App\Livewire\Manager\Student\Index as StudentIndex;
use App\Livewire\Manager\Supports\Supporter as SupportIndex;
use App\Livewire\Manager\Supports\SupporterStudent;
use App\Livewire\Manager\Supports\SupporterStudentDetail;
use App\Livewire\Manager\Task\TaskBoard;
use App\Livewire\Manager\Users\Index as UserIndex;
use App\Livewire\Manager\Users\Detail as UserDetail;
use App\Livewire\Manager\Transaction\Index as TransactionIndex;
use App\Livewire\Manager\Product\index as ProductIndex;
use App\Livewire\Manager\Product\Create as ProductCreate;
use App\Livewire\Manager\Product\content as ProductContent;
use App\Livewire\Manager\Product\CkUpload;
use App\Livewire\Manager\Order\Detail as OrderDetails;
use App\Livewire\Manager\Order\Index as OrderIndex;
use App\Livewire\Manager\Auth\Index as AuthIndex;
use App\Livewire\Manager\Ticket\Department as DepartmentIndex;
use App\Livewire\Manager\Ticket\Index as TicketIndex;
use App\Livewire\Manager\Ticket\Show as TicketShow;

use App\Livewire\Manager\Advisors\AdvisorStudent;
use App\Livewire\Manager\Advisors\AdvisorStudentDetail;
use App\Livewire\Manager\Advisors\AdvisorStudents as AdvisorAssignStudents;

use Illuminate\Support\Facades\Route;


Route::name('manager.')->group(function () {

    Route::get('/sign-in', AuthIndex::class)->name('sign-in');

    Route::middleware(['auth:admin', 'role:super admin'])->group(function () {
        Route::get('/logout', [AuthIndex::class, 'logout'])->name('logout');
        Route::get('/dashboard/crm', Crm::class)->name('dashboard.crm');
        Route::get('/dashboard/analytics', Analytics::class)->name('dashboard.analytics');
        Route::get('/paymentMethod', PaymentIndex::class)->name('paymentMethod');
        Route::get('/coupon', CouponIndex::class)->name('coupon');
        Route::get('/map/country', MapCountry::class)->name('map.country');
        Route::get('/map/state', MapState::class)->name('map.state');
        Route::get('/map/city', MapCity::class)->name('map.city');
        Route::get('/category', CategoryIndex::class)->name('category.index');
        Route::get('/category/{category}/features', CategoryFeature::class)->name('category.features');
        Route::get('/story', StoryIndex::class)->name('story');
        Route::get('/user', UserIndex::class)->name('user');
        Route::get('/user/{id}', UserDetail::class)->name('user.detail');
        Route::get('/transaction', TransactionIndex::class)->name('transaction');
        Route::get('/product', ProductIndex::class)->name('product.index');
        Route::get('/product/create', ProductCreate::class)->name('product.create');
        Route::get('/product/content/{product}', ProductContent::class)->name('product.content');
        Route::post('/ck-upload/{productId}', [CkUpload::class, 'upload'])->name('ck-upload');
        Route::get('/order', OrderIndex::class)->name('order.index');
        Route::get('/order/{order}', OrderDetails::class)->name('order.details');

        Route::get('/admin', AdminManage::class)->name('adminManage');
        Route::get('/studentManager', AssignStudents::class)->name('studentAssign');
        Route::get('/student', StudentIndex::class)->name('student');
        Route::get('/supporter', SupportIndex::class)->name('supporters');
        Route::get('/{supporter}/students', SupporterStudent::class)->name('supporter.student');
        Route::get('/students/{student}/detail', SupporterStudentDetail::class)->name('supporter.students.detail');
        Route::get('/document/contact', ContactDocuments::class)->name('document.contact');
        Route::get('/document/reportStudentStudy', ReportStudentStudy::class)->name('document.reportStudentStudy');
        Route::get('/ticket', TicketIndex::class)->name('ticket.index');
        Route::get('/ticket/{ticket}', TicketShow::class)->name('ticket.show');
        Route::get('/department', DepartmentIndex::class)->name('department');
        Route::get('/blog/example-question', ExampleQuestion::class)->name('blog.exampleQuestion');

        Route::get('/setting/contactUs', SettingContactUs::class)->name('setting.contactUs');
        Route::get('/setting/sdfrStudent', SettingHomeTopStudent::class)->name('setting.topStudent');
        Route::get('/setting/sdfrSchool', SettingHomeSchoolSdfr::class)->name('setting.schoolSdfr');

// مسیر مدیریت آزمون‌ها
        Route::get('/exams', ExamIndex::class)->name('exam.index');
        Route::get('/blog', Index::class)->name('blog.index');
        Route::get('blogs/{blog}/show', \App\Livewire\Manager\Blog\Blog\Show::class)->name('blog.show');

// یک مسیر برای هر دو حالت ایجاد و ویرایش
        Route::get('/exams/form/{exam?}', ExamForm::class)->name('exam.form');

        Route::get('/tasks', TaskBoard::class)->name('task.board');

        Route::get('/advisorManager', AdvisorAssignStudents::class)->name('advisorAssign');
        Route::get('/advisors', AdvisorIndex::class)->name('advisors');
        Route::get('/advisors/{advisor}/students', AdvisorStudent::class)->name('advisors.students');
        Route::get('/advisors/students/{student}/detail', AdvisorStudentDetail::class)->name('advisors.students.detail');

    });

});
