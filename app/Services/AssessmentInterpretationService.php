<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\StudentAssessmentAttempt;
use App\Models\User;

/**
 * تفسیر آزمون‌ها — کاملاً داینامیک و مبتنی بر ستون JSON «interpretation» هر تست.
 *
 * منبع تفسیر به‌ترتیب اولویت:
 *   ۱) ستون interpretation روی همان ردیف assessment (DB)  ← منبع اصلی و قابل ویرایش در پنل
 *   ۲) فایل config/assessment_interpretations.php          ← fallback برای تست‌های قدیمی
 *   ۳) ساخت برچسب از کلید facet/flag                        ← تا هیچ‌وقت کرش نشود
 *
 * اصل طراحی: اگر تستی اضافه/حذف/غیرفعال شد یا تفسیرش نبود، چیزی نباید بشکند.
 */
class AssessmentInterpretationService
{
    /**
     * کارنامهٔ تحلیلی کامل دانش‌آموز از آزمون‌های تکمیل‌شده.
     * فقط آزمون‌هایی که هنوز «فعال» هستند لحاظ می‌شوند.
     *
     * خروجی (سازگار با نسخهٔ قبلی): ['mbti', 'vark', 'custom' => [name => facets], 'flags'].
     */
    public function summaryForUser(User $user): ?array
    {
        $attempts = StudentAssessmentAttempt::where('user_id', $user->id)
            ->where('status', StudentAssessmentAttempt::STATUS_COMPLETED)
            ->whereHas('assessment', fn ($q) => $q->where('is_active', true))
            ->with('assessment')
            ->get();

        if ($attempts->isEmpty()) {
            return null;
        }

        $summary = ['mbti' => null, 'vark' => null, 'custom' => [], 'flags' => []];

        foreach ($attempts as $attempt) {
            $assessment = $attempt->assessment;
            if (! $assessment) {
                continue;
            }
            $cr = $attempt->computed_result;

            // انتخاب نوع تفسیر بر اساس موتور تست — بدون وابستگی به kindهای هاردکد.
            if ($assessment->interpretationEngine() === 'modality') {
                $summary['vark'] = $this->interpretVark($cr, $assessment);
                continue;
            }

            $custom = $this->interpretCustom($cr, $assessment);
            if (! empty($custom['facets'])) {
                $summary['custom'][$assessment->name_fa] = $custom['facets'];
            }
            foreach ($custom['flags'] ?? [] as $flagKey => $flag) {
                $summary['flags'][$flagKey] = $flag;
            }
        }

        return $summary;
    }

    /**
     * تفسیر تست چندبُعدی (VARK و مشابه) — متن مودالیتی‌ها از JSON تست خوانده می‌شود.
     * خروجی: ['profile', 'is_multimodal', 'modalities', 'multimodal_text'].
     */
    public function interpretVark(?array $computed, ?Assessment $assessment = null): array
    {
        if (! $computed || empty($computed['scores'])) {
            return ['profile' => null];
        }

        $scores  = $computed['scores'];
        $profile = $computed['profile'] ?? '';
        $total   = array_sum($scores);

        $jsonModalities = $assessment?->interpretation['modalities'] ?? null;

        $modalities = [];
        foreach (array_keys($scores) as $key) {
            $score = (int) ($scores[$key] ?? 0);
            $meta  = $jsonModalities[$key]
                ?? config('assessment_interpretations.vark_modalities.' . $key, []);
            $modalities[] = [
                'letter'   => $key,
                'title'    => $meta['title'] ?? $key,
                'tip'      => $meta['tip']   ?? '',
                'text'     => $meta['text']  ?? ($meta['tip'] ?? ''),
                'score'    => $score,
                'percent'  => $total > 0 ? (int) round(($score / $total) * 100) : 0,
                'dominant' => $profile !== '' && str_contains($profile, (string) $key),
            ];
        }

        return [
            'profile'         => $profile,
            'is_multimodal'   => strlen($profile) > 1,
            'modalities'      => $modalities,
            'multimodal_text' => $assessment?->interpretation['multimodal_text'] ?? null,
        ];
    }

    /**
     * تفسیر تست facet-based — متن سطوح و پرچم‌ها از JSON تست خوانده می‌شود.
     * خروجی: ['facets', 'flags', 'overall_percent', 'overall_level', 'overall_text'].
     */
    public function interpretCustom(?array $computed, ?Assessment $assessment = null): array
    {
        if (! $computed) {
            return ['facets' => [], 'flags' => []];
        }

        $json = $assessment?->interpretation ?? [];

        $facets = [];
        foreach ($computed['facets'] ?? [] as $facet => $data) {
            $meta  = $json['facets'][$facet]
                ?? config('assessment_interpretations.facets.' . $facet, []);
            $level = $data['level'] ?? 'medium';
            $facets[$facet] = [
                'key'     => $facet,
                'label'   => $meta['label'] ?? $this->humanize($facet),
                'percent' => (int) ($data['percent'] ?? 0),
                'level'   => $level,
                'text'    => $meta[$level] ?? '—',
            ];
        }

        $flags = [];
        foreach ($computed['flags'] ?? [] as $flag => $triggered) {
            if (! $triggered) {
                continue;
            }
            $flags[$flag] = $this->flagLabel($flag, $assessment);
        }

        $overallLevel = $computed['overall_level'] ?? 'medium';
        $overallText  = $json['overall'][$overallLevel] ?? null;

        return [
            'facets'          => $facets,
            'flags'           => $flags,
            'overall_percent' => (int) ($computed['overall_percent'] ?? 0),
            'overall_level'   => $overallLevel,
            'overall_text'    => $overallText,
        ];
    }

    /**
     * نگه‌داشته‌شده برای سازگاری عقب‌رو. MBTI از سیستم حذف شده، پس عملاً هرگز
     * با دادهٔ واقعی فراخوانی نمی‌شود؛ اما حذف نمی‌کنیم تا کدهای مصرف‌کننده نشکنند.
     */
    public function interpretMbti(?array $computed): array
    {
        return ['type' => null];
    }

    /**
     * برچسب پرچم — اول از JSON تست، سپس متن‌های پیش‌فرض.
     */
    private function flagLabel(string $flag, ?Assessment $assessment = null): array
    {
        $fromJson = $assessment?->interpretation['flags'][$flag] ?? null;
        if (is_array($fromJson)) {
            return [
                'severity' => $fromJson['severity'] ?? 'info',
                'title'    => $fromJson['title']    ?? $this->humanize($flag),
                'text'     => $fromJson['text']     ?? '',
            ];
        }

        return match ($flag) {
//            'flag_safety' => [
//                'severity' => 'critical',
//                'title'    => 'هشدار ایمنی جانی',
//                'text'     => 'دانش‌آموز پاسخی داده که نشان از فکر منفی شدید درباره‌ی وجود خود دارد. نیاز به بررسی فوری مشاور/روان‌شناس.',
//            ],
//            'flag' => [
//                'severity' => 'warning',
//                'title'    => 'پرچم بالینی',
//                'text'     => 'پاسخ‌های flag در آزمون نشان از احتمال بالای نیاز به ارجاع پزشک متخصص است (مثلاً ADHD).',
//            ],
            default => [
                'severity' => 'info',
                'title'    => $this->humanize($flag),
                'text'     => '',
            ],
        };
    }

    /**
     * ساخت برچسب خوانا از کلید facet/flag وقتی تفسیری تعریف نشده.
     */
    private function humanize(string $key): string
    {
        return ucfirst(str_replace(['_', '-'], ' ', $key));
    }
}
