<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamAttemptsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exam_attempts')->delete();
        
        \DB::table('exam_attempts')->insert(array (
            0 => 
            array (
                'id' => 8,
                'exam_id' => 10,
                'student_id' => 25,
                'answers' => '{"1":4,"2":null,"3":4,"4":1,"5":3,"6":4,"7":3,"8":4,"9":1,"10":4}',
                'started_at' => '2025-11-12 15:16:47',
                'submitted_at' => '2025-11-12 15:44:17',
                'is_finished' => 0,
                'created_at' => '2025-11-12 15:16:47',
                'updated_at' => '2025-11-12 15:44:17',
            ),
            1 => 
            array (
                'id' => 9,
                'exam_id' => 11,
                'student_id' => 11,
                'answers' => '{"1":4,"2":null,"3":4,"4":1,"5":2,"6":3,"7":4,"8":null,"9":3,"10":null}',
                'started_at' => '2025-11-12 15:56:08',
                'submitted_at' => '2025-11-12 16:15:40',
                'is_finished' => 0,
                'created_at' => '2025-11-12 15:56:08',
                'updated_at' => '2025-11-12 16:15:40',
            ),
            2 => 
            array (
                'id' => 10,
                'exam_id' => 11,
                'student_id' => 2,
                'answers' => '{"1":1,"2":2,"3":2,"4":4,"5":1,"6":3,"7":3,"8":4,"9":1,"10":2}',
                'started_at' => '2025-11-12 16:06:32',
                'submitted_at' => '2025-11-12 16:07:04',
                'is_finished' => 0,
                'created_at' => '2025-11-12 16:06:32',
                'updated_at' => '2025-11-12 16:07:04',
            ),
            3 => 
            array (
                'id' => 12,
                'exam_id' => 12,
                'student_id' => 13,
                'answers' => '{"1":null,"2":2,"3":2,"4":3,"5":3,"6":4,"7":1,"8":3,"9":1,"10":2}',
                'started_at' => '2025-11-12 16:50:45',
                'submitted_at' => '2025-11-12 17:09:07',
                'is_finished' => 0,
                'created_at' => '2025-11-12 16:50:45',
                'updated_at' => '2025-11-12 17:09:07',
            ),
            4 => 
            array (
                'id' => 13,
                'exam_id' => 13,
                'student_id' => 16,
                'answers' => '{"1":1,"2":3,"3":1,"4":null,"5":null,"6":null,"7":4,"8":null,"9":4,"10":null}',
                'started_at' => '2025-11-12 17:28:31',
                'submitted_at' => '2025-11-12 17:57:28',
                'is_finished' => 0,
                'created_at' => '2025-11-12 17:28:31',
                'updated_at' => '2025-11-12 17:57:28',
            ),
            5 => 
            array (
                'id' => 14,
                'exam_id' => 14,
                'student_id' => 15,
                'answers' => '{"1":3,"2":4,"3":3,"4":1,"5":2,"6":2,"7":1,"8":2,"9":3,"10":1}',
                'started_at' => '2025-11-12 18:31:09',
                'submitted_at' => '2025-11-12 18:41:28',
                'is_finished' => 0,
                'created_at' => '2025-11-12 18:31:09',
                'updated_at' => '2025-11-12 18:41:28',
            ),
            6 => 
            array (
                'id' => 16,
                'exam_id' => 15,
                'student_id' => 15,
                'answers' => '{"1":3,"2":3,"3":null,"4":4,"5":2,"6":null,"7":4,"8":null,"9":1,"10":1}',
                'started_at' => '2025-11-12 18:42:14',
                'submitted_at' => '2025-11-12 19:13:16',
                'is_finished' => 0,
                'created_at' => '2025-11-12 18:42:14',
                'updated_at' => '2025-11-12 19:13:16',
            ),
            7 => 
            array (
                'id' => 17,
                'exam_id' => 13,
                'student_id' => 14,
                'answers' => '{"1":1,"2":null,"3":null,"4":1,"5":2,"6":3,"7":2,"8":2,"9":4,"10":null}',
                'started_at' => '2025-11-12 19:00:43',
                'submitted_at' => '2025-11-12 19:23:05',
                'is_finished' => 0,
                'created_at' => '2025-11-12 19:00:43',
                'updated_at' => '2025-11-12 19:23:05',
            ),
            8 => 
            array (
                'id' => 18,
                'exam_id' => 13,
                'student_id' => 10,
                'answers' => '{"1":null,"2":2,"3":3,"4":1,"5":null,"6":null,"7":null,"8":null,"9":3,"10":null}',
                'started_at' => '2025-11-12 19:11:30',
                'submitted_at' => '2025-11-12 19:17:16',
                'is_finished' => 0,
                'created_at' => '2025-11-12 19:11:30',
                'updated_at' => '2025-11-12 19:17:16',
            ),
            9 => 
            array (
                'id' => 19,
                'exam_id' => 10,
                'student_id' => 21,
                'answers' => '{"1":3,"2":null,"3":null,"4":2,"5":2,"6":4,"7":null,"8":null,"9":2,"10":3}',
                'started_at' => '2025-11-13 16:00:19',
                'submitted_at' => '2025-11-13 16:26:48',
                'is_finished' => 0,
                'created_at' => '2025-11-13 16:00:19',
                'updated_at' => '2025-11-13 16:26:48',
            ),
            10 => 
            array (
                'id' => 20,
                'exam_id' => 11,
                'student_id' => 18,
                'answers' => '{"1":2,"2":null,"3":null,"4":null,"5":4,"6":3,"7":null,"8":2,"9":4,"10":null}',
                'started_at' => '2025-11-13 16:13:56',
                'submitted_at' => '2025-11-13 16:32:02',
                'is_finished' => 0,
                'created_at' => '2025-11-13 16:13:56',
                'updated_at' => '2025-11-13 16:32:02',
            ),
            11 => 
            array (
                'id' => 21,
                'exam_id' => 16,
                'student_id' => 17,
                'answers' => NULL,
                'started_at' => '2025-11-13 16:34:55',
                'submitted_at' => NULL,
                'is_finished' => 0,
                'created_at' => '2025-11-13 16:34:55',
                'updated_at' => '2025-11-13 16:34:55',
            ),
            12 => 
            array (
                'id' => 22,
                'exam_id' => 17,
                'student_id' => 12,
                'answers' => '{"1":1,"2":3,"3":1,"4":1,"5":4,"6":3,"7":3,"8":1,"9":1,"10":1}',
                'started_at' => '2025-11-13 16:44:44',
                'submitted_at' => '2025-11-13 16:56:02',
                'is_finished' => 0,
                'created_at' => '2025-11-13 16:44:44',
                'updated_at' => '2025-11-13 16:56:02',
            ),
            13 => 
            array (
                'id' => 24,
                'exam_id' => 13,
                'student_id' => 5,
                'answers' => '{"1":1,"2":null,"3":1,"4":null,"5":3,"6":2,"7":null,"8":null,"9":4,"10":null}',
                'started_at' => '2025-11-15 15:40:29',
                'submitted_at' => '2025-11-15 16:10:30',
                'is_finished' => 0,
                'created_at' => '2025-11-15 15:40:29',
                'updated_at' => '2025-11-15 16:10:30',
            ),
            14 => 
            array (
                'id' => 25,
                'exam_id' => 13,
                'student_id' => 6,
                'answers' => '{"1":4,"2":1,"3":1,"4":null,"5":3,"6":2,"7":1,"8":null,"9":4,"10":3}',
                'started_at' => '2025-11-15 17:14:14',
                'submitted_at' => '2025-11-15 17:44:14',
                'is_finished' => 0,
                'created_at' => '2025-11-15 17:14:14',
                'updated_at' => '2025-11-15 17:44:14',
            ),
            15 => 
            array (
                'id' => 26,
                'exam_id' => 13,
                'student_id' => 7,
                'answers' => '{"1":2,"2":null,"3":1,"4":null,"5":3,"6":2,"7":4,"8":3,"9":4,"10":3}',
                'started_at' => '2025-11-15 17:59:44',
                'submitted_at' => '2025-11-15 18:20:25',
                'is_finished' => 0,
                'created_at' => '2025-11-15 17:59:44',
                'updated_at' => '2025-11-15 18:20:25',
            ),
            16 => 
            array (
                'id' => 27,
                'exam_id' => 18,
                'student_id' => 8,
                'answers' => '{"1":3,"2":4,"3":2,"4":null,"5":2,"6":4,"7":4,"8":3,"9":1,"10":3}',
                'started_at' => '2025-11-15 18:42:13',
                'submitted_at' => '2025-11-15 18:56:28',
                'is_finished' => 0,
                'created_at' => '2025-11-15 18:42:13',
                'updated_at' => '2025-11-15 18:56:28',
            ),
            17 => 
            array (
                'id' => 29,
                'exam_id' => 13,
                'student_id' => 9,
                'answers' => '{"1":4,"2":1,"3":1,"4":null,"5":3,"6":null,"7":null,"8":3,"9":4,"10":3}',
                'started_at' => '2025-11-15 19:16:58',
                'submitted_at' => '2025-11-15 19:46:58',
                'is_finished' => 0,
                'created_at' => '2025-11-15 19:16:58',
                'updated_at' => '2025-11-15 19:46:58',
            ),
            18 => 
            array (
                'id' => 30,
                'exam_id' => 13,
                'student_id' => 26,
                'answers' => '{"1":null,"2":1,"3":1,"4":2,"5":3,"6":2,"7":3,"8":3,"9":4,"10":null}',
                'started_at' => '2025-11-15 19:44:52',
                'submitted_at' => '2025-11-15 20:01:51',
                'is_finished' => 0,
                'created_at' => '2025-11-15 19:44:52',
                'updated_at' => '2025-11-15 20:01:51',
            ),
        ));
        
        
    }
}