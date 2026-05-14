<?php
use App\Livewire\Manager\AdminManage\Index as AdminManage;
use App\Livewire\Manager\TrialWeek\Index as TrialWeekIndex;
use App\Livewire\Manager\TrialWeek\Detail as TrialWeekDetail;
use App\Livewire\Manager\AssignStudents\Index as AssignStudents;
use App\Livewire\Manager\Blog\Blog\Index;
use App\Livewire\Manager\Blog\ExampleQuestion;
use App\Livewire\Manager\Dashboard\Analytics;
use App\Livewire\Manager\Exam\QuestionManager;
use App\Livewire\Manager\Dashboard\Crm;
use App\Livewire\Manager\Exam\ExamForm as ExamForm;
use App\Livewire\Manager\Exam\Index as ExamIndex;
use App\Livewire\Manager\ExamPeriods\ExamPeriodIndex;
use App\Livewire\Manager\Map\Country as MapCountry;
use App\Livewire\Manager\Map\State as MapState;
use App\Livewire\Manager\Map\City as MapCity;
use App\Livewire\Manager\Payment\Index as PaymentIndex;
use App\Livewire\Manager\Category\Index as CategoryIndex;
use App\Livewire\Manager\Category\Feature as CategoryFeature;
use App\Livewire\Manager\Questions\CkUpload as QuestionCkUpload;
use App\Livewire\Manager\Questions\QuestionForm;
use App\Livewire\Manager\Questions\QuestionList;
use App\Livewire\Manager\ReceivedDocuments\ContactDocuments;
use App\Livewire\Manager\ReceivedDocuments\ReportStudentStudy;
use App\Livewire\Manager\Setting\ContactUs\Index as SettingContactUs;
use App\Livewire\Manager\Setting\Home\TopStudent as SettingHomeTopStudent;
use App\Livewire\Manager\Setting\Home\SchoolSdfr as SettingHomeSchoolSdfr;
use App\Livewire\Manager\Story\Index as StoryIndex;
use App\Livewire\Manager\Story\Create as StoryCreate;
use App\Livewire\Manager\Story\Edit as StoryEdit;
use App\Livewire\Manager\Student\Index as StudentIndex;
use App\Livewire\Manager\Supports\Supporter as SupportIndex;
use App\Livewire\Manager\Supports\SupporterStudent;
use App\Livewire\Manager\Supports\SupporterStudentDetail;
use App\Livewire\Manager\Task\TaskBoard;
use App\Livewire\Manager\TypedExam\TypedExamList;
use App\Livewire\Manager\TypedExam\TypedExamWizard;
use App\Livewire\Manager\Users\Index as UserIndex;
use App\Livewire\Manager\Users\Detail as UserDetail;
use App\Livewire\Manager\Transaction\Index as TransactionIndex;
use App\Livewire\Manager\Auth\Index as AuthIndex;
use App\Livewire\Manager\Ticket\Department as DepartmentIndex;
use App\Livewire\Manager\Ticket\Index as TicketIndex;
use App\Livewire\Manager\Ticket\Show as TicketShow;
use App\Livewire\Manager\Newsletter\Index as NewsletterIndex;
use App\Livewire\Manager\Classification\Chapters;
use App\Livewire\Manager\Classification\EducationLevels;
use App\Livewire\Manager\Classification\Fields;
use App\Livewire\Manager\Classification\Grades;
use App\Livewire\Manager\Classification\Projects;
use App\Livewire\Manager\Classification\Subjects;
use App\Livewire\Manager\Classification\Topics;
use App\Livewire\Manager\Notification\Index as NotificationIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Manager\GiftCode\Index as GiftCodeIndex;

use App\Livewire\Manager\Setting\PercentCalculator as SettingPercentCalculator;
use App\Livewire\Manager\Setting\PercentCalculatorCkUpload;
use App\Livewire\Manager\Setting\General as SettingGeneral;
use App\Livewire\Manager\Comment\Index as CommentIndex;
use App\Livewire\Manager\Setting\ExamCountdown as SettingExamCountdown;
use App\Livewire\Manager\Setting\ExamCountdownCkUpload;
Route::name('manager.')->group(function () {

    Route::get('/sign-in', AuthIndex::class)->name('sign-in')->middleware('guest:manager');

    Route::middleware(['auth:manager', 'role:super admin'])->group(function () {
        Route::get('/logout', [AuthIndex::class, 'logout'])->name('logout');
        Route::get('/dashboard/crm', Crm::class)->name('dashboard.crm');
        Route::get('/dashboard/analytics', Analytics::class)->name('dashboard.analytics');
        Route::get('/gift-code', GiftCodeIndex::class)->name('giftcode');


        Route::get('/paymentMethod', PaymentIndex::class)->name('paymentMethod');
        Route::get('/map/country', MapCountry::class)->name('map.country');
        Route::get('/map/state', MapState::class)->name('map.state');
        Route::get('/map/city', MapCity::class)->name('map.city');
        Route::get('/category', CategoryIndex::class)->name('category.index');
        Route::get('/category/{category}/features', CategoryFeature::class)->name('category.features');
        Route::get('/story', StoryIndex::class)->name('story');
        Route::get('/story/create', StoryCreate::class)->name('story.create');
        Route::get('/story/{story}/edit', StoryEdit::class)->name('story.edit');
        Route::get('/user', UserIndex::class)->name('user');
        Route::get('/user/{id}', UserDetail::class)->name('user.detail');
        Route::get('/transaction', TransactionIndex::class)->name('transaction');
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

        Route::get('/setting/general', SettingGeneral::class)->name('setting.general');
        Route::get('/setting/contactUs', SettingContactUs::class)->name('setting.contactUs');
        Route::get('/setting/sdfrStudent', SettingHomeTopStudent::class)->name('setting.topStudent');
        Route::get('/setting/sdfrSchool', SettingHomeSchoolSdfr::class)->name('setting.schoolSdfr');
        Route::get('/setting/examCountdown', SettingExamCountdown::class)->name('setting.examCountdown');
        Route::post('/setting/exam-countdown/ck-upload', [ExamCountdownCkUpload::class, 'upload'])->name('setting.exam-countdown.ck-upload');
        Route::get('/setting/percent-calculator', SettingPercentCalculator::class)->name('setting.percentCalculator');
        Route::post('/setting/percent-calculator/ck-upload', [PercentCalculatorCkUpload::class, 'upload'])->name('setting.percent-calculator.ck-upload');

// مسیر مدیریت آزمون‌ها
        Route::get('/exams', ExamIndex::class)->name('exam.index');
        Route::get('/exams/questions', QuestionManager::class)->name('exam.questions');
        Route::get('/setting/examCountdown', SettingExamCountdown::class)->name('setting.examCountdown');
        Route::post('/setting/exam-countdown/ck-upload', [ExamCountdownCkUpload::class, 'upload'])->name('setting.exam-countdown.ck-upload');
// Comment Management (مدیریت دیدگاه‌ها)

        Route::get('/comments', CommentIndex::class)->name('comment.index');

        Route::get('/blog', Index::class)->name('blog.index');
        Route::get('blogs/{blog}/show', \App\Livewire\Manager\Blog\Blog\Show::class)->name('blog.show');

// یک مسیر برای هر دو حالت ایجاد و ویرایش

        // Notification Routes (اطلاع‌رسانی)

        Route::get('/notification', NotificationIndex::class)->name('notification');

        Route::get('/exams/form/{exam?}', ExamForm::class)->name('exam.form');

        Route::get('/tasks', TaskBoard::class)->name('task.board');

        Route::get('/newsletter', NewsletterIndex::class)->name('newsletter');
        // Question Bank Routes (بانک سوالات)
        Route::get('/questions', QuestionList::class)->name('questions.index');
        Route::get('/questions/form/{code?}', QuestionForm::class)->name('questions.form');
        Route::post('/questions/ck-upload/{questionId?}', [QuestionCkUpload::class, 'upload'])->name('questions.ck-upload');
        // Typed Exam Routes (آزمون‌های تایپی)
        Route::get('/typed-exams', TypedExamList::class)->name('typed-exams.index');
        Route::get('/typed-exams/form/{id?}', TypedExamWizard::class)->name('typed-exams.form');
        Route::get('/academic-year', ExamPeriodIndex::class)->name('academicYear');
        // قیمت‌گذاری بر اساس پایه تحصیلی
        Route::get('/grade-prices', \App\Livewire\Manager\GradePrice\Index::class)
            ->name('grade-price.index');

        // Trial Week Routes (هفته آزمایشی)
        Route::prefix('trial-week')->name('trial-week.')->group(function () {
            Route::get('/', TrialWeekIndex::class)->name('index');
            Route::get('/{id}', TrialWeekDetail::class)->name('detail');
        });

        // Classification Routes
        Route::prefix('classification')->name('classification.')->group(function () {
            Route::get('/education-levels', EducationLevels::class)->name('education-levels');
            Route::get('/education-levels/{educationLevel}/grades', Grades::class)->name('grades');
            Route::get('/fields', Fields::class)->name('fields');
            Route::get('/education-levels/{educationLevel}/grades/{grade}/subjects', Subjects::class)->name('subjects');
            Route::get('/subjects/{subject}/chapters', Chapters::class)->name('chapters');
            Route::get('/chapters/{chapter}/topics', Topics::class)->name('topics');
            Route::get('/projects', Projects::class)->name('projects');
        });
    });
});
