<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsByAdminExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected int $adminId;

    public function __construct(int $adminId)
    {
        $this->adminId = $adminId;
    }

    public function query()
    {
        $query = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation'
            ])
            ->where('admin_id', $this->adminId);

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'نام دانش‌آموز',
            'موبایل',
            'موبایل پدر',
            'موبایل مادر',
        ];
    }

    public function map($student): array
    {

        return [
            $student->id,
            $student->user?->personalInformation?->name ?? '',
            $student->payment?->order?->user?->mobile ?? '',
            $student->user?->personalInformation?->father_mobile ?? '',
            $student->user?->personalInformation?->mother_mobile ?? '',
        ];
    }
}
