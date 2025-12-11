<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void

    {

        // جدول دروس

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // نام درس
            $table->enum('type', ['general', 'specialized'])->default('specialized'); // عمومی یا تخصصی
            $table->enum('grade', ['10', '11', '12'])->nullable(); // پایه
            $table->enum('field', ['math', 'experimental', 'human'])->nullable(); // رشته
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // داده‌های اولیه دروس

        \DB::table('lessons')->insert([
            // دروس تخصصی ریاضی
            ['name' => 'ریاضی۱', 'type' => 'specialized', 'grade' => '10', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ریاضی۲', 'type' => 'specialized', 'grade' => '11', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ریاضی۳', 'type' => 'specialized', 'grade' => '12', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'هندسه۱', 'type' => 'specialized', 'grade' => '10', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'هندسه۲', 'type' => 'specialized', 'grade' => '11', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'هندسه۳', 'type' => 'specialized', 'grade' => '12', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'فیزیک۱', 'type' => 'specialized', 'grade' => '10', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'فیزیک۲', 'type' => 'specialized', 'grade' => '11', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'فیزیک۳', 'type' => 'specialized', 'grade' => '12', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'شیمی۱', 'type' => 'specialized', 'grade' => '10', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'شیمی۲', 'type' => 'specialized', 'grade' => '11', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'شیمی۳', 'type' => 'specialized', 'grade' => '12', 'field' => 'math', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            // دروس تخصصی تجربی
            ['name' => 'زیست۱', 'type' => 'specialized', 'grade' => '10', 'field' => 'experimental', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'زیست۲', 'type' => 'specialized', 'grade' => '11', 'field' => 'experimental', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'زیست۳', 'type' => 'specialized', 'grade' => '12', 'field' => 'experimental', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // دروس عمومی
            ['name' => 'ادبیات فارسی', 'type' => 'general', 'grade' => null, 'field' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'عربی', 'type' => 'general', 'grade' => null, 'field' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'دین و زندگی', 'type' => 'general', 'grade' => null, 'field' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'زبان انگلیسی', 'type' => 'general', 'grade' => null, 'field' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
