<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(database_path('seeders/data/provinces_and_cities.json')), true);

        foreach ($data as $entry) {
            $provinceName = $entry['state'];
            $provinceId = DB::table('states')->where('name', $provinceName)->value('id');

            if ($provinceId && isset($entry['cities'])) {
                foreach ($entry['cities'] as $cityName) {
                    DB::table('cities')->insert([
                        'state_id' => $provinceId,
                        'name' => $cityName,
                    ]);
                }
            }
        }

    }
}
