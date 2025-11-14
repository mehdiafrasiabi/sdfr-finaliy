<?php

namespace App\Exports\admin;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Morilog\Jalali\Jalalian;


class ReportDailyActivitiesForAdmin implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected string $status;
    protected ?string $startDate;
    protected ?string $endDate;

    public function __construct(string $status = 'all', ?string $startDate = null, ?string $endDate = null)
    {
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    protected function parseJalaliToCarbonStart($jalali): ?\Carbon\Carbon
    {
        try {
            return Jalalian::fromFormat('Y/m/d', $jalali)->toCarbon()->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    protected function parseJalaliToCarbonEnd($jalali): ?\Carbon\Carbon
    {
        try {
            return Jalalian::fromFormat('Y/m/d', $jalali)->toCarbon()->endOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    public function query()
    {
        $query = Report::with('student.user');

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        if ($this->startDate) {
            $start = $this->parseJalaliToCarbonStart($this->startDate);
            if ($start) {
                $query->where('created_at', '>=', $start);
            }
        }

        if ($this->endDate) {
            $end = $this->parseJalaliToCarbonEnd($this->endDate);
            if ($end) {
                $query->where('created_at', '<=', $end);
            }
        }

        return $query->latest();
    }

    public function map($item): array
    {
        $complacent = match ($item->complacent) {
            1 => 'راضی‌ام',
            0 => 'تلاش بیشتر',
            default => '---',
        };

        $fileLink = $item->report_file
            ? url("students/reportsDaily/{$item->student_id}/{$item->report_file}")
            : 'ندارد';

        $created = $item->created_at ? Jalalian::fromDateTime($item->created_at)->format('Y/m/d H:i') : '---';
        $updated = $item->updated_at ? Jalalian::fromDateTime($item->updated_at)->format('Y/m/d H:i') : '---';

        return [
            $item->id,
            $item->student?->user?->name ?? '----',
            $item->description ?? '----',
            $complacent,
            $fileLink,
            $item->status,
            $created,
            $updated,
        ];
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
}
