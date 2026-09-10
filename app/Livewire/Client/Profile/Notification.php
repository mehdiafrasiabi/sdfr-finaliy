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
    public $isStudent    = false;
    public $activeCategory = 'all';
    public $unreadCounts = [];

    public function mount()
    {
        $this->seoConfig();
        $this->user      = Auth::user();
        $this->student   = $this->user->student ?? null;
        $this->isStudent = $this->student !== null;

        $this->loadUnreadCounts();

        // ─── تب پیش‌فرض: دسته‌بندی با بیشترین پیام خوانده‌نشده ───
        // اگر هیچ پیام خوانده‌نشده‌ای نداشت، 'all' بماند
        $this->setDefaultActiveCategory();
    }

    public function seoConfig(): void
    {
        $this->seo()->setTitle('پیام‌ها (اطلاع‌رسانی)');
    }

    public function updatingActiveCategory(): void
    {
        $this->resetPage();
    }

    /**
     * تنظیم تب پیش‌فرض:
     * اگر فقط یک دسته‌بندی پیام خوانده‌نشده دارد → آن دسته
     * اگر چند دسته دارند → دسته‌ای که بیشترین تعداد دارد
     * اگر هیچ پیام خوانده‌نشده‌ای نیست → 'all'
     *
     * نکته‌ی کارایی: این متد همیشه بعد از loadUnreadCounts() در mount() صدا زده می‌شود؛
     * تعداد خوانده‌نشده‌ی هر دسته را از $this->unreadCounts (که همان‌جا محاسبه شده)
     * می‌خوانیم به‌جای این‌که دقیقاً همان کوئری‌های شمارشِ هر دسته را دوباره بزنیم.
     * نتیجه‌ی نهایی (این‌که کدام دسته پیش‌فرض انتخاب شود) دقیقاً یکسان می‌ماند.
     */
    protected function setDefaultActiveCategory(): void
    {
        $categories = array_keys($this->getAvailableCategories());

        $counts = [];
        foreach ($categories as $cat) {
            $count = $this->unreadCounts[$cat] ?? 0;
            if ($count > 0) {
                $counts[$cat] = $count;
            }
        }

        if (empty($counts)) {
            // همه خوانده شده — نمایش همه
            $this->activeCategory = 'all';
            return;
        }

        // دسته‌ای که بیشترین پیام خوانده‌نشده دارد
        arsort($counts);
        $this->activeCategory = array_key_first($counts);
    }

    public function loadUnreadCounts(): void
    {
        $userId = $this->user->id;

        $this->unreadCounts['all'] = NotificationRecipient::where('user_id', $userId)
            ->where('is_read', false)
            ->whereHas('notification', fn($q) =>
            $q->whereIn('category', array_keys($this->getAvailableCategories()))
            )
            ->count();

        foreach (array_keys($this->getAvailableCategories()) as $category) {
            $this->unreadCounts[$category] = NotificationRecipient::where('user_id', $userId)
                ->where('is_read', false)
                ->whereHas('notification', fn($q) => $q->where('category', $category))
                ->count();
        }
    }

    public function getAvailableCategories(): array
    {
        if ($this->isStudent) {
            return [
                ModelsNotification::CATEGORY_ANNOUNCEMENT => 'اعلانات',
                ModelsNotification::CATEGORY_ADVISOR       => 'پیام مشاور',
            ];
        }

        return [
            ModelsNotification::CATEGORY_ANNOUNCEMENT => 'اعلانات',
        ];
    }

    public function setCategory($category): void
    {
        $this->activeCategory = $category;
    }

    public function markAsRead($recipientId): void
    {
        $recipient = NotificationRecipient::where('id', $recipientId)
            ->where('user_id', $this->user->id)
            ->first();

        if ($recipient) {
            $recipient->update(['is_read' => true, 'read_at' => now()]);
            $this->loadUnreadCounts();
            $this->dispatch('notification-read');
            $this->dispatch('success', 'پیام با موفقیت خوانده شد.');
        }
    }

    public function render()
    {
        $userId = $this->user->id;

        $query = NotificationRecipient::where('user_id', $userId)
            ->with(['notification.admin'])
            ->whereHas('notification', fn($q) =>
            $q->whereIn('category', array_keys($this->getAvailableCategories()))
            );

        if ($this->activeCategory !== 'all') {
            $query->whereHas('notification', fn($q) =>
            $q->where('category', $this->activeCategory)
            );
        }

        $notifications = $query->latest()->paginate(10);

        return view('livewire.client.profile.notification', [
            'notifications' => $notifications,
            'categories'    => $this->getAvailableCategories(),
            'unreadCounts'  => $this->unreadCounts,
        ])->layout('layouts.client.app');
    }
}
