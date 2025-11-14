<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_documentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('admins')->onDelete('cascade');     // کسی که فایل رو فرستاده
            $table->foreignId('receiver_id')->constrained('admins')->onDelete('cascade');   // سوپرادمین گیرنده
            $table->string('file_path');   // مسیر فایل
            $table->text('message')->nullable(); // پیام اختیاری
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_documentations');
    }
};
