<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->enum('role', ['manager', 'deputy']);
            $table->string('name');
            $table->string('phone');
            $table->timestamps();

            $table->unique(['school_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_staff');
    }
};
