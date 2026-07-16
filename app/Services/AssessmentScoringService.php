<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\StudentAssessmentAttempt;

class AssessmentScoringService
{
    /** سه سطح برای facet scoring */
    public const LEVEL_LOW    = 'low';
    public const LEVEL_MEDIUM = 'medium';
    public const LEVEL_HIGH   = 'high';

    /**
     * Dispatch بر اساس kind آزمون. خروجی JSON-serializable یا null.
     */
    public function score(StudentAssessmentAttempt $attempt): ?array
    {
        $attempt->loadMissing('assessment', 'answers.option', 'assessment.questions.options');

        // dispatch بر اساس موتورِ داینامیک تست (نه kind هاردکد).
        return match ($attempt->assessment->interpretationEngine()) {
            'modality' => $this->scoreVark($attempt),
            default    => $this->scoreCustom($attempt),
        };
    }

    /**
     * نمره‌دهی facet-based برای تست‌های اختصاصی.
     * خروجی شامل facets (با درصد و سطح) + flags + overall.
     */
    public function scoreCustom(StudentAssessmentAttempt $attempt): array
    {
        $attempt->loadMissing('assessment.questions.options', 'answers.option');

        $facetSums = [];   // [facet => sum]
        $facetMax  = [];   // [facet => max possible]
        $facetMin  = [];   // [facet => min possible]
        $flags     = [];

        // آستانه‌های سطح‌بندی per-test (پیش‌فرض ۴۰/۷۰) — قابل override در JSON تفسیر.
        $th       = $attempt->assessment->interpretation['thresholds'] ?? [];
        $lowMax   = (int) ($th['low_max']    ?? 39);
        $medMax   = (int) ($th['medium_max'] ?? 70);

        $questionsById = $attempt->assessment->questions->keyBy('id');
        $answersByQ = $attempt->answers->keyBy('question_id');

        foreach ($attempt->assessment->questions as $q) {
            if (! $q->is_active) {
                continue;
            }
            $meta = $q->scoring_meta ?? [];
            $facet = $meta['facet'] ?? null;
            if (! $facet) {
                continue;
            }
            $reverse = (bool) ($meta['reverse'] ?? false);
            $answer = $answersByQ->get($q->id);
            if (! $answer) {
                continue;
            }

            // سوالات flag به‌جای facet در flags جمع می‌شوند
            if (str_starts_with($facet, 'flag')) {
                $isYes = ($answer->free_value === 'yes')
                    || ($answer->option && $answer->option->value === 'yes');
                if ($isYes) {
                    $flags[$facet] = true;
                }
                continue;
            }

            [$score, $min, $max] = $this->scoreSingleAnswer($q, $answer, $reverse);

            $facetSums[$facet] = ($facetSums[$facet] ?? 0) + $score;
            $facetMin[$facet]  = ($facetMin[$facet]  ?? 0) + $min;
            $facetMax[$facet]  = ($facetMax[$facet]  ?? 0) + $max;
        }

        $facets = [];
        $sumPercent = 0;
        foreach ($facetSums as $facet => $sum) {
            $max = $facetMax[$facet];
            $min = $facetMin[$facet];
            $range = max(1, $max - $min);
            $percent = (int) round((($sum - $min) / $range) * 100);
            $percent = max(0, min(100, $percent));
            $facets[$facet] = [
                'score'   => $sum,
                'min'     => $min,
                'max'     => $max,
                'percent' => $percent,
                'level'   => $this->percentToLevel($percent, $lowMax, $medMax),
            ];
            $sumPercent += $percent;
        }

        $overallPercent = $facets ? (int) round($sumPercent / count($facets)) : 0;

        return [
            'facets'          => $facets,
            'flags'           => $flags,
            'overall_percent' => $overallPercent,
            'overall_level'   => $this->percentToLevel($overallPercent, $lowMax, $medMax),
        ];
    }

    /**
     * امتیاز یک پاسخ + min و max ممکن آن سوال (برای محاسبه‌ی درصد facet).
     */
    private function scoreSingleAnswer($question, $answer, bool $reverse): array
    {
        if ($question->type === \App\Models\AssessmentQuestion::TYPE_LIKERT5) {
            $raw = (int) ($answer->free_value ?: ($answer->option?->value ?? 3));
            $raw = max(1, min(5, $raw));
            $score = $reverse ? (6 - $raw) : $raw;
            return [$score, 1, 5];
        }

        if ($question->type === \App\Models\AssessmentQuestion::TYPE_YES_NO) {
            $isYes = ($answer->free_value === 'yes') || ($answer->option && $answer->option->value === 'yes');
            $raw = $isYes ? 1 : 0;
            $score = $reverse ? (1 - $raw) : $raw;
            return [$score, 0, 1];
        }

        return [0, 0, 0];
    }

    private function percentToLevel(int $percent, int $lowMax = 39, int $mediumMax = 70): string
    {
        return match (true) {
            $percent <= $lowMax    => self::LEVEL_LOW,
            $percent <= $mediumMax => self::LEVEL_MEDIUM,
            default                => self::LEVEL_HIGH,
        };
    }

    /**
     * VARK: ۴ بُعد V/A/R/K. هر گزینه weights = {"V":1} یا … دارد.
     * profile = ترکیب بُعدهای با حداکثر امتیاز (پشتیبانی multimodal).
     */
    private function scoreVark(StudentAssessmentAttempt $attempt): array
    {
        $scores = ['V' => 0, 'A' => 0, 'R' => 0, 'K' => 0];

        $optionsById = $attempt->assessment->questions
            ->flatMap(fn ($q) => $q->options)
            ->keyBy('id');

        foreach ($attempt->answers as $answer) {
            $selectedIds = [];
            if (!empty($answer->selected_options)) {
                $selectedIds = $answer->selected_options;
            } elseif ($answer->selected_option_id) {
                $selectedIds = [$answer->selected_option_id];
            }

            foreach ($selectedIds as $optionId) {
                $option = $optionsById->get($optionId);
                if (!$option) {
                    continue;
                }
                $weights = $option->weights ?? [];
                foreach ($weights as $modality => $weight) {
                    if (isset($scores[$modality])) {
                        $scores[$modality] += (int) $weight;
                    }
                }
            }
        }

        $max = max($scores);
        $profile = '';
        if ($max > 0) {
            foreach (['V', 'A', 'R', 'K'] as $modality) {
                if ($scores[$modality] === $max) {
                    $profile .= $modality;
                }
            }
        }

        return [
            'profile' => $profile,
            'scores'  => $scores,
        ];
    }
}
