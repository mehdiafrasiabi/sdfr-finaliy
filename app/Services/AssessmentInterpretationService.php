<?php

namespace App\Services;

use App\Models\Assessment;

class AssessmentInterpretationService
{
    /**
     * تفسیر MBTI — ورودی computed_result از StudentAssessmentAttempt.
     * خروجی: ['type', 'title', 'description', 'study_tip', 'axes'].
     */
    public function interpretMbti(?array $computed): array
    {
        if (! $computed || empty($computed['type'])) {
            return ['type' => null];
        }
        $type = $computed['type'];
        $meta = config('assessment_interpretations.mbti_types.' . $type, []);
        return [
            'type'        => $type,
            'title'       => $meta['title']     ?? '—',
            'description' => $meta['short']     ?? '—',
            'study_tip'   => $meta['study_tip'] ?? '—',
            'axes'        => $computed['axes']  ?? [],
        ];
    }

    /**
     * تفسیر VARK — خروجی شامل profile، دومینانت‌ها و تفسیر هر مودالیتی.
     */
    public function interpretVark(?array $computed): array
    {
        if (! $computed || empty($computed['scores'])) {
            return ['profile' => null];
        }

        $scores = $computed['scores'];
        $profile = $computed['profile'] ?? '';
        $total = array_sum($scores);

        $modalities = [];
        foreach (['V', 'A', 'R', 'K'] as $key) {
            $score = (int) ($scores[$key] ?? 0);
            $meta = config('assessment_interpretations.vark_modalities.' . $key, []);
            $modalities[] = [
                'letter'   => $key,
                'title'    => $meta['title'] ?? $key,
                'tip'      => $meta['tip']   ?? '',
                'score'    => $score,
                'percent'  => $total > 0 ? (int) round(($score / $total) * 100) : 0,
                'dominant' => $profile !== '' && str_contains($profile, $key),
            ];
        }

        return [
            'profile'    => $profile,
            'is_multimodal' => strlen($profile) > 1,
            'modalities' => $modalities,
        ];
    }

    /**
     * تفسیر تست اختصاصی — ورودی computed_result و خود assessment.
     * خروجی: ['facets' => [...], 'flags' => [...], 'overall_level', 'overall_percent'].
     */
    public function interpretCustom(?array $computed, ?Assessment $assessment = null): array
    {
        if (! $computed) {
            return ['facets' => [], 'flags' => []];
        }

        $facets = [];
        foreach ($computed['facets'] ?? [] as $facet => $data) {
            $meta = config('assessment_interpretations.facets.' . $facet, []);
            $level = $data['level'] ?? 'medium';
            $facets[$facet] = [
                'key'     => $facet,
                'label'   => $meta['label']     ?? $facet,
                'percent' => (int) ($data['percent'] ?? 0),
                'level'   => $level,
                'text'    => $meta[$level]      ?? '—',
            ];
        }

        $flags = [];
        foreach ($computed['flags'] ?? [] as $flag => $triggered) {
            if (! $triggered) {
                continue;
            }
            $flags[$flag] = $this->flagLabel($flag);
        }

        return [
            'facets'          => $facets,
            'flags'           => $flags,
            'overall_percent' => (int) ($computed['overall_percent'] ?? 0),
            'overall_level'   => $computed['overall_level'] ?? 'medium',
        ];
    }

    private function flagLabel(string $flag): array
    {
        return match ($flag) {
            'flag_safety' => [
                'severity' => 'critical',
                'title'    => 'هشدار ایمنی جانی',
                'text'     => 'دانش‌آموز پاسخی داده که نشان از فکر منفی شدید درباره‌ی وجود خود دارد. نیاز به بررسی فوری مشاور/روان‌شناس.',
            ],
            'flag' => [
                'severity' => 'warning',
                'title'    => 'پرچم بالینی',
                'text'     => 'پاسخ‌های flag در آزمون نشان از احتمال بالای نیاز به ارجاع پزشک متخصص است (مثلاً ADHD).',
            ],
            default => [
                'severity' => 'info',
                'title'    => $flag,
                'text'     => '',
            ],
        };
    }
}
