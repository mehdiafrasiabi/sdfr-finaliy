<?php

namespace App\Exports;

use App\Models\Admin;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupportersExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return Admin::role('academic support')
            ->withCount('students')
            ->get()
            ->map(function ($admin) {
                return [
                    'نام و نام خانوادگی' => $admin->name,
                    'ایمیل'              => $admin->email??'وجود ندارد',
                    'موبایل'             => $admin->mobile,
                    'تعداد دانش‌آموزان'  => $admin->students_count??'دانش اموزی ندارد',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'نام و نام خانوادگی',
            'ایمیل',
            'موبایل',
            'تعداد دانش‌آموزان',
        ];
    }
}
