<?php

namespace App\Jobs;

use App\Models\TrialWeek;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * هر هفته آزمایشی منقضی‌شده‌ای که کاربر هنوز خرید نکرده باشد را غیرفعال می‌کند.
 * بعد از غیرفعال شدن، EnsureSupporterAssigned middleware کاربر را به /checkout هدایت می‌کند.
 */
class ExpireTrialWeeks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = now();

        TrialWeek::query()
            ->where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->with('user.student.payment')
            ->chunkById(100, function ($trials) {
                foreach ($trials as $trial) {
                    $paid = $trial->user
                        && $trial->user->student
                        && $trial->user->student->payment
                        && $trial->user->student->payment->status === 'completed';

                    if ($paid) {
                        continue;
                    }

                    $trial->update(['is_active' => false]);
                }
            });
    }
}
