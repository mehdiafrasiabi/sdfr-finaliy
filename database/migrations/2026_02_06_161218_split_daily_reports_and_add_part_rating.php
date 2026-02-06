<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        // 1. Create daily_report_details table
        Schema::create('daily_report_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('phone_hours')->default(0);
            $table->text('description')->nullable();
            $table->tinyInteger('rating')->default(3);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        // 2. Create daily_report_feedbacks table
        Schema::create('daily_report_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            $table->text('advisor_comment')->nullable();
            $table->timestamp('advisor_commented_at')->nullable();
            $table->text('student_reply')->nullable();
            $table->timestamp('student_replied_at')->nullable();
            $table->timestamps();
        });

        // 3. Migrate existing data to new tables
        $reports = DB::table('daily_reports')->get();

        foreach ($reports as $report) {
            DB::table('daily_report_details')->insert([
                'daily_report_id' => $report->id,
                'phone_hours' => $report->phone_hours,
                'description' => $report->description,
                'rating' => $report->rating,
                'status' => $report->status,
                'created_at' => $report->created_at,
                'updated_at' => $report->updated_at,
            ]);

            DB::table('daily_report_feedbacks')->insert([
                'daily_report_id' => $report->id,
                'advisor_comment' => $report->advisor_comment,
                'advisor_commented_at' => $report->advisor_commented_at,
                'student_reply' => $report->student_reply,
                'student_replied_at' => $report->student_replied_at,
                'created_at' => $report->created_at,
                'updated_at' => $report->updated_at,
            ]);
        }

        // 4. Drop moved columns from daily_reports
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropColumn([
                'phone_hours',
                'description',
                'rating',
                'status',
                'advisor_comment',
                'advisor_commented_at',
                'student_reply',
                'student_replied_at',
            ]);
        });

        // 5. Add part_rating to daily_report_parts
        Schema::table('daily_report_parts', function (Blueprint $table) {
            $table->tinyInteger('part_rating')->nullable()->after('tests_done');
        });
    }

    public function down(): void
    {
        // Add columns back to daily_reports
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->tinyInteger('phone_hours')->default(0)->after('day_of_week');
            $table->text('description')->nullable()->after('phone_hours');
            $table->tinyInteger('rating')->default(3)->after('description');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('rating');
            $table->text('advisor_comment')->nullable()->after('status');
            $table->timestamp('advisor_commented_at')->nullable()->after('advisor_comment');
            $table->text('student_reply')->nullable()->after('advisor_commented_at');
            $table->timestamp('student_replied_at')->nullable()->after('student_reply');
        });

        // Migrate data back
        $details = DB::table('daily_report_details')->get();
        foreach ($details as $detail) {
            DB::table('daily_reports')->where('id', $detail->daily_report_id)->update([
                'phone_hours' => $detail->phone_hours,
                'description' => $detail->description,
                'rating' => $detail->rating,
                'status' => $detail->status,
            ]);
        }

        $feedbacks = DB::table('daily_report_feedbacks')->get();
        foreach ($feedbacks as $feedback) {
            DB::table('daily_reports')->where('id', $feedback->daily_report_id)->update([
                'advisor_comment' => $feedback->advisor_comment,
                'advisor_commented_at' => $feedback->advisor_commented_at,
                'student_reply' => $feedback->student_reply,
                'student_replied_at' => $feedback->student_replied_at,
            ]);
        }

        Schema::dropIfExists('daily_report_details');
        Schema::dropIfExists('daily_report_feedbacks');

        Schema::table('daily_report_parts', function (Blueprint $table) {
            $table->dropColumn('part_rating');
        });
    }
};
