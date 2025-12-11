<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exams')->delete();
        
        \DB::table('exams')->insert(array (
            0 => 
            array (
                'id' => 10,
                'title' => 'هندسه1 فصل1',
                'level' => 'easy',
                'number_of_questions' => 10,
                'is_active' => 1,
                'pdf_path' => 'exam/823e74242cfa9fa7bce807bdcd32cf62/questions/gPfivhgui5Fn2O9R8lRdcJjH8pRusA6285bCkUUy.pdf',
                'solution_pdf_path' => 'exam/823e74242cfa9fa7bce807bdcd32cf62/solutions/u9uXCTET5nHHGrskIogkJOvhq68HQAJV0MXStUJi.pdf',
                'duration_minutes' => 30,
                'admin_id' => 1,
                'created_at' => '2025-11-12 15:15:01',
                'updated_at' => '2025-11-12 15:15:52',
            ),
            1 => 
            array (
                'id' => 11,
                'title' => 'زیست3 فصل1',
                'level' => 'medium',
                'number_of_questions' => 10,
                'is_active' => 1,
                'pdf_path' => 'exam/d361d6fb9648cd29bec67921521f0f04/questions/uv2DlQjSvCAqDDEhlXeROMB9b5vf20x0Cd92BOXU.pdf',
                'solution_pdf_path' => 'exam/d361d6fb9648cd29bec67921521f0f04/solutions/rAczdrFcrRxrQAeTGeWf1KxLy6STAb8XrXyBfrSk.pdf',
                'duration_minutes' => 30,
                'admin_id' => 1,
                'created_at' => '2025-11-12 15:55:28',
                'updated_at' => '2025-11-12 15:55:41',
            ),
            2 => 
            array (
                'id' => 12,
                'title' => 'زیست1 فصل1',
                'level' => 'easy',
                'number_of_questions' => 10,
                'is_active' => 1,
                'pdf_path' => 'exam/0a18c9c30ccdf1902c52d1af6b74402d/questions/tPZ5kBuIR4G7JagHqiGkgKTjpvbjdEaXlaGiuyZ9.pdf',
                'solution_pdf_path' => 'exam/0a18c9c30ccdf1902c52d1af6b74402d/solutions/hQlVgiWCxzur7PY0gDUtFAh3Es1vm6qynHYE4Z5B.pdf',
                'duration_minutes' => 30,
                'admin_id' => 1,
                'created_at' => '2025-11-12 16:50:16',
                'updated_at' => '2025-11-12 16:50:29',
            ),
            3 => 
            array (
                'id' => 13,
                'title' => 'ریاضی1 فصل1',
                'level' => 'easy',
                'number_of_questions' => 10,
                'is_active' => 1,
                'pdf_path' => 'exam/7416dc51542198fc83ae0845a6d84141/questions/jfny54wYWGkCVnIgpbqf3WKeAb4ejrGSAuioNNfV.pdf',
                'solution_pdf_path' => 'exam/7416dc51542198fc83ae0845a6d84141/solutions/GkzINLdPTHgkvmxH1jKgGePZYkbpNhKi0DTvUmwW.pdf',
                'duration_minutes' => 30,
                'admin_id' => 1,
                'created_at' => '2025-11-12 17:27:41',
                'updated_at' => '2025-11-12 17:27:52',
            ),
            4 => 
            array (
                'id' => 14,
                'title' => 'فیزیک1 فصل2',
                'level' => 'easy',
                'number_of_questions' => 10,
                'is_active' => 1,
                'pdf_path' => 'exam/e5a9f00a266e4bab1a014bf215b61d35/questions/kWeAC6MkvAJPwfJSsyeDKVgGrB8soaOZVz03iI7E.pdf',
                'solution_pdf_path' => 'exam/e5a9f00a266e4bab1a014bf215b61d35/solutions/ycLd7rGIWT3qJS9H1my61vvm6mjWHiqFAROP4ibc.pdf',
                'duration_minutes' => 30,
                'admin_id' => 1,
                'created_at' => '2025-11-12 18:27:10',
                'updated_at' => '2025-11-12 18:28:45',
            ),
            5 => 
            array (
                'id' => 15,
                'title' => 'فیزیک1 فصل2',
                'level' => 'medium',
                'number_of_questions' => 10,
                'is_active' => 1,
                'pdf_path' => 'exam/fb3da9be77598945f7f1998d5b0ab0ce/questions/DknZdhTo5eepbp1y9xB47J6qBVgD8Qtt3vSQcotW.pdf',
                'solution_pdf_path' => 'exam/fb3da9be77598945f7f1998d5b0ab0ce/solutions/HyawqpNWN6nbKVl9nrdd2849Ga6xvFAgXhCvZ8qv.pdf',
                'duration_minutes' => 30,
                'admin_id' => 1,
                'created_at' => '2025-11-12 18:28:23',
                'updated_at' => '2025-11-12 18:28:43',
            ),
            6 => 
            array (
                'id' => 16,
            'title' => 'تاریخ3 درس1 (تشریحی)',
                'level' => 'easy',
                'number_of_questions' => 1,
                'is_active' => 1,
                'pdf_path' => 'exam/ad61fd00f6176af521d897f2bfe34269/questions/NCuceeb0Q99cTk9eflkIi87z1P64Z91dkTypDgm4.pdf',
                'solution_pdf_path' => NULL,
                'duration_minutes' => 75,
                'admin_id' => 1,
                'created_at' => '2025-11-13 16:33:32',
                'updated_at' => '2025-11-13 16:33:43',
            ),
            7 => 
            array (
                'id' => 17,
                'title' => 'زیست1 فصل2',
                'level' => 'easy',
                'number_of_questions' => 10,
                'is_active' => 1,
                'pdf_path' => 'exam/0c7da5eb3af6ee08e43a3e0744310218/questions/V2EOQDxgfbQTPYo9pumeZL3V4gypTMy0xkK5xz4u.pdf',
                'solution_pdf_path' => 'exam/0c7da5eb3af6ee08e43a3e0744310218/solutions/AsItNNkdhF34IaVE65L6K6K6joEyVKL6RDo2TWf1.pdf',
                'duration_minutes' => 30,
                'admin_id' => 1,
                'created_at' => '2025-11-13 16:43:15',
                'updated_at' => '2025-11-13 16:43:26',
            ),
            8 => 
            array (
                'id' => 18,
                'title' => 'زیست1 فصل1',
                'level' => 'medium',
                'number_of_questions' => 10,
                'is_active' => 1,
                'pdf_path' => 'exam/101c81a602e9fcd2da204d64548c4bd9/questions/R4LuHPIguz1DucgDTGEREriFsdmRDO85haFyHJTo.pdf',
                'solution_pdf_path' => 'exam/101c81a602e9fcd2da204d64548c4bd9/solutions/mCpv6WHu3j0zbnE7frVA72djDcKcuZ5NfRL22D4D.pdf',
                'duration_minutes' => 30,
                'admin_id' => 1,
                'created_at' => '2025-11-15 18:37:39',
                'updated_at' => '2025-11-15 18:41:36',
            ),
        ));
        
        
    }
}