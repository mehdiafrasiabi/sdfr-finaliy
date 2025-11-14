<?php
namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class BarnamehExport implements FromCollection, WithHeadings
{
    protected Student $student;
    protected ?Carbon $from;
    protected ?Carbon $to;

    public function __construct(Student $student, ?Carbon $from = null, ?Carbon $to = null)
    {
        $this->student = $student;
        $this->from = $from?->startOfDay();
        $this->to = $to?->endOfDay();
    }

    public function collection(): Collection
    {
        $query = $this->student->barnamehs()->with('admin');

        if ($this->from && $this->to) {
            $query->whereBetween('created_at', [$this->from, $this->to]);
        } elseif ($this->from) {
            $query->where('created_at', '>=', $this->from);
        } elseif ($this->to) {
            $query->where('created_at', '<=', $this->to);
        }

        return $query->get()->map(function ($barnameh) {
            return [
                'عنوان' => $barnameh->title,
                'پشتیبان' => $barnameh->supporter->name ?? '---',
                'تاریخ ایجاد' => jdate($barnameh->created_at)->format('Y/m/d H:i:s'),
                'وضعیت مشاهده' => $barnameh->views->isNotEmpty() ? 'دیده شده' : 'دیده نشده',
            ];
        });
    }

    public function headings(): array
    {
        return ['عنوان', 'پشتیبان', 'تاریخ ایجاد', 'وضعیت مشاهده'];
    }
}
