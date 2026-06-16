<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * خروجی تفصیلی نمرات: هر ردیف = یک (دانش‌آموز، درس) با فعالیت/امتحان/معدل/نظر دبیر.
 * ورودی: همان ساختار گروه‌بندی‌شدهٔ ReportCards::buildRanking().
 */
class SchoolGradesReportExport implements FromCollection, WithHeadings
{
    public function __construct(protected Collection $groups) {}

    public function collection()
    {
        $out = collect();

        foreach ($this->groups as $group) {
            foreach ($group as $student) {
                if ($student['subjects']->isEmpty()) {
                    continue;
                }
                foreach ($student['subjects'] as $subject) {
                    $out->push([
                        'rank'           => $student['rank'],
                        'name'           => $student['name'],
                        'grade'          => $student['grade'],
                        'field'          => $student['field_label'],
                        'subject'        => $subject['subject'],
                        'class_activity' => $subject['class_activity'],
                        'exam'           => $subject['exam'],
                        'average'        => $subject['average'],
                        'comment'        => $subject['teacher_comment'],
                    ]);
                }
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
            'درس',
            'فعالیت کلاسی (۲۰)',
            'امتحان (۲۰)',
            'معدل درس',
            'نظر دبیر',
        ];
    }
}
