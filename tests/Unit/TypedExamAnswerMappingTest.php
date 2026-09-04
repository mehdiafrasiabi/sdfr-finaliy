<?php

namespace Tests\Unit;

use App\Livewire\Client\Profile\TypedExam\TypedExamResult;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\TypedExamAttemptAnswer;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class TypedExamAnswerMappingTest extends TestCase
{
    public function test_question_correct_option_is_the_authoritative_key(): void
    {
        $question = new Question(['correct_option' => 3]);
        $question->setRelation('options', new Collection([
            new QuestionOption(['option_number' => 1, 'is_correct' => true]),
            new QuestionOption(['option_number' => 3, 'is_correct' => false]),
        ]));

        $this->assertSame(3, $question->correct_option_number);
    }

    public function test_attempt_uses_its_snapshotted_key_after_question_is_edited(): void
    {
        $question = new Question(['correct_option' => 4]);
        $answer = new TypedExamAttemptAnswer([
            'selected_option' => 2,
            'correct_option' => 2,
        ]);
        $answer->setRelation('question', $question);

        $this->assertSame(2, $answer->correctOptionNumber());
    }

    public function test_result_maps_canonical_options_back_to_the_positions_student_saw(): void
    {
        $question = new Question(['correct_option' => 4]);
        $question->setRelation('options', new Collection([
            new QuestionOption(['option_number' => 1, 'content' => 'A']),
            new QuestionOption(['option_number' => 2, 'content' => 'B']),
            new QuestionOption(['option_number' => 3, 'content' => 'C']),
            new QuestionOption(['option_number' => 4, 'content' => 'D']),
        ]));
        $answer = new TypedExamAttemptAnswer([
            'selected_option' => 2,
            'correct_option' => 4,
        ]);

        $component = new class extends TypedExamResult {
            public function mapAnswer($question, $answer, array $order): array
            {
                return $this->formatQuestionAnswer($question, $answer, $order);
            }
        };

        $mapped = $component->mapAnswer($question, $answer, [2, 1, 4, 3]);

        $this->assertSame(1, $mapped['selected_position']);
        $this->assertSame(3, $mapped['correct_position']);
        $this->assertFalse($mapped['is_correct']);
        $this->assertSame([2, 1, 4, 3], $mapped['ordered_options']->pluck('option_number')->all());

        $unanswered = new TypedExamAttemptAnswer([
            'selected_option' => null,
            'correct_option' => 4,
        ]);
        $unansweredMapped = $component->mapAnswer($question, $unanswered, [2, 1, 4, 3]);

        $this->assertNull($unansweredMapped['selected_position']);
        $this->assertNull($unansweredMapped['is_correct']);
    }
}
