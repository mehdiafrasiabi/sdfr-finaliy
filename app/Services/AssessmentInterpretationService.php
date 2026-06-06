<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\StudentAssessmentAttempt;
use App\Models\User;
use Illuminate\Support\Collection;

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

    /**
     * خلاصه‌ی پروفایل برای صفحه‌ی ProfileReview دانش‌آموز.
     * شامل MBTI، VARK، و فهرست facetهای ۹ تست Mindset.
     */
    public function summarizeForReview(User $user): array
    {
        $attempts = StudentAssessmentAttempt::where('user_id', $user->id)
            ->where('status', StudentAssessmentAttempt::STATUS_COMPLETED)
            ->with('assessment')
            ->get();

        $mbti = $this->extractByKind($attempts, Assessment::KIND_MBTI);
        $vark = $this->extractByKind($attempts, Assessment::KIND_VARK);

        $mindsetFacets = [];
        foreach ($attempts as $attempt) {
            if ($attempt->assessment->kind !== Assessment::KIND_CUSTOM
                || $attempt->assessment->stage !== Assessment::STAGE_MINDSET) {
                continue;
            }
            $interpreted = $this->interpretCustom($attempt->computed_result, $attempt->assessment);
            foreach ($interpreted['facets'] ?? [] as $key => $facet) {
                $mindsetFacets[] = array_merge($facet, [
                    'assessment_name' => $attempt->assessment->name_fa,
                ]);
            }
        }

        return [
            'mbti'           => $this->interpretMbti($mbti?->computed_result),
            'vark'           => $this->interpretVark($vark?->computed_result),
            'mindset_facets' => $mindsetFacets,
        ];
    }

    /**
     * گزارش کامل برای کارنامه‌ی نهایی (StudentReport).
     * شامل پروفایل کامل + توصیه‌های ترکیبی + facetها با تفسیر کامل.
     */
    public function fullReport(User $user): array
    {
        $attempts = StudentAssessmentAttempt::where('user_id', $user->id)
            ->where('status', StudentAssessmentAttempt::STATUS_COMPLETED)
            ->with('assessment')
            ->get();

        $mbti = $this->extractByKind($attempts, Assessment::KIND_MBTI);
        $vark = $this->extractByKind($attempts, Assessment::KIND_VARK);

        $mbtiInterpreted = $this->interpretMbti($mbti?->computed_result);
        $varkInterpreted = $this->interpretVark($vark?->computed_result);

        $mindsetSections = [];
        foreach ($attempts as $attempt) {
            if ($attempt->assessment->kind !== Assessment::KIND_CUSTOM
                || $attempt->assessment->stage !== Assessment::STAGE_MINDSET) {
                continue;
            }
            $interpreted = $this->interpretCustom($attempt->computed_result, $attempt->assessment);
            $mindsetSections[] = [
                'assessment'      => $attempt->assessment,
                'interpretation'  => $interpreted,
            ];
        }

        return [
            'mbti'             => $mbtiInterpreted,
            'vark'             => $varkInterpreted,
            'mindset_sections' => $mindsetSections,
            'study_tips'       => $this->interpretStudyTips($mbtiInterpreted, $varkInterpreted),
        ];
    }

    /**
     * توصیه‌های ترکیبی سبک مطالعه بر اساس MBTI + VARK.
     */
    public function interpretStudyTips(array $mbti, array $vark): array
    {
        $tips = [];

        if (! empty($mbti['study_tip'])) {
            $tips[] = [
                'source' => 'MBTI · ' . ($mbti['title'] ?? $mbti['type'] ?? ''),
                'text'   => $mbti['study_tip'],
            ];
        }

        foreach ($vark['modalities'] ?? [] as $m) {
            if (! ($m['dominant'] ?? false)) {
                continue;
            }
            if (! empty($m['tip'])) {
                $tips[] = [
                    'source' => 'VARK · ' . $m['title'],
                    'text'   => $m['tip'],
                ];
            }
        }

        if (empty($tips)) {
            $tips[] = [
                'source' => '—',
                'text'   => 'پروفایل کافی برای ارائه‌ی توصیه‌ی شخصی‌سازی‌شده موجود نیست.',
            ];
        }

        return $tips;
    }

    private function extractByKind(Collection $attempts, string $kind): ?StudentAssessmentAttempt
    {
        return $attempts->first(fn ($a) => $a->assessment?->kind === $kind);
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
