<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('grade_prices')
            ->whereNotNull('start_at')
            ->orderBy('id')
            ->get()
            ->each(function ($price) {
                $serviceYear = (int) Jalalian::fromCarbon(Carbon::parse($price->start_at))->getYear();
                $endAt = Jalalian::fromFormat('Y/m/d', sprintf('%d/03/31', $serviceYear + 1))
                    ->toCarbon()
                    ->endOfDay();

                DB::table('grade_prices')
                    ->where('id', $price->id)
                    ->update(['end_at' => $endAt->toDateString()]);
            });
    }

    public function down(): void
    {
        DB::table('grade_prices')
            ->whereNotNull('start_at')
            ->orderBy('id')
            ->get()
            ->each(function ($price) {
                $serviceYear = (int) Jalalian::fromCarbon(Carbon::parse($price->start_at))->getYear();
                $endAt = Jalalian::fromFormat('Y/m/d', sprintf('%d/12/29', $serviceYear))
                    ->toCarbon()
                    ->endOfDay();

                DB::table('grade_prices')
                    ->where('id', $price->id)
                    ->update(['end_at' => $endAt->toDateString()]);
            });
    }
};
