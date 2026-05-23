<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\TrialWeek;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WaitingForSupporter extends Component
{
    use SEOTools;

    public ?TrialWeek $trialWeek = null;

    /** ساعت شروع کار مجموعه (۲۴ ساعته) */
    public const WORK_START_HOUR = 9;

    /** ساعت پایان کار مجموعه (۲۴ ساعته) */
    public const WORK_END_HOUR = 21;

    /** آیا الان داخل ساعت کاری هستیم؟ */
    public bool $isWithinWorkingHours = true;

    /** پیام وضعیت ساعت کاری برای نمایش */
    public string $workingHoursMessage = '';

    /** ساعت تقریبی شروع رسیدگی (متن آماده برای نمایش) */
    public string $nextActiveTime = '';

    public function mount(): void
    {
        $this->seo()->setTitle('در انتظار پشتیبان');
        $this->trialWeek = TrialWeek::where('user_id', Auth::id())->latest()->first();

        if (!$this->trialWeek) {
            redirect()->route('client.profile.dashboard');
            return;
        }

        // اگر پشتیبان قبلاً تخصیص داده شده، به راهنمای هفته آزمایشی برو
        if ($this->trialWeek->status !== TrialWeek::STATUS_PENDING) {
            redirect()->route('client.profile.trial.guide');
            return;
        }

        $this->evaluateWorkingHours();
    }

    /**
     * بررسی اینکه الان داخل ساعت کاری مجموعه (۹ تا ۲۱) هستیم یا نه،
     * و آماده‌سازی پیام مناسب برای دانش‌آموز.
     */
    public function evaluateWorkingHours(): void
    {
        $now  = Carbon::now();
        $hour = (int) $now->format('H');

        $this->isWithinWorkingHours = $hour >= self::WORK_START_HOUR
            && $hour < self::WORK_END_HOUR;

        if ($this->isWithinWorkingHours) {
            $this->workingHoursMessage = 'الان در ساعت کاری هستیم و تیم پشتیبانی در حال بررسی درخواست شماست.';
            $this->nextActiveTime = '';
        } else {
            // خارج از ساعت کاری — محاسبه‌ی زمان شروع رسیدگی بعدی
            if ($hour < self::WORK_START_HOUR) {
                // قبل از ۹ صبحِ همین روز
                $this->nextActiveTime = 'امروز ساعت ۹:۰۰ صبح';
            } else {
                // بعد از ۲۱ — رسیدگی فردا صبح
                $this->nextActiveTime = 'فردا ساعت ۹:۰۰ صبح';
            }

            $this->workingHoursMessage = 'درخواست شما خارج از ساعت کاری مجموعه ثبت شده است. '
                . 'تیم پشتیبانی از ساعت ۹ صبح تا ۹ شب پاسخگوست و درخواست شما '
                . $this->nextActiveTime . ' بررسی خواهد شد.';
        }
    }

    /**
     * بررسی دستی وضعیت — جایگزین wire:poll.
     * با دکمه‌ی «بررسی وضعیت» فراخوانی می‌شود.
     */
    public function checkStatus(): void
    {
        if (!$this->trialWeek) {
            return;
        }

        $this->trialWeek->refresh();
        $this->evaluateWorkingHours();

        if ($this->trialWeek->status !== TrialWeek::STATUS_PENDING) {
            // پشتیبان تخصیص داده شد — به صفحه‌ی راهنما برو
            $this->redirect(route('client.profile.trial.guide'), navigate: true);
            return;
        }

        // هنوز تخصیص داده نشده — پیام به کاربر
        $this->dispatch('status-checked', assigned: false);
    }

    /**
     * لغو هفتهٔ آزمایشی — تنها قبل از تخصیص پشتیبان (status = pending) مجاز است.
     */
    public function cancelTrial(): void
    {
        if (!$this->trialWeek) {
            return;
        }

        if ($this->trialWeek->status !== TrialWeek::STATUS_PENDING) {
            session()->flash('error', 'لغو پس از تخصیص پشتیبان جذب امکان‌پذیر نیست.');
            return;
        }

        $this->trialWeek->delete();
        $this->redirect(route('client.purchase'), navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.profile.trial-week.waiting-for-supporter')
            ->layout('layouts.client.app');
    }
}
