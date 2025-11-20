<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdvisorStudentsExport implements FromCollection, WithHeadings
{
    protected $advisorId;

    public function __construct($advisorId)
    {
        $this->advisorId = $advisorId;
    }

    public function collection()
    {
        return Student::with('advisor')
            ->where('advisor_id', $this->advisorId)
            ->get()
            ->map(function ($student) {
                return [
                    'نام دانش‌آموز' => $student->personalInformation?->name,
                    'شماره موبایل' => $student->user?->mobile,
                    'نام پدر' => $student->personalInformation?->father_name,
                    'کدملی' => $student->personalInformation?->code_mell,
                    'محل تولد' => $student->personalInformation?->place_of_birth,
                    'استان' => $student->personalInformation?->state->name,
                    'شهر' => $student->personalInformation?->city->name,
                    'ادرس' => $student->personalInformation?->address,
                    'موبایل پدر' => $student->personalInformation?->father_mobile,
                    'موبایل مادر' => $student->personalInformation?->mother_mobile,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'نام دانش‌آموز',
            'شماره موبایل',
            'نام پدر',
            'کدملی',
            'محل تولد',
            'استان',
            'شهر',
            'ادرس',
            'موبایل پدر',
            'موبایل مادر',
        ];
    }
}
