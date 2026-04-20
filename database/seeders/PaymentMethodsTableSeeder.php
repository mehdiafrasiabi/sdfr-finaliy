<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('payment_methods')->delete();

        \DB::table('payment_methods')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'Zibal',
                    'merchant_id' => 'Zibal',
                    'active' => 1,
                    'created_at' => '2025-03-30 17:49:47',
                    'updated_at' => '2025-03-30 17:49:50',
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Zarinpal',
                    'merchant_id' => 'Zarinpal',
                    'active' => 0,
                    'created_at' => '2025-03-30 19:50:30',
                    'updated_at' => '2025-03-30 19:50:30',
                ),
        ));


    }
}
