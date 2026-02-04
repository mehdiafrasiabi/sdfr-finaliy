<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('stories')->delete();
        
        \DB::table('stories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'admin_id' => 1,
                'title' => 'کاهش بی دقتی',
                'type' => 'video',
                'thumbnail' => 'WGAq2kLaKHUBBcfmdMKZFBUfvej5qHlxqaoiDHew.webp',
                'story' => 'https://sdfr.me/stories/story/VvvHeHMuWQRz5btdnGqqNqLjSawrsOTsFf3d8WnM.mp4',
                'status' => 1,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 13:55:27',
                'updated_at' => '2026-02-04 13:56:12',
            ),
            1 => 
            array (
                'id' => 2,
                'admin_id' => 1,
                'title' => 'درس عمومی رو رها نکن !',
                'type' => 'video',
                'thumbnail' => '2In8aKgVjGdqIUNzs4u4C1XbavISTNKryG4lUszw.webp',
                'story' => 'https://sdfr.me/stories/story/KpQ5hgt4znrFdHrlG7G4QbOZue2kgOqn7Oyz6tWm.mp4',
                'status' => 1,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 13:57:52',
                'updated_at' => '2026-02-04 14:17:59',
            ),
            2 => 
            array (
                'id' => 3,
                'admin_id' => 1,
                'title' => 'پایبندی به قوانین SDFR',
                'type' => 'video',
                'thumbnail' => 'tmDgS5hoT9mjk3rVNO4dIYBrLXkpI7P4CVd3pY9H.webp',
                'story' => 'https://sdfr.me/stories/story/sSD2gPeZOanQQPW8K8pIhQpQmal258ki3St1dXlb.mp4',
                'status' => 1,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 13:59:47',
                'updated_at' => '2026-02-04 14:17:56',
            ),
            3 => 
            array (
                'id' => 4,
                'admin_id' => 1,
                'title' => 'بنازم پاداش 😂🥇',
                'type' => 'video',
                'thumbnail' => '2LqgyO0MP2x1E7MqinDNVPsEM83lZ35qWdojbLXw.webp',
                'story' => 'http://127.0.0.1:8000/stories/story/AQPC9JzDjY8Htqu5Sbr6vuPv7MmiFvz4WPJo9Q55nEA-cifW0k9emZfyDBGkZI2E9qN4JUCtmBXrY30VaQWrhDzChoyUH6vzQWjs6NU.mp4',
                'status' => 1,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 14:06:00',
                'updated_at' => '2026-02-04 14:17:53',
            ),
            4 => 
            array (
                'id' => 5,
                'admin_id' => 1,
                'title' => 'رکورد تست هفته شکسته شد !',
                'type' => 'image',
                'thumbnail' => '4LpfUtdxek9xF7taQ30ZRJ7OgEyVA0JVJRATsO7g.webp',
                'story' => '1JHRAAx7OkZGOnBkgmIjpJC1RBYvPBRh7ePDw5qn.webp',
                'status' => 1,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 14:11:23',
                'updated_at' => '2026-02-04 14:17:48',
            ),
            5 => 
            array (
                'id' => 6,
                'admin_id' => 1,
                'title' => 'میلاد یا ...؟',
                'type' => 'video',
                'thumbnail' => 'iKby5aMnwiNQxQ23FmpgZPN1q5GGr1KaQex8CpjM.webp',
                'story' => 'http://127.0.0.1:8000/stories/story/AQMDUjIjK7LXMUDsOpYf9I6EVXcrSdWQiKVLYa0inamMVfbo-Lef03NvyRiEPmXI5zdE2o1ZHeqqa219-nL4eFiik1HKHG89Wt2mlag.mp4',
                'status' => 1,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 14:12:45',
                'updated_at' => '2026-02-04 14:17:41',
            ),
            6 => 
            array (
                'id' => 7,
                'admin_id' => 1,
                'title' => 'چرک نویس تمیز !',
                'type' => 'video',
                'thumbnail' => 'S0CcHEXwT27py9i6HTM3WINnrXvk1i8c3y2zjd3m.webp',
                'story' => 'http://127.0.0.1:8000/stories/story/AQP928-olh_Ds3N7mHN1ISQnngP9UQVvQEHAbuX1BxJRSsUekieQ0JWEShAIXOPFw-sxXcSJPt1DBH2NJwV-DFO9aZlB9mBrz4OfxUE.mp4',
                'status' => 1,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 14:14:06',
                'updated_at' => '2026-02-04 14:17:37',
            ),
            7 => 
            array (
                'id' => 8,
                'admin_id' => 1,
                'title' => 'بزودی !',
                'type' => 'video',
                'thumbnail' => 'NwoZ55SDFXLKT5ir4ihxiYKjxjtn7JmC8NrnfpJV.webp',
                'story' => 'http://127.0.0.1:8000/stories/story/313476893355adcfb56d0d12bf5ad2bb68179263-1080p.mp4',
                'status' => 1,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 14:17:25',
                'updated_at' => '2026-02-04 14:17:32',
            ),
            8 => 
            array (
                'id' => 9,
                'admin_id' => 1,
                'title' => 'تحلیل آزمون خفن!',
                'type' => 'video',
                'thumbnail' => 'YjpPaaOH2S37FqaGDfkpkxA2PzbYCxakhKxtDZ15.webp',
                'story' => 'http://127.0.0.1:8000/stories/story/StorySaver.net-atghiaeee-Video-1770202495550.mp4',
                'status' => 1,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 14:27:00',
                'updated_at' => '2026-02-04 14:28:17',
            ),
            9 => 
            array (
                'id' => 10,
                'admin_id' => 1,
                'title' => 'پسری ک اینقدر درس میخونه چیه ؟',
                'type' => 'video',
                'thumbnail' => 's133x6egRxfxDIRPWN1TwDK1bpVNYNBIIttrrd2Z.webp',
                'story' => 'http://127.0.0.1:8000/stories/story/StorySaver.net-atghiaeee-Video-1770202518738.mp4',
                'status' => 1,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 14:28:09',
                'updated_at' => '2026-02-04 14:28:22',
            ),
            10 => 
            array (
                'id' => 11,
                'admin_id' => 1,
                'title' => 'کودتاگر باشههه!!',
                'type' => 'image',
                'thumbnail' => 'gBqqcPBZtd77aTYxm14RjUK8xbVfkavGXSBiSTPf.webp',
                'story' => 'c7IplkM6dO4cfVXomcWX9zJodjpeRjhBf75nV7Z3.webp',
                'status' => 0,
                'expires_at' => '2026-02-28 00:00:00',
                'widget_title' => NULL,
                'widget_link' => NULL,
                'created_at' => '2026-02-04 14:28:59',
                'updated_at' => '2026-02-04 14:30:07',
            ),
        ));
        
        
    }
}