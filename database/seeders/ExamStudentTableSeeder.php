<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamStudentTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exam_student')->delete();
        
        \DB::table('exam_student')->insert(array (
            0 => 
            array (
                'id' => 12,
                'exam_id' => 10,
                'student_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 13,
                'exam_id' => 11,
                'student_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 14,
                'exam_id' => 11,
                'student_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 16,
                'exam_id' => 12,
                'student_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 17,
                'exam_id' => 13,
                'student_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 18,
                'exam_id' => 14,
                'student_id' => 15,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 19,
                'exam_id' => 15,
                'student_id' => 15,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 21,
                'exam_id' => 13,
                'student_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 22,
                'exam_id' => 13,
                'student_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 23,
                'exam_id' => 10,
                'student_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 24,
                'exam_id' => 11,
                'student_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 25,
                'exam_id' => 16,
                'student_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 26,
                'exam_id' => 17,
                'student_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 28,
                'exam_id' => 13,
                'student_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 29,
                'exam_id' => 13,
                'student_id' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 30,
                'exam_id' => 13,
                'student_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 31,
                'exam_id' => 18,
                'student_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 32,
                'exam_id' => 13,
                'student_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 34,
                'exam_id' => 13,
                'student_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}