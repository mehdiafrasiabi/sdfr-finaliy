<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_parent_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->enum('contacted_with', ['father', 'mother']);
            $table->text('notes')->nullable();
            $table->timestamp('contacted_at');
            $table->timestamps();

            $table->index(['student_id', 'contacted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_parent_contacts');
    }
};
