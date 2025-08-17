<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductImagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('product_images')->delete();
        
        \DB::table('product_images')->insert(array (
            0 => 
            array (
                'id' => 1,
                'path' => 'gxMdMJY9Jo9OFshWhoMc9NNNp7LZX1ATjLP5rSiw.webp',
                'is_cover' => 0,
                'product_id' => 1,
                'deleted_at' => '2025-03-27 22:37:03',
                'created_at' => '2025-03-27 21:49:41',
                'updated_at' => '2025-03-27 22:37:03',
            ),
            1 => 
            array (
                'id' => 2,
                'path' => 'jRRqA9WYqLMspj4JjgSgYoeji2izhp6kpvFg6mmY.webp',
                'is_cover' => 0,
                'product_id' => 1,
                'deleted_at' => '2025-03-27 22:37:14',
                'created_at' => '2025-03-27 22:03:54',
                'updated_at' => '2025-03-27 22:37:14',
            ),
            2 => 
            array (
                'id' => 3,
                'path' => 'Zi6PZouny4FQLBlO5S6fo41G0EO1z809h7RZYnZs.webp',
                'is_cover' => 1,
                'product_id' => 1,
                'deleted_at' => '2025-03-27 22:37:38',
                'created_at' => '2025-03-27 22:37:21',
                'updated_at' => '2025-03-27 22:37:38',
            ),
            3 => 
            array (
                'id' => 4,
                'path' => 'uR9rKozNvqkMY5m3im9zwbGftPUFK8IUCCnwPv56.webp',
                'is_cover' => 1,
                'product_id' => 1,
                'deleted_at' => '2025-03-27 23:07:13',
                'created_at' => '2025-03-27 22:37:42',
                'updated_at' => '2025-03-27 23:07:13',
            ),
            4 => 
            array (
                'id' => 5,
                'path' => 'RIGl7SEDEZBl4vRpuIeimkPDKQqRIL5ZwPpuhv0x.webp',
                'is_cover' => 1,
                'product_id' => 1,
                'deleted_at' => '2025-08-12 14:48:30',
                'created_at' => '2025-03-27 23:07:32',
                'updated_at' => '2025-08-12 14:48:30',
            ),
            5 => 
            array (
                'id' => 6,
                'path' => 'LKoLCrxgMBus7SYUlGQMAdzxX1i3lP3OcbRjfO4i.webp',
                'is_cover' => 1,
                'product_id' => 2,
                'deleted_at' => '2025-08-12 14:50:01',
                'created_at' => '2025-03-27 23:27:16',
                'updated_at' => '2025-08-12 14:50:01',
            ),
            6 => 
            array (
                'id' => 7,
                'path' => 'ANUponeG226L4ai6DITrObwfK5VqKHwGksDoixRD.webp',
                'is_cover' => 1,
                'product_id' => 3,
                'deleted_at' => NULL,
                'created_at' => '2025-03-27 23:30:09',
                'updated_at' => '2025-08-12 14:50:12',
            ),
            7 => 
            array (
                'id' => 8,
                'path' => 'y75KumBFN1F6rvGUcTrxwPbno9kpT5IYoucaV36H.webp',
                'is_cover' => 1,
                'product_id' => 4,
                'deleted_at' => '2025-08-12 15:10:44',
                'created_at' => '2025-08-12 14:54:18',
                'updated_at' => '2025-08-12 15:10:44',
            ),
            8 => 
            array (
                'id' => 9,
                'path' => '2yJMdN2wgkSDF8xbzWRk7h31KxKAa7sOcrqmphON.webp',
                'is_cover' => 1,
                'product_id' => 5,
                'deleted_at' => '2025-08-12 15:23:26',
                'created_at' => '2025-08-12 14:56:40',
                'updated_at' => '2025-08-12 15:23:26',
            ),
            9 => 
            array (
                'id' => 10,
                'path' => 'dBriIJtIcmY40ZEw2qrLrqvWpYjVV3e2Euwzbb8q.webp',
                'is_cover' => 1,
                'product_id' => 6,
                'deleted_at' => '2025-08-12 15:24:33',
                'created_at' => '2025-08-12 14:58:44',
                'updated_at' => '2025-08-12 15:24:33',
            ),
            10 => 
            array (
                'id' => 11,
                'path' => 'FcDJBr5l3Luud6Yus6wUKT4FNWR4OUwxoz2rHKvX.webp',
                'is_cover' => 1,
                'product_id' => 7,
                'deleted_at' => '2025-08-12 15:24:58',
                'created_at' => '2025-08-12 15:01:00',
                'updated_at' => '2025-08-12 15:24:58',
            ),
            11 => 
            array (
                'id' => 12,
                'path' => '1LzBJM88prLRPQeM6QrIBbRKK54qgGNzR7Mu0Gn9.webp',
                'is_cover' => 1,
                'product_id' => 8,
                'deleted_at' => '2025-08-12 15:25:31',
                'created_at' => '2025-08-12 15:02:47',
                'updated_at' => '2025-08-12 15:25:31',
            ),
            12 => 
            array (
                'id' => 13,
                'path' => 'SfhCDGhrAmjbZTrSqXV8LE2z16LtF4E7aKjglAX5.webp',
                'is_cover' => 1,
                'product_id' => 4,
                'deleted_at' => '2025-08-12 15:22:24',
                'created_at' => '2025-08-12 15:17:47',
                'updated_at' => '2025-08-12 15:22:24',
            ),
            13 => 
            array (
                'id' => 14,
                'path' => 'v7x16t6f83n8iDWTHJzkBdn9YQ5KeMkIeFa5Mk0N.webp',
                'is_cover' => 1,
                'product_id' => 4,
                'deleted_at' => '2025-08-17 18:10:16',
                'created_at' => '2025-08-12 15:23:03',
                'updated_at' => '2025-08-17 18:10:16',
            ),
            14 => 
            array (
                'id' => 15,
                'path' => 'zGg4BNRndNNBvHIqhBYXLGT0xXWu36pAZpzrVNSe.webp',
                'is_cover' => 1,
                'product_id' => 5,
                'deleted_at' => '2025-08-17 18:14:06',
                'created_at' => '2025-08-12 15:23:28',
                'updated_at' => '2025-08-17 18:14:06',
            ),
            15 => 
            array (
                'id' => 16,
                'path' => 'YshGAW00pzWuWGZjMG75xbbGyBUIDKmsRkKxQZTV.webp',
                'is_cover' => 1,
                'product_id' => 6,
                'deleted_at' => '2025-08-17 18:29:49',
                'created_at' => '2025-08-12 15:24:34',
                'updated_at' => '2025-08-17 18:29:49',
            ),
            16 => 
            array (
                'id' => 17,
                'path' => 'njeUSSqIfxUUAfZReS6NT4zbTiivTs2KlvYy8Hv8.webp',
                'is_cover' => 1,
                'product_id' => 7,
                'deleted_at' => '2025-08-17 18:30:52',
                'created_at' => '2025-08-12 15:24:59',
                'updated_at' => '2025-08-17 18:30:52',
            ),
            17 => 
            array (
                'id' => 18,
                'path' => 'lYl2NlzOQ0QdEMqwB48pOcHPsQUyF7Tu5feO6sIy.webp',
                'is_cover' => 1,
                'product_id' => 8,
                'deleted_at' => '2025-08-17 18:33:00',
                'created_at' => '2025-08-12 15:25:39',
                'updated_at' => '2025-08-17 18:33:00',
            ),
            18 => 
            array (
                'id' => 19,
                'path' => '2kD3vQTLrhUrmxzWSlnQQe52H61DBV8eAZ4QaAf1.webp',
                'is_cover' => 1,
                'product_id' => 4,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 18:10:59',
                'updated_at' => '2025-08-17 18:10:59',
            ),
            19 => 
            array (
                'id' => 20,
                'path' => 'DPxx69z906ntCNtiTNkke44ZqQ5zX788cXnOEULq.webp',
                'is_cover' => 1,
                'product_id' => 5,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 18:17:01',
                'updated_at' => '2025-08-17 18:17:01',
            ),
            20 => 
            array (
                'id' => 21,
                'path' => 'vp3pB9hZAohf2F6NTiXmOEJljDL7tNcc5hvb3Ujm.webp',
                'is_cover' => 1,
                'product_id' => 6,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 18:29:57',
                'updated_at' => '2025-08-17 18:29:57',
            ),
            21 => 
            array (
                'id' => 22,
                'path' => 'x96LLSdzO4xCMX2fDTpKdgCHezsCDOtMSuGnDxCz.webp',
                'is_cover' => 1,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 18:32:27',
                'updated_at' => '2025-08-17 18:32:27',
            ),
            22 => 
            array (
                'id' => 23,
                'path' => 'gXM1rAeKT4fBWlmclp76tEjdd0oYYj08JduBU7Z1.webp',
                'is_cover' => 1,
                'product_id' => 8,
                'deleted_at' => '2025-08-17 18:38:53',
                'created_at' => '2025-08-17 18:33:58',
                'updated_at' => '2025-08-17 18:38:53',
            ),
            23 => 
            array (
                'id' => 24,
                'path' => '3mYj25IYDZlflc7y16OKRIrchr8EJQ3z39aMayDo.webp',
                'is_cover' => 1,
                'product_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-08-17 18:39:15',
                'updated_at' => '2025-08-17 18:39:15',
            ),
        ));
        
        
    }
}