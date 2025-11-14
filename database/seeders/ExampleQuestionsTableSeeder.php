<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExampleQuestionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('example_questions')->delete();
        
        \DB::table('example_questions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'آزمون مدیرت خانواده',
                'image_path' => 'blog/example-question/1/images/vqftTAXJcJ86qEk7MsMt4gcrLWL5RJ6P1LKfmGCa.webp',
                'file_path' => 'blog/exam-files/1/137743_PDF_Gama.ir_U0PwNK.pdf',
                'exam_category_id' => 7,
                'created_at' => '2025-08-18 01:08:38',
                'updated_at' => '2025-08-18 01:08:40',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'درسنامه هندسه 3',
                'image_path' => 'blog/example-question/2/images/x3bCRpIAvFiI43dA57Z5YSUXMy7PbwPq2qUFsYaO.webp',
                'file_path' => 'blog/exam-files/2/137455_PDF_Gama.ir_1QxIbE.pdf',
                'exam_category_id' => 7,
                'created_at' => '2025-08-18 01:10:14',
                'updated_at' => '2025-08-18 01:10:14',
            ),
        ));
        
        
    }
}