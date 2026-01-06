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
        Schema::create('gift_codes', function (Blueprint $table) {

            $table->id();

            $table->string('code')->unique();

            $table->enum('type', ['for_all', 'for_one']);

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            $table->unsignedInteger('usage_limit')->default(1);

            $table->unsignedInteger('usage_count')->default(0);

            $table->unsignedBigInteger('amount');

            $table->date('expires_at');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_codes');
    }
};
