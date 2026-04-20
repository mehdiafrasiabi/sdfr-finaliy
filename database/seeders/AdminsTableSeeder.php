<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('admins')->delete();

        \DB::table('admins')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'Super Admin',
                    'email' => 'superadmin@gmail.com',
                    'mobile' => '09940682693',
                    'password' => '$2y$12$XJY1C/jejf05GZFdZzriDecQ3hKToEoo7cNIPXRAQ8h77uP0oLMLC',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:40',
                    'updated_at' => '2025-10-18 20:57:40',
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Product Admin',
                    'email' => 'productadmin@gmail.com',
                    'mobile' => '09940682692',
                    'password' => '$2y$12$xMVgZEuvC1xcDlD1Fvzxe.b1muCUbU.zzBEvceIt81N60jxcmakdy',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:40',
                    'updated_at' => '2025-10-18 20:57:40',
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'Order Admin',
                    'email' => 'orderadmin@gmail.com',
                    'mobile' => '09940682576',
                    'password' => '$2y$12$tDNRVdlHtC2rRjjpszLTJuvkADJPmMVYww1gCSenz/jVJ0I6YcXWq',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:40',
                    'updated_at' => '2025-10-18 20:57:40',
                ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'Payment Admin',
                    'email' => 'paymentsadmin@gmail.com',
                    'mobile' => '099406826939',
                    'password' => '$2y$12$YNnV65QGcO2.jdFgp5FknO9kK0OepiLK/kZVGrcqGCKbg/SOrieV.',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:41',
                    'updated_at' => '2025-10-18 20:57:41',
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'Map Admin',
                    'email' => 'mapadmin@gmail.com',
                    'mobile' => '09920682546',
                    'password' => '$2y$12$5OPoNNKoYSbcOJKzWwzRHuey0VHNlxVn9OQmWSt/NirUO4n.iGNdu',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:41',
                    'updated_at' => '2025-10-18 20:57:41',
                ),
            5 =>
                array (
                    'id' => 6,
                    'name' => 'student Admin',
                    'email' => 'studentadmin@gmail.com',
                    'mobile' => '09940342546',
                    'password' => '$2y$12$TOvhdUR3IQ/Urci6ua9qiOw7qXunSFFrl1MxPfLPA7x2k8jhOEXvG',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:41',
                    'updated_at' => '2025-10-18 20:57:41',
                ),
            6 =>
                array (
                    'id' => 7,
                    'name' => 'حریربافان',
                    'email' => 'harirbafan@gmail.com',
                    'mobile' => '09952486571',
                    'password' => '$2y$12$YnhJRvcddaPseo4sNfbWkeTy9zHBI5rAnMHT0shKMuAO9gnxU9sYq',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:41',
                    'updated_at' => '2025-10-18 20:57:41',
                ),
            7 =>
                array (
                    'id' => 8,
                    'name' => 'story Admin',
                    'email' => 'storyadmin@gmail.com',
                    'mobile' => '09140046546',
                    'password' => '$2y$12$cBtJU8k9e7tb1uSRjNmNvehmjpuAuCWe0qZqcfszzSOko9tRSTg6y',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:41',
                    'updated_at' => '2025-10-18 20:57:41',
                ),
            8 =>
                array (
                    'id' => 9,
                    'name' => 'ContactUs Admin',
                    'email' => 'contactusadmin@gmail.com',
                    'mobile' => '09240082546',
                    'password' => '$2y$12$V32xfCAavWXwqNKysFK/gedIiP8l8x5W.59IM90r0jgTKFLFsJR5u',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:42',
                    'updated_at' => '2025-10-18 20:57:42',
                ),
            9 =>
                array (
                    'id' => 10,
                    'name' => 'user Admin',
                    'email' => 'useradmin@gmail.com',
                    'mobile' => '09236982676',
                    'password' => '$2y$12$Am8kRBrAbATxrabBocIuFONTJ7cv3dsgT1YFLvpIZAbwjNid2d0SG',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:42',
                    'updated_at' => '2025-10-18 20:57:42',
                ),
            10 =>
                array (
                    'id' => 11,
                    'name' => 'پشتیبان تحصیلی',
                    'email' => 'academicsupport@gmail.com',
                    'mobile' => '09123458795',
                    'password' => '$2y$12$v8r0//HGfV50FfZHT3soUeNBLltc6s4ZMnX5pxY.qTtBZ/NvQfOMK',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:42',
                    'updated_at' => '2025-10-18 20:57:42',
                ),
            11 =>
                array (
                    'id' => 12,
                    'name' => 'مشاور تحصیلی',
                    'email' => 'academicadvisor@gmail.com',
                    'mobile' => '09121234567',
                    'password' => '$2y$12$DEv.a6PpSq67hNjU9XQxd.76ViAD9PjWmt3Ii4z3EJg5tGJpD7jLC',
                    'national_code' => NULL,
                    'address' => NULL,
                    'postal_code' => NULL,
                    'document' => NULL,
                    'contract' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 20:57:42',
                    'updated_at' => '2025-10-18 20:57:42',
                ),
            12 =>
                array (
                    'id' => 17,
                    'name' => 'بهشاد اتقیایی(مشاور)',
                    'email' => 'sdfr.education@gmail.com',
                    'mobile' => '09020029757',
                    'password' => '$2y$12$oZMdHbeaVvcj8rMdWEspLu/HHNxQj127m7k1n9fGWBQhs7g0mGh.m',
                    'national_code' => '5179896547',
                    'address' => 'مشهد-بلوار توس- توس70-امام زمان7-پلاک28-واحد8',
                    'postal_code' => '9198144257',
                    'document' => 'document176139185252555140980591.pdf',
                    'contract' => 'contract_176139185223198715761074.pdf',
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-25 15:00:52',
                    'updated_at' => '2025-10-25 15:00:52',
                ),
        ));


    }
}
