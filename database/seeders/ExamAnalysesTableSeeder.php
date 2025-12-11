<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamAnalysesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exam_analyses')->delete();
        
        \DB::table('exam_analyses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'exam_id' => 11,
                'student_id' => 11,
                'image_path' => 'exams/students/11/examAnalysis/QOsQHWl0FDIytk55JncE2oFUkt2Ts3o9tc1XuXOD.webp',
                'created_at' => '2025-11-12 16:58:59',
                'updated_at' => '2025-11-12 16:58:59',
            ),
            1 => 
            array (
                'id' => 2,
                'exam_id' => 13,
                'student_id' => 16,
                'image_path' => 'exams/students/16/examAnalysis/8VoFpoMqkyI3TIuV7OvtPi8l7cKWuTaqNeM3QjLj.webp',
                'created_at' => '2025-11-12 23:24:51',
                'updated_at' => '2025-11-12 23:24:51',
            ),
            2 => 
            array (
                'id' => 3,
                'exam_id' => 11,
                'student_id' => 2,
                'image_path' => '["exam\\/students\\/2\\/examAnalysis\\/6915e3d3a4e00-SaftehPersonal.webp","exam\\/students\\/2\\/examAnalysis\\/6915e3d3c6145-photo_2025-11-12_17-31-22.webp","exam\\/students\\/2\\/examAnalysis\\/6915e3d3ef040-photo_2025-11-12_17-26-11.webp"]',
                'created_at' => '2025-11-13 17:27:40',
                'updated_at' => '2025-11-13 17:27:40',
            ),
            3 => 
            array (
                'id' => 4,
                'exam_id' => 13,
                'student_id' => 14,
                'image_path' => '["exam\\/students\\/14\\/examAnalysis\\/69162ea958125-1763061379592280819910438474099.webp"]',
                'created_at' => '2025-11-13 22:46:58',
                'updated_at' => '2025-11-13 22:46:58',
            ),
            4 => 
            array (
                'id' => 5,
                'exam_id' => 18,
                'student_id' => 8,
                'image_path' => '["exam\\/students\\/8\\/examAnalysis\\/69189e92c9350-1000026738.webp"]',
                'created_at' => '2025-11-15 19:08:59',
                'updated_at' => '2025-11-15 19:08:59',
            ),
            5 => 
            array (
                'id' => 6,
                'exam_id' => 13,
                'student_id' => 9,
                'image_path' => '["exam\\/students\\/9\\/examAnalysis\\/6918beda53d8e-IMG_20251115_212540.webp"]',
                'created_at' => '2025-11-15 21:26:43',
                'updated_at' => '2025-11-15 21:26:43',
            ),
        ));
        
        
    }
}