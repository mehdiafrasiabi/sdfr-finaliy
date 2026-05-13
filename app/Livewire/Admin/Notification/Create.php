<?php


namespace App\Livewire\Admin\Notification;


use App\Models\Notification;

use App\Models\NotificationRecipient;

use App\Models\Student;

use Illuminate\Support\Facades\DB;

use Livewire\Component;

use Livewire\WithPagination;


class Create extends Component

{

    use WithPagination;



    public $title;

    public $body;

    public $studentId = null;

    public $sendType = 'all'; // 'all' یا 'single'

    public $search = '';


    // مودال نمایش گیرندگان

    public $showRecipientsModal = false;

    public $selectedNotification = null;

    public $recipientsList = [];


    protected $rules = [

        'title' => 'required|string|min:3|max:255',

        'body' => 'required|string|min:5',

        'sendType' => 'required|in:all,single',

        'studentId' => 'nullable|exists:students,id',

    ];


    protected $messages = [

        'title.required' => 'عنوان اعلان الزامی است.',

        'title.min' => 'عنوان اعلان باید حداقل ۳ کاراکتر باشد.',

        'title.max' => 'عنوان اعلان نباید بیشتر از ۲۵۵ کاراکتر باشد.',

        'body.required' => 'متن اعلان الزامی است.',

        'body.min' => 'متن اعلان باید حداقل ۵ کاراکتر باشد.',

        'studentId.exists' => 'شناسه دانش‌آموز نامعتبر است.',

    ];



    /**
     * بارگذاری دانش‌آموزان مربوط به این مشاور/پشتیبان
     */

    private function getStudents()

    {

        $adminId = auth('admin')->id();

        return Student::query()
            ->where('advisor_id', $adminId)
            ->with(['user.personalInformation'])
            ->get();

    }


    /**
     * تشخیص اینکه آیا admin مشاور است یا پشتیبان برای این دانش‌آموز
     */

    private function getRelationType(Student $student): string

    {

        $adminId = auth('admin')->id();


        return Notification::CATEGORY_SUPPORTER;

    }


    /**
     * ارسال اعلان
     */

    public function send()

    {

        // اعتبارسنجی اضافی برای حالت تکی

        if ($this->sendType === 'single' && empty($this->studentId)) {

            $this->addError('studentId', 'لطفاً یک دانش‌آموز انتخاب کنید.');

            return;

        }


        $this->validate();


        DB::beginTransaction();


        try {

            if ($this->sendType === 'single') {

                // ارسال به یک دانش‌آموز

                $student = Student::findOrFail($this->studentId);

                $category = $this->getRelationType($student);


                $notification = Notification::create([

                    'admin_id' => auth('admin')->id(),

                    'student_id' => $student->id,

                    'title' => $this->title,

                    'body' => $this->body,

                    'category' => $category === 'sdfr' ? Notification::CATEGORY_SUPPORTER : $category,

                    'target_type' => Notification::TARGET_SINGLE,

                    'is_from_manager' => false,

                ]);


                // ایجاد رکورد گیرنده

                NotificationRecipient::create([

                    'notification_id' => $notification->id,

                    'user_id' => $student->user_id,

                    'is_read' => false,

                ]);


                $this->dispatch('success', 'اعلان با موفقیت به دانش‌آموز ارسال شد.');


            } else {

                // ارسال به همه دانش‌آموزان

                $students = $this->getStudents();
                $sentCount = $students->count();


                if ($sentCount === 0) {

                    $this->dispatch('warning', 'هیچ دانش‌آموزی یافت نشد.');


                    DB::rollBack();
                    return;
                    }

                $firstStudent = $students->first();
                $category = $this->getRelationType($firstStudent);
                if ($category === 'sdfr') {  $category = Notification::CATEGORY_SUPPORTER;

                }

                // یک رکورد اعلان برای همه دانش‌آموزان

                $notification = Notification::create([

                    'admin_id' => auth('admin')->id(),

                    'student_id' => null,

                    'title' => $this->title,

                    'body' => $this->body,

                    'category' => $category,

                    'target_type' => Notification::TARGET_ALL_STUDENTS,

                    'is_from_manager' => false,

                ]);


                // ایجاد رکورد گیرنده برای هر دانش‌آموز

                foreach ($students as $student) {



                    NotificationRecipient::create([

                        'notification_id' => $notification->id,

                        'user_id' => $student->user_id,

                        'is_read' => false,

                    ]);



                }


                $this->dispatch('success', "اعلان با موفقیت به {$sentCount} دانش‌آموز ارسال شد.");

            }


            DB::commit();

            $this->resetPage();

            $this->reset(['title', 'body', 'studentId']);

            $this->sendType = 'all';


        } catch (\Exception $e) {

            DB::rollBack();

            $this->dispatch('error', 'خطا در ارسال اعلان: ' . $e->getMessage());

        }

    }


    /**
     * حذف اعلان
     */

    public function deleteNotification($id)

    {

        Notification::findOrFail($id)->delete();

        $this->dispatch('success', 'اعلان با موفقیت حذف شد.');

    }


    /**
     * نمایش لیست گیرندگان برای پیام‌های گروهی
     */

    public function showRecipients($notificationId)

    {

        $notification = Notification::where('admin_id', auth('admin')->id())
            ->findOrFail($notificationId);


        // پیدا کردن همه پیام‌های مرتبط (با همان عنوان و متن و زمان مشابه)

        $this->selectedNotification = $notification;
        $this->recipientsList = NotificationRecipient::where('notification_id', $notification->id)
            ->with('user.personalInformation')
            ->get();



        $this->showRecipientsModal = true;

    }


    /**
     * بستن مودال
     */

    public function closeRecipientsModal()

    {

        $this->showRecipientsModal = false;

        $this->selectedNotification = null;

        $this->recipientsList = [];

    }


    public function render()

    {

        $adminId = auth('admin')->id();
        $students = $this->getStudents();

        $notifications = Notification::query()
            ->where('admin_id', $adminId)
            ->when($this->search, function ($query) {

                $query->where(function ($q) {

                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('body', 'like', "%{$this->search}%");

                });

            })
            ->with(['student.user.personalInformation', 'recipients'])
            ->latest()
            ->paginate(10);


        return view('livewire.admin.notification.create', [

            'notifications' => $notifications,
            'students' => $students,
        ])->layout('layouts.admin.app');

    }

}
