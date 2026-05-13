<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'supporter_id')) {
                try { $table->dropForeign(['supporter_id']); } catch (\Throwable $e) {}
                $table->dropColumn('supporter_id');
            }
            if (Schema::hasColumn('students', 'product_id')) {
                try { $table->dropForeign(['product_id']); } catch (\Throwable $e) {}
                $table->dropColumn('product_id');
            }
            if (!Schema::hasColumn('students', 'grade_price_id')) {
                $table->foreignId('grade_price_id')->nullable()->after('payment_id')->constrained('grade_prices')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'grade_price_id')) {
                try { $table->dropForeign(['grade_price_id']); } catch (\Throwable $e) {}
                $table->dropColumn('grade_price_id');
            }
            $table->foreignId('supporter_id')->nullable()->constrained('admins');
            $table->foreignId('product_id')->nullable()->constrained();
        });
    }
};
