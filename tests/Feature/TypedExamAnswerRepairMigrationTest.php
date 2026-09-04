<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TypedExamAnswerRepairMigrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Schema::create('questions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedTinyInteger('correct_option')->nullable();
        });
        Schema::create('question_options', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->unsignedTinyInteger('option_number');
            $table->boolean('is_correct')->default(false);
        });
        Schema::create('typed_exam_attempts', function (Blueprint $table): void {
            $table->id();
            $table->boolean('is_finished')->default(false);
            $table->decimal('score', 5, 2)->nullable();
        });
        Schema::create('typed_exam_student_orders', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('question_id');
            $table->json('options_order')->nullable();
        });
        Schema::create('typed_exam_attempt_answers', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedTinyInteger('selected_option')->nullable();
            $table->boolean('is_correct')->nullable();
        });
    }

    public function test_migration_repairs_old_display_position_and_score(): void
    {
        DB::table('questions')->insert(['id' => 10, 'correct_option' => 1]);
        DB::table('typed_exam_attempts')->insert(['id' => 20, 'is_finished' => true, 'score' => 0]);
        DB::table('typed_exam_student_orders')->insert([
            'attempt_id' => 20,
            'question_id' => 10,
            'options_order' => json_encode([2, 4, 1, 3]),
        ]);
        DB::table('typed_exam_attempt_answers')->insert([
            'attempt_id' => 20,
            'question_id' => 10,
            // The old code stored canonical option 2 when the student clicked position 1.
            'selected_option' => 2,
            'is_correct' => false,
        ]);

        $migration = require database_path('migrations/2026_09_02_120000_snapshot_typed_exam_answer_keys.php');
        $migration->up();

        $answer = DB::table('typed_exam_attempt_answers')->first();
        $order = DB::table('typed_exam_student_orders')->first();
        $attempt = DB::table('typed_exam_attempts')->first();

        $this->assertSame(1, (int) $answer->selected_option);
        $this->assertSame(1, (int) $answer->correct_option);
        $this->assertSame(1, (int) $answer->is_correct);
        $this->assertSame([1, 2, 3, 4], json_decode($order->options_order, true));
        $this->assertSame(100.0, (float) $attempt->score);
    }
}
