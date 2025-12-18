<?php


namespace App\Livewire\Manager\Notification;


use App\Models\Notification;

use App\Models\NotificationRecipient;

use App\Models\Student;

use App\Models\User;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\DB;

use Livewire\Component;

use Livewire\WithPagination;


class Index extends Component

{

    use WithPagination, SEOTools;


    // فیلدهای فرم

    public $title = '';

    public $body = '';

    public $category = 'announcement';

    public $targetType = 'all_users';


    // جستجو و فیلتر

    public $search = '';


    // مودال نمایش گیرندگان

    public $showRecipientsModal = false;

    public $selectedNotification = null;

    public $recipientsList = [];


    protected $rules = [

        'title' => 'required|string|min:3|max:255',

        'body' => 'required|string|min:10',

        'category' => 'required|in:announcement,special',

        'targetType' => 'required|in:all_users,all_students',

    ];


    protected $messages = [

        'title.required' => 'عنوان پیام الزامی است.',

        'title.min' => 'عنوان پیام باید حداقل ۳ کاراکتر باشد.',

        'title.max' => 'عنوان پیام نباید بیشتر از ۲۵۵ کاراکتر باشد.',

        'body.required' => 'توضیحات پیام الزامی است.',

        'body.min' => 'توضیحات پیام باید حداقل ۱۰ کاراکتر باشد.',

        'category.required' => 'دسته‌بندی پیام الزامی است.',

        'category.in' => 'دسته‌بندی انتخاب شده معتبر نیست.',

        'targetType.required' => 'نوع گیرنده الزامی است.',

        'targetType.in' => 'نوع گیرنده انتخاب شده معتبر نیست.',

    ];


    public function mount()

    {

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('اطلاع‌رسانی');

    }


    /**
     * ارسال پیام جدید
     */

    public function send()

    {

        $this->validate();


        DB::beginTransaction();


        try {

            // ایجاد notification

            $notification = Notification::create([

                'admin_id' => auth('manager')->id(),

                'title' => $this->title,

                'body' => $this->body,

                'category' => $this->category,

                'target_type' => $this->targetType,

                'is_from_manager' => true,

            ]);


            // تعیین گیرندگان

            if ($this->targetType === Notification::TARGET_ALL_USERS) {

                // همه کاربران

                $users = User::all();

            } else {

                // همه دانش‌آموزان

                $users = User::whereHas('student')->get();

            }


            // ایجاد رکوردهای گیرنده

            $recipients = $users->map(function ($user) use ($notification) {

                return [

                    'notification_id' => $notification->id,

                    'user_id' => $user->id,

                    'is_read' => false,

                    'created_at' => now(),

                    'updated_at' => now(),

                ];

            })->toArray();


            NotificationRecipient::insert($recipients);


            DB::commit();


            $this->dispatch('success', 'پیام با موفقیت به ' . count($recipients) . ' نفر ارسال شد.');

            $this->reset(['title', 'body']);

            $this->category = 'announcement';

            $this->targetType = 'all_users';

            $this->resetPage();


        } catch (\Exception $e) {

            DB::rollBack();

            $this->dispatch('error', 'خطا در ارسال پیام: ' . $e->getMessage());

        }

    }


    /**
     * حذف پیام
     */

    public function delete($id)

    {

        $notification = Notification::findOrFail($id);

        $notification->delete();

        $this->dispatch('success', 'پیام با موفقیت حذف شد.');

    }


    /**
     * نمایش لیست گیرندگان
     */

    public function showRecipients($notificationId)

    {

        $this->selectedNotification = Notification::with(['recipients.user.personalInformation'])
            ->findOrFail($notificationId);


        $this->recipientsList = $this->selectedNotification->recipients()
            ->with(['user.personalInformation'])
            ->get();


        $this->showRecipientsModal = true;

    }


    /**
     * بستن مودال گیرندگان
     */

    public function closeRecipientsModal()

    {

        $this->showRecipientsModal = false;

        $this->selectedNotification = null;

        $this->recipientsList = [];

    }


    public function render()

    {

        $notifications = Notification::query()
            ->fromManager()
            ->when($this->search, function ($query) {

                $query->where(function ($q) {

                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('body', 'like', "%{$this->search}%");

                });

            })
            ->withCount([

                'recipients',

                'recipients as read_recipients_count' => function ($query) {

                    $query->where('is_read', true);

                }

            ])
            ->latest()
            ->paginate(10);


        return view('livewire.manager.notification.index', [

            'notifications' => $notifications,

            'categories' => Notification::managerCategories(),

            'targetTypes' => Notification::targetTypes(),

        ])->layout('layouts.manager.app');

    }

}
