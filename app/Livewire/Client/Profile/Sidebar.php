<?php

namespace App\Livewire\Client\Profile;

use App\Models\NotificationRecipient;
use App\Services\ExamPlanningService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Sidebar extends Component
{
    public ?string $profilePictureUrl = null;
    public ?string $gender = null;
    public $unreadCount = 0;
    public int $advisorUnread = 0;
    public bool $advisorChatLocked = false;
    public bool $showExamPlanningLink = false;
    public bool $hideForExamProgramTrialStudent = false;

    public function mount()
    {
        $this->loadUnreadCount();
        $this->loadAdvisorChatState();
        $this->loadUserProfileData();
        $this->hideForExamProgramTrialStudent = Auth::user()
            ? app(ExamPlanningService::class)->shouldHideTrialExamProgramSections(Auth::user())
            : false;
        $this->showExamPlanningLink = Auth::user()
            ? (app(ExamPlanningService::class)->shouldExposePaidModule(Auth::user())
                || app(ExamPlanningService::class)->shouldExposeTrialModule(Auth::user()))
            : false;
    }

    /**
     * وضعیت چت با مشاور: قفلِ هفته‌ی آزمایشی + تعداد پیام نخوانده‌ی مشاور.
     */
    public function loadAdvisorChatState(): void
    {
        $user = Auth::user();
        $student = $user?->student;

        if (! $student) {
            $this->advisorChatLocked = false;
            $this->advisorUnread = 0;
            return;
        }

        $trial = $student->trialWeek;
        $this->advisorChatLocked = $trial && ! $trial->hasFullAccess();

        $conversation = $student->conversation;
        $this->advisorUnread = $conversation ? $conversation->unreadCountFor('student') : 0;
    }
    public function loadUnreadCount()
    {
        $user = Auth::user();

        if (! $user) {
            $this->unreadCount = 0;
            return;
        }

        $this->unreadCount = NotificationRecipient::where('user_id', $user->id)
            ->where('is_read', false)
            ->where('created_at', '>=', now()->subDays(5)) // فقط پیام‌های 5 روز اخیر
            ->whereHas('notification', fn($q) => $q->where('category', '!=', \App\Models\Notification::CATEGORY_SPECIAL))
            ->count();
    }

    public function loadUserProfileData(): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->profilePictureUrl = null;
            $this->gender = null;
            return;
        }

        $this->gender = $user->profile?->gender;
        $this->profilePictureUrl = $this->resolveUserPictureUrl($user);
    }
    protected function resolveUserPictureUrl($user): ?string
    {
        $raw = $user?->picture ?: $user?->profile?->picture;

        if (! $raw) {
            return null;
        }

        $picture = ltrim($raw, '/');
        if (file_exists(public_path($picture))) {
            return asset($picture);
        }
        $legacyPath = "user/img/{$user->id}/{$raw}";
        if (file_exists(public_path($legacyPath))) {
            return asset($legacyPath);
        }

        return null;
    }

    public function getDefaultAvatarTypeProperty(): string
    {
        return $this->gender === 'female' ? 'female' : 'male';
    }

    #[On('notificationAdded')]
    public function refreshUnreadCount()
    {
        $this->loadUnreadCount();
    }

    public function render()
    {
        return view('livewire.client.profile.sidebar')->layout('layouts.client.app');
    }
}
