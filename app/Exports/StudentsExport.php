<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return Student::with(['user.personalInformation', 'admin'])->get()->map(function ($student) {
            return [
                'نام و نام خانوادگی'     => $student->user->personalInformation->name ?? '---',
                'کدملی'                  => $student->user->personalInformation->code_mell ?? '---',
                'شماره موبایل'           => $student->user->mobile ?? '---',
                'شماره پدر'              => $student->user->personalInformation->father_mobile ?? '---',
                'شماره مادر'             => $student->user->personalInformation->mother_mobile ?? '---',
                'پشتیبان'                => $student->admin->name ?? '---',
                'تاریخ آخرین تغییرات'   => jalali($student->updated_at)->format('Y/m/d - H:i:s'),
                'تاریخ عضویت'           => jalali($student->created_at)->format('Y/m/d - H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'نام و نام خانوادگی',
            'کدملی',
            'شماره موبایل',
            'شماره پدر',
            'شماره مادر',
            'پشتیبان',
            'تاریخ آخرین تغییرات',
            'تاریخ عضویت',
        ];
    }
}
