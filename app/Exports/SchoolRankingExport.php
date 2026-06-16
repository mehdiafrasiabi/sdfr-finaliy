<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * خروجی رتبه‌بندی: هر ردیف = یک دانش‌آموز با معدل کل و رتبهٔ او در پایه+رشته.
 * ورودی: همان ساختار گروه‌بندی‌شدهٔ ReportCards::buildRanking().
 */
class SchoolRankingExport implements FromCollection, WithHeadings
{
    public function __construct(protected Collection $groups) {}

    public function collection()
    {
        $out = collect();

        foreach ($this->groups as $group) {
            foreach ($group as $student) {
                $out->push([
                    'rank'    => $student['rank'],
                    'name'    => $student['name'],
                    'grade'   => $student['grade'],
                    'field'   => $student['field_label'],
                    'overall' => $student['overall'],
                ]);
            }
        }

        return $out;
    }

    public function headings(): array
    {
        return [
            'رتبه',
            'دانش‌آموز',
            'پایه',
            'رشته',
            'معدل کل',
        ];
    }
}
