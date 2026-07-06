<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'installment_ids')) {
                $table->json('installment_ids')->nullable()->after('installment_id');
            }
        });

        // Add 'installment_bulk' to the purpose enum
        DB::statement("ALTER TABLE payments MODIFY COLUMN purpose ENUM('course_full','installment_initial','installment', 'installment_bulk')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }
        
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'installment_ids')) {
                $table->dropColumn('installment_ids');
            }
        });
        
        // Revert enum change
        DB::statement("ALTER TABLE payments MODIFY COLUMN purpose ENUM('course_full','installment_initial','installment')");
    }
};
