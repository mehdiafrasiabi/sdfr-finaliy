<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BlogImagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('blog_images')->delete();
        
        \DB::table('blog_images')->insert(array (
            0 => 
            array (
                'id' => 1,
                'path' => 'KpE9RAUPaYJy2j98SabQ9RRnaYKkQHqalg1oc0LN.webp',
                'blog_id' => 1,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:50:09',
                'updated_at' => '2025-08-17 17:50:09',
            ),
            1 => 
            array (
                'id' => 2,
                'path' => 'qPIPBf8LKGBF5Db69hpcLZAsWxm0yHXUpaKSVaCL.webp',
                'blog_id' => 2,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:52:14',
                'updated_at' => '2025-08-17 17:52:14',
            ),
            2 => 
            array (
                'id' => 3,
                'path' => 'JecURVaeUpM6FSerbR9qDwJB3wusWNyWDdSCFwCS.webp',
                'blog_id' => 3,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:53:21',
                'updated_at' => '2025-08-17 17:53:21',
            ),
            3 => 
            array (
                'id' => 4,
                'path' => 'Qtct3bRGXXFjvpoYM35J6aL3fi9XioMJ1B5S9MWi.webp',
                'blog_id' => 4,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 17:54:41',
                'updated_at' => '2025-08-17 17:54:41',
            ),
        ));
        
        
    }
}