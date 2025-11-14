<?php
namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportDailyExport implements FromCollection, WithHeadings
{
    public function __construct(public Student $student) {}

    public function collection(): Collection
    {
        return $this->student->reportdaily()->get()->map(function ($report) {
            return [
                'توضیحات' => $report->description,
                'رضایت از خود' => $report->complacent ? 'راضی‌ام' : 'نیاز به تلاش بیشتر',
                'وضعیت' => match ($report->status) {
                    'pending' => 'در انتظار تایید',
                    'completed' => 'تایید شده',
                    'rejected' => 'رد شده',
                    default => '---'
                },
                'تاریخ ثبت' => jdate($report->created_at)->format('Y/m/d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return ['توضیحات', 'رضایت از خود', 'وضعیت', 'تاریخ ثبت'];
    }
}
