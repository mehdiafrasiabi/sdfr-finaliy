<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BlogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('blogs')->delete();
        
        \DB::table('blogs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'معرفی رشته روانشناسی',
                'description' => '<p><span style="color:#ecf0f1"><strong><span style="background-color:#c0392b">بزودی!!!!!!!!!</span></strong></span></p>
',
                'study_time' => '20',
                'category_id' => 1,
                'blog_code' => 'SDFR-66222',
                'status' => 'completed',
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:50:09',
                'updated_at' => '2025-08-17 17:56:05',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'معرفی رشته مهندسی کامپیوتر',
                'description' => '<p><span style="color:#ecf0f1"><strong><span style="background-color:#c0392b">بزودی!!!!!!!!!!!!</span></strong></span></p>
',
                'study_time' => '10',
                'category_id' => 1,
                'blog_code' => 'SDFR-94260',
                'status' => 'completed',
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:52:14',
                'updated_at' => '2025-08-17 17:56:00',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'معرفی رشته دندان پزشکی',
                'description' => '<p><strong><span style="color:#ecf0f1"><span style="background-color:#c0392b">بزودی!!!!!!!!</span></span></strong></p>
',
                'study_time' => '15',
                'category_id' => 1,
                'blog_code' => 'SDFR-45021',
                'status' => 'completed',
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:53:21',
                'updated_at' => '2025-08-17 17:55:55',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'معرفی رشته مهندسی مکانیک',
                'description' => '<p><span style="color:#ecf0f1"><strong><span style="background-color:#c0392b">بزودی!!!!!!!</span></strong></span></p>
',
                'study_time' => '12',
                'category_id' => 1,
                'blog_code' => 'SDFR-64215',
                'status' => 'completed',
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:54:41',
                'updated_at' => '2025-08-17 17:55:50',
            ),
        ));
        
        
    }
}