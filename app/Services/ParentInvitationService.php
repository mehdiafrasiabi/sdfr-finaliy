<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\ParentAssessmentAttempt;
use App\Models\ParentAssessmentInvitation;
use App\Models\TrialWeek;
use App\Notifications\ParentAssessmentInvite;
use Carbon\Carbon;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParentInvitationService
{
    /** اعتبار توکن ۷ روز */
    public const TOKEN_TTL_DAYS = 7;

    /**
     * برای پدر و مادر (اگر شماره ثبت شده) invitation می‌سازد یا token تازه می‌کند
     * و SMS لینک را ارسال می‌کند. در checkAllCompleted دانش‌آموز فراخوانی می‌شود.
     */
    public function sendForTrialWeek(TrialWeek $trial): void
    {
        if (! $trial->user) {
            return;
        }

        $candidates = [
            ParentAssessmentInvitation::ROLE_FATHER => $trial->father_mobile,
            ParentAssessmentInvitation::ROLE_MOTHER => $trial->mother_mobile,
        ];

        foreach ($candidates as $role => $mobile) {
            if (! $mobile) {
                continue;
            }
            $inv = $this->createOrRefreshInvitation($trial, $role, $mobile);
            $this->sendSms($inv, $trial->user->name ?? 'دانش‌آموز شما');
        }
    }

    /**
     * توکن را اعتبارسنجی می‌کند. اگر منقضی یا یافت نشد، null برمی‌گرداند.
     */
    public function verifyToken(string $token): ?ParentAssessmentInvitation
    {
        $inv = ParentAssessmentInvitation::where('token', $token)->first();
        if (! $inv) {
            return null;
        }
        if ($inv->isExpired()) {
            return null;
        }
        return $inv;
    }

    /**
     * ارسال مجدد: token تازه + expires_at تازه + sms_attempts++
     */
    public function resend(ParentAssessmentInvitation $inv): void
    {
        $studentName = $inv->user?->name ?? 'دانش‌آموز شما';
        $inv->update([
            'token'       => $this->generateToken(),
            'expires_at'  => Carbon::now()->addDays(self::TOKEN_TTL_DAYS),
            'sms_attempts'=> $inv->sms_attempts + 1,
        ]);
        $this->sendSms($inv->fresh(), $studentName);
    }

    /**
     * پس از تکمیل یک attempt، چک می‌کند آیا همه‌ی تست‌های parent (audience='parent')
     * توسط این invitation تمام شده‌اند. اگر بله، completed_at را پر می‌کند.
     */
    public function markInvitationCompletedIfDone(ParentAssessmentInvitation $inv): bool
    {
        $required = Assessment::active()->where('audience', Assessment::AUDIENCE_PARENT)->count();
        if ($required === 0) {
            return false;
        }
        $completed = ParentAssessmentAttempt::where('invitation_id', $inv->id)
            ->where('status', ParentAssessmentAttempt::STATUS_COMPLETED)
            ->whereHas('assessment', function ($q) {
                $q->where('is_active', true)->where('audience', Assessment::AUDIENCE_PARENT);
            })
            ->count();
        if ($completed >= $required && ! $inv->completed_at) {
            $inv->update(['completed_at' => Carbon::now()]);
            return true;
        }
        return false;
    }

    private function createOrRefreshInvitation(TrialWeek $trial, string $role, string $mobile): ParentAssessmentInvitation
    {
        return DB::transaction(function () use ($trial, $role, $mobile) {
            $inv = ParentAssessmentInvitation::where('user_id', $trial->user_id)
                ->where('parent_role', $role)
                ->first();

            $attrs = [
                'trial_week_id' => $trial->id,
                'mobile'        => $mobile,
                'token'         => $this->generateToken(),
                'expires_at'    => Carbon::now()->addDays(self::TOKEN_TTL_DAYS),
                'sent_at'       => Carbon::now(),
            ];

            if ($inv) {
                $inv->update($attrs);
                $inv->increment('sms_attempts');
                return $inv->fresh();
            }

            return ParentAssessmentInvitation::create(array_merge($attrs, [
                'user_id'      => $trial->user_id,
                'parent_role'  => $role,
                'sms_attempts' => 1,
            ]));
        });
    }

    private function sendSms(ParentAssessmentInvitation $inv, string $studentName): void
    {
        $link = route('client.parent.assessment.entry', ['token' => $inv->token]);

        try {
            (new AnonymousNotifiable())
                ->route(\App\Notifications\Channels\ParentInviteSmsChannel::class, $inv->mobile)
                ->notify(new ParentAssessmentInvite(
                    mobile:      $inv->mobile,
                    studentName: $studentName,
                    link:        $link,
                    parentRole:  $inv->parent_role,
                ));
        } catch (\Throwable $e) {
            Log::error('ParentInvite SMS failed', [
                'invitation_id' => $inv->id,
                'mobile'        => $inv->mobile,
                'error'         => $e->getMessage(),
            ]);
            // SMS fail نباید فرایند را بشکند — لینک در پنل manager قابل ارسال مجدد است.
        }
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
