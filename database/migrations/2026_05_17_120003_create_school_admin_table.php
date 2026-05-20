<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_admin', function (Blueprint $table) {
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['school_id', 'admin_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_admin');
    }
};
