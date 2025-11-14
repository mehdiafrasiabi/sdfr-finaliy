<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PersonalInformationTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('personal_information')->delete();
        
        \DB::table('personal_information')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'مهدی افراسیابی گولک',
                'father_name' => 'فریبرز',
                'code_mell' => '0928767256',
                'place_of_birth' => 'مشهد',
                'father_mobile' => '09940682693',
                'mother_mobile' => '09940682693',
                'birth_date' => '2025-03-13 00:00:00',
                'address' => 'مشهد-بلوار توس- توس70-امام زمان7-پلاک28-واحد8',
                'state_id' => 11,
                'city_id' => 418,
                'user_id' => 1,
                'created_at' => '2025-07-24 15:38:43',
                'updated_at' => '2025-07-24 15:38:43',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'بهشاد اتقیایی',
                'father_name' => 'تست 1',
                'code_mell' => '5179893585',
                'place_of_birth' => 'بجنورد',
                'father_mobile' => '09999999999',
                'mother_mobile' => '09126656595',
                'birth_date' => '1945-08-09 00:00:00',
                'address' => 'دانش اموز دانش اموز8',
                'state_id' => 12,
                'city_id' => 441,
                'user_id' => 2,
                'created_at' => '2025-07-26 20:19:57',
                'updated_at' => '2025-07-26 20:19:57',
            ),
        ));
        
        
    }
}