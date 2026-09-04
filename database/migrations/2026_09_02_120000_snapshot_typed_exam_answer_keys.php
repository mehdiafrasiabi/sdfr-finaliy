<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('typed_exam_attempt_answers', function (Blueprint $table) {
            $table->unsignedTinyInteger('correct_option')
                ->nullable()
                ->after('selected_option');
        });

        /*
         * Older exam pages displayed the four numeric choices in their original
         * order, but saved them through options_order. Convert those saved values
         * back to the number the student actually clicked and freeze the key that
         * was valid at the time of this repair.
         */
        DB::table('typed_exam_attempt_answers')
            ->orderBy('id')
            ->chunkById(500, function ($answers): void {
                $questionIds = $answers->pluck('question_id')->unique()->values();
                $keys = DB::table('questions')
                    ->whereIn('id', $questionIds)
                    ->pluck('correct_option', 'id');
                $fallbackKeys = DB::table('question_options')
                    ->whereIn('question_id', $questionIds)
                    ->where('is_correct', true)
                    ->pluck('option_number', 'question_id');
                $orders = DB::table('typed_exam_student_orders')
                    ->whereIn('attempt_id', $answers->pluck('attempt_id')->unique())
                    ->whereIn('question_id', $questionIds)
                    ->get()
                    ->keyBy(fn ($order) => $order->attempt_id . ':' . $order->question_id);

                foreach ($answers as $answer) {
                    $correctOption = (int) ($keys[$answer->question_id]
                        ?? $fallbackKeys[$answer->question_id]
                        ?? 0);
                    $selectedOption = $answer->selected_option === null
                        ? null
                        : (int) $answer->selected_option;
                    $order = $orders->get($answer->attempt_id . ':' . $answer->question_id);
                    $optionsOrder = $order?->options_order
                        ? array_map('intval', json_decode($order->options_order, true) ?: [])
                        : [];

                    if ($selectedOption !== null && count($optionsOrder) === 4 && $optionsOrder !== [1, 2, 3, 4]) {
                        $clickedPosition = array_search($selectedOption, $optionsOrder, true);

                        if ($clickedPosition !== false) {
                            $selectedOption = $clickedPosition + 1;
                        }
                    }

                    DB::table('typed_exam_attempt_answers')
                        ->where('id', $answer->id)
                        ->update([
                            'selected_option' => $selectedOption,
                            'correct_option' => $correctOption ?: null,
                            'is_correct' => $selectedOption === null
                                ? null
                                : ($correctOption > 0 && $selectedOption === $correctOption),
                        ]);
                }
            });

        // Existing attempts never rendered standalone option content, so their
        // visible numeric positions are the canonical option numbers.
        DB::table('typed_exam_student_orders')->update([
            'options_order' => json_encode([1, 2, 3, 4]),
        ]);

        DB::table('typed_exam_attempts')
            ->where('is_finished', true)
            ->orderBy('id')
            ->chunkById(500, function ($attempts): void {
                foreach ($attempts as $attempt) {
                    $total = DB::table('typed_exam_student_orders')
                        ->where('attempt_id', $attempt->id)
                        ->count();

                    if ($total === 0) {
                        $total = DB::table('typed_exam_attempt_answers')
                            ->where('attempt_id', $attempt->id)
                            ->count();
                    }

                    $correct = DB::table('typed_exam_attempt_answers')
                        ->where('attempt_id', $attempt->id)
                        ->where('is_correct', true)
                        ->count();

                    DB::table('typed_exam_attempts')
                        ->where('id', $attempt->id)
                        ->update(['score' => $total > 0 ? round(($correct / $total) * 100, 2) : 0]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('typed_exam_attempt_answers', function (Blueprint $table) {
            $table->dropColumn('correct_option');
        });
    }
};
