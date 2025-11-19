<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Report;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class ReportDailyForAdminExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected string $status;

    public function __construct(string $status = 'all')
    {
        $this->status = $status;
    }

    public function query()
    {
        $query = Report::with('student.user');

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            '#',
            'نام دانش‌آموز',
            'توضیحات',
            'رضایت',
            'فایل',
            'وضعیت',
            'تاریخ ثبت درخواست',
            'تاریخ تغییر وضعیت',
        ];
    }

    public function map($item): array
    {
        $rating = (int) $item->complacent;
        $complacent = $rating > 0 ? $rating . ' از 10' : '---';

        $fileLink = $item->report_file
            ? url("students/reportsDaily/{$item->student_id}/{$item->report_file}")
            : 'ندارد';

        return [
            $item->id,
            $item->student?->user?->name ?? '----',
            $item->description ?? '----',
            $complacent,
            $fileLink,
            $item->status,
            jalali($item->created_at)?->format('Y-m-d H:i') ?? '---',
            jalali($item->updated_at)?->format('Y-m-d H:i') ?? '---',
        ];
    }
}
