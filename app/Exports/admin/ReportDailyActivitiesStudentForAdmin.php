<?php
namespace App\Exports\admin;

use App\Models\Report;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Morilog\Jalali\Jalalian;

class ReportDailyActivitiesStudentForAdmin implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected int $studentId;
    protected string $status;
    protected ?string $startDate;
    protected ?string $endDate;

    public function __construct(int $studentId, string $status = 'all', ?string $startDate = null, ?string $endDate = null)
    {
        $this->studentId = $studentId;
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

    public function query(): Builder
    {
        $query = Report::with('student.user')
            ->where('student_id', $this->studentId);

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
        $complacent = match ($item->complacent) {
            1 => 'راضی‌ام',
            0 => 'تلاش بیشتر',
            default => '---',
        };
        $status = match ($item->status) {
            'pending' => 'در انتظار تایید گزارش',
            'completed' => 'گزارش تایید شده است',
            'rejected' => 'گزارش رد شده است',
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
            $status,
            $created,
            $updated,
        ];
    }
}
