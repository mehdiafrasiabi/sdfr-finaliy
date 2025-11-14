<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BlogSeoItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('blog_seo_items')->delete();
        
        \DB::table('blog_seo_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'slug' => 'معرفی-رشته-روانشناسی',
                'meta_title' => 'معرفی رشته روانشناسی',
                'meta_description' => 'معرفی رشته روانشناسی',
                'ref_id' => 1,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:50:09',
                'updated_at' => '2025-08-17 17:50:09',
            ),
            1 => 
            array (
                'id' => 2,
                'slug' => 'معرفی-رشته-مهندسی-کامپیوتر',
                'meta_title' => 'معرفی رشته مهندسی کامپیوتر',
                'meta_description' => 'معرفی رشته مهندسی کامپیوتر',
                'ref_id' => 2,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:52:14',
                'updated_at' => '2025-08-17 17:52:14',
            ),
            2 => 
            array (
                'id' => 3,
                'slug' => 'معرفی-رشته-دندان-پزشکی',
                'meta_title' => 'معرفی رشته دندان پزشکی',
                'meta_description' => 'معرفی رشته دندان پزشکی',
                'ref_id' => 3,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:53:21',
                'updated_at' => '2025-08-17 17:53:21',
            ),
            3 => 
            array (
                'id' => 4,
                'slug' => 'معرفی-رشته-مهندسی-مکانیک',
                'meta_title' => 'معرفی رشته مهندسی مکانیک',
                'meta_description' => 'معرفی رشته مهندسی مکانیک',
                'ref_id' => 4,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:54:41',
                'updated_at' => '2025-08-17 17:54:41',
            ),
        ));
        
        
    }
}