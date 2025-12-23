<?php


namespace App\Exports;


use App\Models\DailyReport;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class DailyReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles

{

    protected int $studentId;

    protected int $weeklyProgramId;

    protected array $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];


    public function __construct(int $studentId, int $weeklyProgramId)

    {

        $this->studentId = $studentId;

        $this->weeklyProgramId = $weeklyProgramId;

    }


    public function collection()

    {

        return DailyReport::with(['reportParts.programPart', 'student.user'])
            ->where('student_id', $this->studentId)
            ->where('weekly_program_id', $this->weeklyProgramId)
            ->orderBy('report_date')
            ->get();

    }


    public function headings(): array

    {

        return [

            'ردیف',

            'تاریخ',

            'روز هفته',

            'پارت خوانده',

            'پارت نخوانده',

            'تست زده',

            'تست نزده',

            'گوشی (غیردرسی)',

            'امتیاز',

            'نوع گزارش',

            'وضعیت',

            'توضیحات',

        ];

    }


    public function map($report): array

    {

        static $row = 0;

        $row++;


        $readParts = $report->reportParts->where('is_read', true)->count();

        $totalParts = $report->reportParts->count();

        $unreadParts = $totalParts - $readParts;


        $totalTests = $report->reportParts->sum(fn($p) => $p->programPart?->test_count ?? 0);

        $doneTests = $report->reportParts->sum('tests_done');

        $undoneTests = $totalTests - $doneTests;


        $ratingLabels = DailyReport::RATINGS;

        $statusLabels = [

            'pending' => 'در انتظار',

            'approved' => 'تایید شده',

            'rejected' => 'رد شده',

        ];


        return [

            $row,

            jdate($report->report_date)->format('Y/m/d'),

            $this->dayNames[$report->day_of_week] ?? '-',

            $readParts,

            $unreadParts,

            $doneTests,

            $undoneTests,

            $report->phone_hours,

            $ratingLabels[$report->rating] ?? 'نامشخص',

            $report->is_compensatory ? 'جبرانی' : 'عادی',

            $statusLabels[$report->status] ?? 'نامشخص',

            $report->description ?? '',

        ];

    }


    public function styles(Worksheet $sheet)

    {

        // Set RTL direction

        $sheet->setRightToLeft(true);


        // Style header row

        $sheet->getStyle('A1:L1')->applyFromArray([

            'font' => [

                'bold' => true,

                'size' => 12,

                'color' => ['rgb' => 'FFFFFF'],

            ],

            'fill' => [

                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,

                'startColor' => ['rgb' => '4B5563'],

            ],

            'alignment' => [

                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,

                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,

            ],

        ]);


        // Auto-size columns

        foreach (range('A', 'L') as $column) {

            $sheet->getColumnDimension($column)->setAutoSize(true);

        }


        return [];

    }

}
