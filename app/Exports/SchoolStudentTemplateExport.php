<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SchoolStudentTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'احمد رضایی',
                '0010012345',
                '09121112233',
                '09124445566',
                '09127778899',
                '10',
                'math',
                'father',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'نام و نام خانوادگی',
            'کدملی',
            'تلفن دانش‌آموز',
            'تلفن پدر',
            'تلفن مادر',
            'پایه (9/10/11/12)',
            'رشته (math/experimental/human)',
            'پیگیر آموزشی (father/mother)',
        ];
    }
}
