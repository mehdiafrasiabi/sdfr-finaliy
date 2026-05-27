<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\StudentAssessmentAttempt;

class AssessmentScoringService
{
    /**
     * Dispatch بر اساس kind آزمون. خروجی JSON-serializable یا null.
     */
    public function score(StudentAssessmentAttempt $attempt): ?array
    {
        $attempt->loadMissing('assessment', 'answers.option', 'assessment.questions.options');

        return match ($attempt->assessment->kind) {
            Assessment::KIND_MBTI => $this->scoreMbti($attempt),
            Assessment::KIND_VARK => $this->scoreVark($attempt),
            default               => null,
        };
    }

    /**
     * MBTI: ۴ محور EI/SN/TF/JP. هر سوال scoring_meta = {axis, a_pole, b_pole}.
     * گزینه‌های A/B هر سوال value='A' یا value='B' دارند.
     * تایپ = ترکیب ۴ قطب برنده. در تساوی → I, N, F, P (default ثابت).
     */
    private function scoreMbti(StudentAssessmentAttempt $attempt): array
    {
        $axes = [
            'E' => 0, 'I' => 0,
            'S' => 0, 'N' => 0,
            'T' => 0, 'F' => 0,
            'J' => 0, 'P' => 0,
        ];

        $questionsById = $attempt->assessment->questions->keyBy('id');

        foreach ($attempt->answers as $answer) {
            $question = $questionsById->get($answer->question_id);
            if (!$question || !$answer->option) {
                continue;
            }
            $meta = $question->scoring_meta ?? [];
            $aPole = $meta['a_pole'] ?? null;
            $bPole = $meta['b_pole'] ?? null;
            if (!$aPole || !$bPole) {
                continue;
            }
            $value = $answer->option->value;
            if ($value === 'A' && isset($axes[$aPole])) {
                $axes[$aPole]++;
            } elseif ($value === 'B' && isset($axes[$bPole])) {
                $axes[$bPole]++;
            }
        }

        $type = $this->pickPole($axes['E'], $axes['I'], 'E', 'I')
              . $this->pickPole($axes['S'], $axes['N'], 'S', 'N')
              . $this->pickPole($axes['T'], $axes['F'], 'T', 'F')
              . $this->pickPole($axes['J'], $axes['P'], 'J', 'P');

        return [
            'type' => $type,
            'axes' => $axes,
        ];
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

    /**
     * در تساوی، قطب پیش‌فرض (پارامتر دوم) برنده است (I, N, F, P).
     */
    private function pickPole(int $aScore, int $bScore, string $a, string $b): string
    {
        return $aScore > $bScore ? $a : $b;
    }
}
