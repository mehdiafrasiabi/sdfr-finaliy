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

        Schema::table('orders', function (Blueprint $table) {

            $table->boolean('paid_with_wallet')->default(false)->after('status');

            $table->unsignedBigInteger('wallet_amount')->default(0)->after('paid_with_wallet');

        });

    }


    /**
     * Reverse the migrations.
     */

    public function down(): void

    {

        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn(['paid_with_wallet', 'wallet_amount']);

        });

    }
};
