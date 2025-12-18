<?php



namespace App\Livewire\Client\Profile;



use App\Models\Notification as ModelsNotification;

use App\Models\NotificationRecipient;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;

use Livewire\WithPagination;



class Notification extends Component

{

    use SEOTools, WithPagination;



    public $student;

    public $user;

    public $isStudent = false;



    // دسته‌بندی فعال

    public $activeCategory = 'all';



    // تعداد پیام‌های خوانده نشده در هر دسته‌بندی

    public $unreadCounts = [];



    public function mount()

    {

        $this->seoConfig();

        $this->user = Auth::user();

        $this->student = $this->user->student ?? null;

        $this->isStudent = $this->student !== null;

        $this->loadUnreadCounts();

    }



    public function seoConfig()

    {

        $this->seo()->setTitle('پیام‌ها (اطلاع‌رسانی)');

    }



    /**

     * بارگذاری تعداد پیام‌های خوانده نشده در هر دسته‌بندی

     */

    public function loadUnreadCounts()

    {

        $userId = $this->user->id;



        // تعداد کل پیام‌های خوانده نشده

        $this->unreadCounts['all'] = NotificationRecipient::where('user_id', $userId)

            ->where('is_read', false)

            ->count();



        // دسته‌بندی‌های قابل نمایش

        $categories = $this->getAvailableCategories();



        foreach (array_keys($categories) as $category) {

            $this->unreadCounts[$category] = NotificationRecipient::where('user_id', $userId)

                ->where('is_read', false)

                ->whereHas('notification', function ($query) use ($category) {

                    $query->where('category', $category);

                })

                ->count();

        }

    }



    /**

     * دسته‌بندی‌های قابل نمایش برای این کاربر

     */

    public function getAvailableCategories(): array

    {

        if ($this->isStudent) {

            // برای دانش‌آموزان

            $student = $this->student;



            // بررسی اینکه آیا مشاور و پشتیبان یکی هستند

            if ($student->advisor_id && $student->supporter_id && $student->advisor_id == $student->supporter_id) {

                // اگر مشاور و پشتیبان یکی است، فقط "پیام SDFR" نمایش داده شود

                return [

                    ModelsNotification::CATEGORY_ANNOUNCEMENT => 'اعلانات',

                    ModelsNotification::CATEGORY_SPECIAL => 'اعلان ویژه',

                    'sdfr' => 'پیام SDFR', // ترکیب مشاور و پشتیبان

                ];

            }



            return [

                ModelsNotification::CATEGORY_ANNOUNCEMENT => 'اعلانات',

                ModelsNotification::CATEGORY_SPECIAL => 'اعلان ویژه',

                ModelsNotification::CATEGORY_ADVISOR => 'مشاور',

                ModelsNotification::CATEGORY_SUPPORTER => 'پشتیبان',

            ];

        }



        // برای کاربران عادی

        return [

            ModelsNotification::CATEGORY_ANNOUNCEMENT => 'اعلانات',

            ModelsNotification::CATEGORY_SPECIAL => 'اعلان ویژه',

        ];

    }



    /**

     * تغییر دسته‌بندی فعال

     */

    public function setCategory($category)

    {

        $this->activeCategory = $category;

        $this->resetPage();

    }



    /**

     * علامت‌گذاری به عنوان خوانده شده

     */

    public function markAsRead($recipientId)

    {

        $recipient = NotificationRecipient::where('id', $recipientId)

            ->where('user_id', $this->user->id)

            ->first();



        if ($recipient) {

            $recipient->update([

                'is_read' => true,

                'read_at' => now(),

            ]);



            $this->loadUnreadCounts();

            $this->dispatch('success', 'پیام با موفقیت خوانده شد.');

        }

    }



    public function render()

    {

        $userId = $this->user->id;



        $query = NotificationRecipient::where('user_id', $userId)

            ->with(['notification.admin'])

            ->whereHas('notification');



        // فیلتر بر اساس دسته‌بندی

        if ($this->activeCategory !== 'all') {

            if ($this->activeCategory === 'sdfr') {

                // پیام‌های SDFR (ترکیب مشاور و پشتیبان)

                $query->whereHas('notification', function ($q) {

                    $q->whereIn('category', [

                        ModelsNotification::CATEGORY_ADVISOR,

                        ModelsNotification::CATEGORY_SUPPORTER

                    ]);

                });

            } else {

                $query->whereHas('notification', function ($q) {

                    $q->where('category', $this->activeCategory);

                });

            }

        } else {

            // اگر کاربر عادی است، فقط اعلانات و اعلان ویژه را نمایش بده

            if (!$this->isStudent) {

                $query->whereHas('notification', function ($q) {

                    $q->whereIn('category', [

                        ModelsNotification::CATEGORY_ANNOUNCEMENT,

                        ModelsNotification::CATEGORY_SPECIAL

                    ]);

                });

            }

        }



        $notifications = $query->latest()->paginate(10);



        return view('livewire.client.profile.notification', [

            'notifications' => $notifications,

            'categories' => $this->getAvailableCategories(),

            'unreadCounts' => $this->unreadCounts,

        ])->layout('layouts.client.app');

    }

}
