<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * لینک یکتای ثبت‌نام مشاور جذب تلفنی (ردیابی تبدیل و پاداش).
 */
class PhoneRegistrationLink extends Model
{
    public const PLAN_DEFAULT = 'default';
    public const PLAN_TRIAL = 'trial';
    public const PLAN_EXAM = 'exam';

    protected $guarded = [];

    protected $casts = [
        'sent_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(PhoneLead::class, 'phone_lead_id');
    }

    /** مشاور جذب تلفنی که لینک را ارسال کرده است. */
    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /** کاربری که با این لینک ثبت‌نام کرده است. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_user_id');
    }

    public function isConverted(): bool
    {
        return $this->used_at !== null;
    }

    public function completedRegistrationProgramType(): ?string
    {
        if (! $this->registered_user_id || ! $this->user) {
            return null;
        }

        if ($this->completedExamProgramBuiltAt()) {
            return self::PLAN_EXAM;
        }

        if ($this->completedTrialProgramBuiltAt()) {
            return self::PLAN_TRIAL;
        }

        return null;
    }

    public function hasCompletedProgramRegistration(): bool
    {
        return $this->completedRegistrationProgramType() !== null;
    }

    public function completedRegistrationAt(): ?Carbon
    {
        return $this->completedExamProgramBuiltAt()
            ?? $this->completedTrialProgramBuiltAt();
    }

    protected function completedTrialProgramBuiltAt(): ?Carbon
    {
        $user = $this->user;
        if (! $user) {
            return null;
        }

        $trial = $user->relationLoaded('trialWeek')
            ? $user->trialWeek
            : $user->trialWeek()->first();

        if (! $trial || $trial->acq_disinterest_status !== null) {
            return null;
        }

        if ($trial->program_built_at) {
            return $trial->program_built_at;
        }

        if ($trial->status === TrialWeek::STATUS_PROGRAM_BUILT) {
            return $trial->updated_at ?? $trial->created_at;
        }

        return null;
    }

    protected function completedExamProgramBuiltAt(): ?Carbon
    {
        $user = $this->user;
        if (! $user) {
            return null;
        }

        $examSchedules = collect();
        $hasLoadedExamRelations = false;

        if ($user->relationLoaded('examSchedules')) {
            $hasLoadedExamRelations = true;
            $examSchedules = $examSchedules->merge($user->examSchedules);
        }

        if ($user->relationLoaded('student')) {
            $student = $user->student;
            if ($student?->relationLoaded('examSchedules')) {
                $hasLoadedExamRelations = true;
                $examSchedules = $examSchedules->merge($student->examSchedules);
            }
        }

        if (! $hasLoadedExamRelations) {
            $builtAt = $user->examSchedules()
                ->whereNotNull('weekly_program_id')
                ->whereNotNull('program_built_at')
                ->oldest('program_built_at')
                ->value('program_built_at');

            return $builtAt ? Carbon::parse($builtAt) : null;
        }

        $schedule = $examSchedules
            ->filter(fn ($schedule) => $schedule->weekly_program_id !== null && $schedule->program_built_at !== null)
            ->sortBy('program_built_at')
            ->first();

        return $schedule?->program_built_at;
    }

    public static function isValidPlan(string $plan): bool
    {
        return in_array($plan, [
            self::PLAN_DEFAULT,
            self::PLAN_TRIAL,
            self::PLAN_EXAM,
        ], true);
    }

    /** نشانی کامل لینک یکتا. */
    public function getUrlAttribute(): string
    {
        return rtrim(config('services.melipayamak.public_url', 'https://sdfr.me'), '/') . '/r/' . $this->token;
    }
}
