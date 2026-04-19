<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamCountdownEventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exam_countdown_events')->delete();
        
        \DB::table('exam_countdown_events')->insert(array (
            0 => 
            array (
                'id' => 10,
                'exam_countdown_setting_id' => 1,
                'event_title' => 'مهلت ثبت‌نام',
                'event_date' => '2026-03-19',
                'sort_order' => 0,
                'created_at' => '2026-04-12 19:43:56',
                'updated_at' => '2026-04-12 19:43:56',
            ),
            1 => 
            array (
                'id' => 11,
                'exam_countdown_setting_id' => 1,
                'event_title' => 'زمان برگزاری کنکور تجربی',
                'event_date' => '2026-07-03',
                'sort_order' => 1,
                'created_at' => '2026-04-12 19:43:56',
                'updated_at' => '2026-04-12 19:43:56',
            ),
            2 => 
            array (
                'id' => 12,
                'exam_countdown_setting_id' => 1,
                'event_title' => 'زمان برگزاری کنکور ریاضی',
                'event_date' => '2026-07-02',
                'sort_order' => 2,
                'created_at' => '2026-04-12 19:43:56',
                'updated_at' => '2026-04-12 19:43:56',
            ),
            3 => 
            array (
                'id' => 13,
                'exam_countdown_setting_id' => 1,
                'event_title' => 'زمان برگزاری کنکور انسانی',
                'event_date' => '2026-07-02',
                'sort_order' => 3,
                'created_at' => '2026-04-12 19:43:56',
                'updated_at' => '2026-04-12 19:43:56',
            ),
        ));
        
        
    }
}