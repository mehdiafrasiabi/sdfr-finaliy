<?php

namespace App\Exports;

use App\Models\TrialWeek;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * خروجی دانش‌آموزان آزمایشی یک «پشتیبان جذب».
 */
class SupporterStudentsExport implements FromCollection, WithHeadings
{
    protected int $acquisitionSupporterId;

    public function __construct(int $acquisitionSupporterId)
    {
        $this->acquisitionSupporterId = $acquisitionSupporterId;
    }

    public function collection()
    {
        return TrialWeek::with(['user.personalInformation.state', 'user.personalInformation.city'])
            ->where('acquisition_supporter_id', $this->acquisitionSupporterId)
            ->get()
            ->map(function (TrialWeek $trial) {
                $pi = $trial->user?->personalInformation;
                return [
                    'نام دانش‌آموز' => $pi?->name ?? $trial->user?->name,
                    'شماره موبایل' => $trial->user?->mobile,
                    'نام پدر'      => $pi?->father_name,
                    'کدملی'        => $pi?->code_mell,
                    'محل تولد'     => $pi?->place_of_birth,
                    'استان'        => $pi?->state?->name,
                    'شهر'          => $pi?->city?->name,
                    'ادرس'         => $pi?->address,
                    'موبایل پدر'   => $trial->father_mobile ?? $pi?->father_mobile,
                    'موبایل مادر'  => $trial->mother_mobile ?? $pi?->mother_mobile,
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
