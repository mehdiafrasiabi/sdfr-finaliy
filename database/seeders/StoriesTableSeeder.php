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
                'id' => 3,
                'title' => 'کاهش بی دقتی',
                'thumbnail' => 'Tc7Ak5tsUL6mGHBD88FFhqMJHLzST7aSEQYU069T.jpg',
                'story' => 'VvvHeHMuWQRz5btdnGqqNqLjSawrsOTsFf3d8WnM.mp4',
                'status' => 1,
                'created_at' => '2025-10-13 12:38:05',
                'updated_at' => '2025-10-13 12:38:10',
            ),
            1 => 
            array (
                'id' => 4,
                'title' => 'تحلیل آزموون',
                'thumbnail' => 'kycr2dggikqRoRleiu9bQ2q8vzrT1aoyhUZc2V4Q.jpg',
                'story' => 'HsyFT7hvwDJ2ayA4nzV5GyGABJhR6vtaT6KE5oqs.mp4',
                'status' => 1,
                'created_at' => '2025-10-13 13:13:00',
                'updated_at' => '2025-10-13 13:13:08',
            ),
            2 => 
            array (
                'id' => 5,
                'title' => 'چرک نویس باشه!!',
                'thumbnail' => 'S5ZgQD8ZdimphCxJsPhEltuDVidn4lg58uPOMRMv.jpg',
                'story' => 'cbXfHJLbmPPAwNKGxjKZBIbnH2JhBLBRH05rDD13.mp4',
                'status' => 1,
                'created_at' => '2025-10-13 13:15:32',
                'updated_at' => '2025-10-13 13:15:35',
            ),
            3 => 
            array (
                'id' => 6,
                'title' => 'دروس عمومی فراموش نشه',
                'thumbnail' => 'w8T6rjVDdBGKJqZXgAJNCzsK6Dql3eBh23zYDb5Y.jpg',
                'story' => 'KpQ5hgt4znrFdHrlG7G4QbOZue2kgOqn7Oyz6tWm.mp4',
                'status' => 1,
                'created_at' => '2025-10-13 13:16:57',
                'updated_at' => '2025-10-13 13:17:01',
            ),
            4 => 
            array (
                'id' => 7,
                'title' => 'وقتی پایبندی به قوانین SDFR',
                'thumbnail' => 'ZIrnLVtWYu8AjL2kUDIQUadTBDlX8DBkwYfidIy4.jpg',
                'story' => 'sSD2gPeZOanQQPW8K8pIhQpQmal258ki3St1dXlb.mp4',
                'status' => 1,
                'created_at' => '2025-10-13 13:17:59',
                'updated_at' => '2025-10-13 13:19:01',
            ),
            5 => 
            array (
                'id' => 9,
                'title' => 'رقابت تجربی ها',
                'thumbnail' => 'YmJPxrgEVLDn2j5my4PefPkqyjWNP14iUpQBzwgX.jpg',
                'story' => 'Xyis8H1uQStNNqoIjDPyWLxjbDV6MYziXtFvKGhG.mp4',
                'status' => 1,
                'created_at' => '2025-10-13 13:19:54',
                'updated_at' => '2025-10-13 13:20:00',
            ),
        ));
        
        
    }
}