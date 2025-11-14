<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'مهدی افراسیابی گولک',
                'email' => 'mr.abbann1976@gmail.com',
                'mobile' => '09940682693',
                'picture' => 'f8eaefc449eb37993f81237cf729819880eaa73d.webp',
                'password' => '$2y$12$l8Rxvlsvm998fTzTqDWZ0OXn3L/cvElj7cyz54PqyBg.w6g2ZR92q',
                'remember_token' => 'hhz16NmimIWQfjbxL86ESAZRzqEKhWhwNlE4ynSVE22NYP2LbiHw9Gh2mVKy',
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => '2025-07-26 23:06:15',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'بهشاد اتقیایی11x7',
                'email' => NULL,
                'mobile' => '09028488061',
                'picture' => NULL,
                'password' => '$2y$12$OEURYRgByb9Gxfr0St0Q.eDddAFkK8XGWMjIs4hxBPEbZnnAaBN.S',
                'remember_token' => 'Vlwsb5bb28lYPksKTdjLmZxWjXNq5P6LsrKhiKoX89V0uq5a3kqyMQF6wndg',
                'deleted_at' => NULL,
                'created_at' => '2025-07-26 20:17:58',
                'updated_at' => '2025-07-26 20:17:58',
            ),
        ));
        
        
    }
}