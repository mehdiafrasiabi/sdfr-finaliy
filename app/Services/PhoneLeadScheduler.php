<?php

namespace App\Services;

use App\Models\PhoneCall;
use App\Models\PhoneLead;
use Carbon\Carbon;

/**
 * منطق چرخهٔ حیات شمارهٔ «مشاور جذب تلفنی» پس از یک تماسِ ناموفق.
 *
 * قواعد:
 *   • شماره اشتباه (wrong):  بلافاصله خاکستری «شماره اشتباه».
 *   • هر ۵ تماس ناموفق پشت‌سرهم با علت‌های دیگر: عدم تمایل قطعی.
 *   • قبل از ۵ تماس ناموفق، زمان‌بندی پیگیری مثل قبل انجام می‌شود.
 */
class PhoneLeadScheduler
{
    /** حداکثر تماس در یک روز برای عدم‌پاسخ/رد تماس. */
    const PER_DAY = 2;

    /**
     * وضعیت/زمان‌بندی بعدی شماره را پس از ثبت تماسِ ناموفق تعیین می‌کند.
     * پیش‌فرض: رکورد تماسِ ناموفق همین‌الان ساخته شده و در تاریخچهٔ lead موجود است.
     *
     * @return array{status:string, grey_reason:?string, next_call_at:?\Carbon\Carbon, last_outcome?:string, disinterest_status?:string, disinterest_reason?:string, disinterest_at?:\Carbon\Carbon}
     */
    public function afterFail(PhoneLead $lead, string $reason): array
    {
        // شماره اشتباه → خاکستری فوری
        if ($reason === PhoneCall::FAIL_WRONG) {
            return $this->grey(PhoneCall::FAIL_WRONG);
        }

        if (count($this->currentUnansweredStreak($lead)) >= PhoneLead::MAX_ATTEMPTS) {
            return $this->definitiveDisinterest();
        }

        $streak = $this->currentFailStreak($lead, $reason);

        // خاموش: هر بار به فردا؛ تبدیل نهایی فقط با ۵ تماس ناموفق پشت‌سرهم انجام می‌شود.
        if ($reason === PhoneCall::FAIL_OFF) {
            return $this->active($this->nextDay());
        }

        // عدم پاسخ / رد تماس: ۲ بار در روز؛ بعد از سقف روزانه موکول به فردا.
        $byDate = [];
        foreach ($streak as $c) {
            $d = $c->called_at->toDateString();
            $byDate[$d] = ($byDate[$d] ?? 0) + 1;
        }
        ksort($byDate);

        $todayCount = $byDate[array_key_last($byDate)];

        if ($todayCount >= self::PER_DAY) {
            return $this->active($this->nextDay());
        }

        // هنوز به سقف روزانه نرسیده → پیگیری در همان روز فعال
        return $this->active(Carbon::now());
    }

    /** آخرین رشتهٔ پیوستهٔ تماس‌های ناموفقِ هم‌علت (شامل تماس جاری). */
    protected function currentFailStreak(PhoneLead $lead, string $reason): array
    {
        $calls = $lead->calls()->orderByDesc('called_at')->orderByDesc('id')->get();

        $streak = [];
        foreach ($calls as $c) {
            if (! $c->connected && $c->fail_reason === $reason) {
                $streak[] = $c;
            } else {
                break; // رشته با تغییر علت یا تماس برقرارشده می‌شکند
            }
        }

        return $streak;
    }

    /** آخرین رشتهٔ پیوستهٔ تماس‌های ناموفق با هر علتی به‌جز شماره اشتباه. */
    protected function currentUnansweredStreak(PhoneLead $lead): array
    {
        $calls = $lead->calls()->orderByDesc('called_at')->orderByDesc('id')->get();

        $streak = [];
        foreach ($calls as $call) {
            if (! $call->connected && $call->fail_reason !== PhoneCall::FAIL_WRONG) {
                $streak[] = $call;
            } else {
                break;
            }
        }

        return $streak;
    }

    protected function nextDay(): Carbon
    {
        // فردا، ابتدای ساعات تماس
        return Carbon::now()->addDay()->startOfDay()->addHours(9);
    }

    protected function grey(string $reason): array
    {
        return ['status' => PhoneLead::STATUS_DEAD, 'grey_reason' => $reason, 'next_call_at' => null];
    }

    protected function active(Carbon $nextCallAt): array
    {
        return ['status' => PhoneLead::STATUS_ACTIVE, 'grey_reason' => null, 'next_call_at' => $nextCallAt];
    }

    protected function definitiveDisinterest(): array
    {
        return [
            'status' => PhoneLead::STATUS_CLOSED,
            'grey_reason' => null,
            'next_call_at' => null,
            'last_outcome' => PhoneCall::RESULT_NO_INTEREST,
            'disinterest_status' => PhoneCall::DISINTEREST_DEFINITIVE,
            'disinterest_reason' => 'پس از ۵ تماس ناموفق پشت‌سرهم، سیستم به‌صورت خودکار عدم تمایل قطعی ثبت کرد.',
            'disinterest_at' => Carbon::now(),
        ];
    }
}
