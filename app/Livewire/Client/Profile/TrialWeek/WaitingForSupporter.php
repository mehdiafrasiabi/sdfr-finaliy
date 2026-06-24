<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * صفحهٔ تخصیص خودکار «مشاور جذب»:
 *  - سیستم از بین ادمین‌های دارای نقش «مشاور جذب»، کسی که کمترین
 *    دانش‌آموز دارد را انتخاب می‌کند.
 *  - یک تایمر ۲۰ ثانیه‌ای نمایش داده می‌شود؛ بعد از رد شدن از ثانیهٔ ۱۲،
 *    باکس مشاور جذب (نام، موبایل، عکس) ظاهر می‌شود و در پایان تایمر
 *    کاربر وارد راهنمای هفتهٔ آزمایشی می‌شود.
 */
class WaitingForSupporter extends Component
{
    use SEOTools;

    public ?TrialWeek $trialWeek = null;

    /** کل زمان تایمر (ثانیه) */
    public const TIMER_SECONDS = 20;

    /** از این ثانیه به پایین، باکس مشاور جذب نمایش داده می‌شود. */
    public const REVEAL_AT_SECOND = 12;

    public ?string $consultantName   = null;
    public ?string $consultantMobile = null;
    public ?string $consultantAvatar = null;

    public function mount(TrialWeekService $service): void
    {
        $this->seo()->setTitle('انتخاب مشاور');
        $this->trialWeek = TrialWeek::where('user_id', Auth::id())->latest()->first();

        if (!$this->trialWeek) {
            redirect()->route('client.profile.dashboard');
            return;
        }

        // تخصیص خودکار مشاور جذب (اگر هنوز انجام نشده باشد).
        if ($this->trialWeek->status === TrialWeek::STATUS_PENDING) {
            $service->autoAssignAcquisitionConsultant($this->trialWeek);
            $this->trialWeek->refresh();
        } elseif ($this->trialWeek->status !== TrialWeek::STATUS_SUPPORTER_ASSIGNED) {
            // مراحل بعدی — این صفحه دیگر معنا ندارد.
            redirect()->route('client.profile.trial.guide');
            return;
        }

        $consultant = $this->trialWeek->acquisitionSupporter;
        if ($consultant) {
            $this->consultantName   = $consultant->name;
            $this->consultantMobile = $consultant->mobile;
            $this->consultantAvatar = $consultant->picture
                ? asset('adminsFile/' . $consultant->id . '/' . $consultant->picture)
                : null;
        }
    }

    /**
     * پایان تایمر — ورود به راهنمای هفتهٔ آزمایشی.
     */
    public function finish(): void
    {
        $this->redirect(route('client.profile.trial.guide'), navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.profile.trial-week.waiting-for-supporter')
            ->layout('layouts.client.app');
    }
}
