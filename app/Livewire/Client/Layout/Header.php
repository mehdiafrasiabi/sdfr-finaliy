<?php

namespace App\Livewire\Client\Layout;

use App\Models\Cart;
use App\Models\Notification;
use App\Models\TrialWeek;
use App\Services\ExamPlanningService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Header extends Component
{
    public $cart = 0;

    public ?string $profilePictureUrl = null;

    public ?string $gender = null;
    public $unreadCount = 0;

    /** اطلاعات مشاور/پشتیبان برای نمایش در مودال موبایل */
    public ?array $advisorInfo = null;
    public bool $showExamPlanningLink = false;
    public bool $showSampleQuestionsLink = false;

    public function mount()
    {
        $this->loadUnreadCount();
        $this->loadUserProfileData();
        $this->loadAdvisorInfo();
        $this->loadExamPlanningState();
        $this->cart = Cart::query()
            ->where('user_id', Auth()->id())->count();
    }

    public function loadUnreadCount()
    {
        $user = Auth::user();
        $student = $user?->student;

        if (! $student) {
            $this->unreadCount = 0;
            return;
        }
        $this->unreadCount = Notification::where('student_id', $student->id)
            ->where('is_read', false)
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

    /**
     * بارگذاری اطلاعات مشاور یا پشتیبان جذب (در حالت آزمایشی)
     * منطق مشابه Dashboard::getDisplayAdvisor()
     */
    public function loadAdvisorInfo(): void
    {
        $user = Auth::user();
        if (! $user) {
            $this->advisorInfo = null;
            return;
        }

        $student = $user->student ?? null;

        // دانش‌آموز اصلی با مشاور
        if ($student && ! $student->is_trial && $student->advisor) {
            $advisor = $student->advisor;
            $this->advisorInfo = [
                'id'      => $advisor->id,
                'name'    => $advisor->name,
                'mobile'  => $advisor->mobile ?? null,
                'picture' => $advisor->picture
                    ? asset('adminsFile/' . $advisor->id . '/' . $advisor->picture)
                    : null,
                'label'   => 'مشاور شما',
            ];
            return;
        }

        // حالت آزمایشی → پشتیبان جذب
        $trial = TrialWeek::where('user_id', $user->id)
            ->whereNotNull('acquisition_supporter_id')
            ->latest()->first();
        if ($trial && $trial->acquisitionSupporter) {
            $sup = $trial->acquisitionSupporter;
            $this->advisorInfo = [
                'id'      => $sup->id,
                'name'    => $sup->name,
                'mobile'  => $sup->mobile ?? null,
                'picture' => $sup->picture
                    ? asset('adminsFile/' . $sup->id . '/' . $sup->picture)
                    : null,
                'label'   => 'پشتیبان شما',
            ];
            return;
        }

        $this->advisorInfo = null;
    }

    public function loadExamPlanningState(): void
    {
        $user = Auth::user();
        if (! $user) {
        $this->showExamPlanningLink = false;
        $this->showSampleQuestionsLink = false;
        return;
    }

        $this->showExamPlanningLink = app(ExamPlanningService::class)->shouldExposePaidModule($user)
            || app(ExamPlanningService::class)->shouldExposeTrialModule($user);
        $this->showSampleQuestionsLink = app(ExamPlanningService::class)->shouldExposePaidModule($user)
            || app(ExamPlanningService::class)->shouldExposeTrialModule($user);
    }

    protected function resolveUserPictureUrl($user): ?string
    {
        // اولویت: عکسِ کاربر؛ سپس آواتارِ انتخابیِ پروفایل (B1).
        $raw = $user?->picture ?: $user?->profile?->picture;

        if (! $raw) {
            return null;
        }

        $picture = ltrim($raw, '/');
        if (file_exists(public_path($picture))) {
            return asset($picture);
        }

        $legacyPicture = $user?->picture ?: $user?->profile?->picture;
        $legacyPath = "user/img/{$user->id}/{$legacyPicture}";
        if (file_exists(public_path($legacyPath))) {
            return asset($legacyPath);
        }

        return null;
    }

    public function getDefaultAvatarTypeProperty(): string
    {
        return $this->gender === 'female' ? 'female' : 'male';
    }

    #[On('add-to-cart')]
    public function getUserCart()
    {
        $this->cart = $this->cart + 1;
    }

    #[On('remove-from-cart')]
    public function removeUserCart($newCount)
    {
        $this->cart = $newCount;
    }

    #[On('notificationAdded')]
    #[On('notificationRead')]
    public function refreshUnreadCount()
    {
        $this->loadUnreadCount();
    }

    public function render()
    {
        return view('livewire.client.layout.header')->layout('layouts.client.app');
    }
}
