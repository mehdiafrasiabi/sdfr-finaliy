<?php

namespace App\Services;

use App\Models\PhoneCall;
use App\Models\PhoneLead;
use Carbon\Carbon;

/**
 * منطق چرخهٔ حیات شمارهٔ «مشاور جذب تلفنی» پس از یک تماسِ ناموفق.
 *
 * قواعد (هر علت مستقل؛ با تغییر علت رشته از اول شروع می‌شود):
 *   • عدم پاسخ (no_answer):  ۲ بار در همان روز → موکول به فردا؛ فردا هم ۲ بار → خاکستری «عدم پاسخ».
 *   • رد تماس (rejected):    مثل عدم پاسخ (۲ بار در روز، ۲ روز) → خاکستری «رد تماس».
 *   • خاموش (off):           هر بار موکول به فردا؛ بعد از ۴ بار → خاکستری «خاموش».
 *   • شماره اشتباه (wrong):  بلافاصله خاکستری «شماره اشتباه».
 */
class PhoneLeadScheduler
{
    /** حداکثر تماس در یک روز برای عدم‌پاسخ/رد تماس. */
    const PER_DAY = 2;

    /** حداکثر تماسِ خاموش پیش از خاکستری‌شدن. */
    const OFF_MAX = 4;

    /**
     * وضعیت/زمان‌بندی بعدی شماره را پس از ثبت تماسِ ناموفق تعیین می‌کند.
     * پیش‌فرض: رکورد تماسِ ناموفق همین‌الان ساخته شده و در تاریخچهٔ lead موجود است.
     *
     * @return array{status:string, grey_reason:?string, next_call_at:?\Carbon\Carbon}
     */
    public function afterFail(PhoneLead $lead, string $reason): array
    {
        // شماره اشتباه → خاکستری فوری
        if ($reason === PhoneCall::FAIL_WRONG) {
            return $this->grey(PhoneCall::FAIL_WRONG);
        }

        $streak = $this->currentFailStreak($lead, $reason);

        // خاموش: هر بار به فردا، بعد از ۴ بار خاکستری
        if ($reason === PhoneCall::FAIL_OFF) {
            return count($streak) >= self::OFF_MAX
                ? $this->grey(PhoneCall::FAIL_OFF)
                : $this->active($this->nextDay());
        }

        // عدم پاسخ / رد تماس: ۲ بار در روز برای ۲ روز، سپس خاکستری
        $byDate = [];
        foreach ($streak as $c) {
            $d = $c->called_at->toDateString();
            $byDate[$d] = ($byDate[$d] ?? 0) + 1;
        }
        ksort($byDate);

        $dayIndex   = count($byDate);              // امروز همیشه آخرین (بزرگ‌ترین) تاریخ است
        $todayCount = $byDate[array_key_last($byDate)];

        // روز سوم به بعد نباید رخ دهد؛ اگر رخ داد، خاکستری می‌کنیم.
        if ($dayIndex >= 3) {
            return $this->grey($reason);
        }

        // روز دوم و رسیدن به سقف روزانه → خاکستری
        if ($dayIndex === 2 && $todayCount >= self::PER_DAY) {
            return $this->grey($reason);
        }

        // رسیدن به سقف روزانه در روز اول → موکول به فردا
        if ($dayIndex === 1 && $todayCount >= self::PER_DAY) {
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
}
