<?php

namespace App\Livewire\Admin\EducationalManager\AdvisorOnboardingApprovals;

use App\Models\AdvisorOnboarding;
use App\Services\NotificationService;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, SEOTools;

    public array $rejectReason = [];

    public function mount(): void
    {
        $this->seo()->setTitle('تایید لینک گروه بله');
    }

    public function approve(int $onboardingId): void
    {
        $onboarding = AdvisorOnboarding::with(['student', 'advisor'])
            ->where('status', AdvisorOnboarding::STATUS_PENDING_REVIEW)
            ->find($onboardingId);

        if (! $onboarding) {
            $this->dispatch('warning', 'این درخواست دیگر معتبر نیست.');
            return;
        }

        $onboarding->update([
            'status'        => AdvisorOnboarding::STATUS_APPROVED,
            'reviewed_by'   => auth('admin')->id(),
            'reviewed_at'   => now(),
            'reject_reason' => null,
        ]);

        NotificationService::sendToStudent(
            $onboarding->student_id,
            'تایید گروه بله',
            'لینک گروه بله شما تایید شد. از این مرحله به بعد مشاور زمان جلسه‌ها و هماهنگی‌های بعدی را اعلام می‌کند.',
            $onboarding->advisor_id
        );

        $this->dispatch('success', 'لینک گروه بله تایید شد و جریان مشاوره برای دانش‌آموز باز شد.');
    }

    public function reject(int $onboardingId): void
    {
        $onboarding = AdvisorOnboarding::where('status', AdvisorOnboarding::STATUS_PENDING_REVIEW)
            ->find($onboardingId);

        if (! $onboarding) {
            $this->dispatch('warning', 'این درخواست دیگر معتبر نیست.');
            return;
        }

        $onboarding->update([
            'status'        => AdvisorOnboarding::STATUS_REJECTED,
            'reviewed_by'   => auth('admin')->id(),
            'reviewed_at'   => now(),
            'reject_reason' => $this->rejectReason[$onboardingId] ?? 'لینک گروه بله نیاز به اصلاح دارد.',
        ]);

        unset($this->rejectReason[$onboardingId]);
        $this->dispatch('success', 'لینک رد شد. مشاور فقط می‌تواند لینک را ویرایش و دوباره ارسال کند.');
    }

    public function render()
    {
        $pending = AdvisorOnboarding::where('status', AdvisorOnboarding::STATUS_PENDING_REVIEW)
            ->with(['student.user.personalInformation', 'advisor', 'call'])
            ->latest('submitted_at')
            ->get();

        $history = AdvisorOnboarding::whereIn('status', [
                AdvisorOnboarding::STATUS_APPROVED,
                AdvisorOnboarding::STATUS_REJECTED,
            ])
            ->with(['student.user.personalInformation', 'advisor', 'reviewer'])
            ->latest('reviewed_at')
            ->paginate(15);

        return view('livewire.admin.educational-manager.advisor-onboarding-approvals.index', [
            'pending' => $pending,
            'history' => $history,
        ])->layout('layouts.admin.app');
    }
}
