<?php


namespace App\Exports;


use App\Models\DailyReport;

use App\Models\WeeklyProgram;

use Carbon\Carbon;

use Carbon\CarbonPeriod;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class DailyReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles

{

    protected int $studentId;

    protected ?int $weeklyProgramId;

    protected ?Carbon $startDate;

    protected ?Carbon $endDate;

    protected array $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

    protected array $existingReportDates = [];

    protected int $row = 0;


    public function __construct(

        int     $studentId,

        ?int    $weeklyProgramId = null,

        ?Carbon $startDate = null,

        ?Carbon $endDate = null

    )
    {

        $this->studentId = $studentId;

        $this->weeklyProgramId = $weeklyProgramId;

        $this->startDate = $startDate;

        $this->endDate = $endDate;

    }


    public function collection()

    {

        $query = DailyReport::with(['reportParts.programPart', 'student.user'])
            ->where('student_id', $this->studentId);


        if ($this->weeklyProgramId) {

            $query->where('weekly_program_id', $this->weeklyProgramId);

        }


        if ($this->startDate && $this->endDate) {

            $query->whereBetween('report_date', [$this->startDate, $this->endDate]);

        }


        $reports = $query->orderBy('report_date')->get();


        // Get existing report dates

        $this->existingReportDates = $reports->pluck('report_date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();


        // If we have date range, we need to add "not sent" days

        if ($this->startDate && $this->endDate) {

            $period = CarbonPeriod::create($this->startDate, '1 day', $this->endDate);

            $missingDays = collect([]);


            foreach ($period as $day) {

                if ($day->gt(now())) {

                    continue;

                }


                $dayString = $day->format('Y-m-d');

                if (!in_array($dayString, $this->existingReportDates)) {

                    // Create a pseudo-report object for missing days

                    $missingDays->push((object)[

                        'is_missing' => true,

                        'report_date' => $day,

                        'day_of_week' => $day->dayOfWeek == 0 ? 6 : $day->dayOfWeek - 1,

                        'phone_hours' => 0,

                        'rating' => null,

                        'is_compensatory' => false,

                        'status' => 'not_sent',

                        'description' => '',

                        'reportParts' => collect([]),

                    ]);

                }

            }


            // Merge and sort

            $allItems = $reports->concat($missingDays)->sortBy(fn($item) => $item->report_date);

            return $allItems->values();

        }


        return $reports;

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

            'ساعت گوشی (غیردرسی)',

            'امتیاز روز',

            'نوع گزارش',

            'وضعیت',

            'توضیحات',

        ];

    }


    public function map($report): array

    {

        $this->row++;


        $isMissing = $report->is_missing ?? false;


        if ($isMissing) {

            $jalaliDate = jdate($report->report_date);

            $dayOfWeek = $report->report_date->dayOfWeek;

            $dayName = $this->dayNames[$dayOfWeek == 0 ? 6 : $dayOfWeek - 1] ?? '-';


            return [

                $this->row,

                $jalaliDate->format('Y/m/d'),

                $dayName,

                '-',

                '-',

                '-',

                '-',

                '-',

                '-',

                '-',

                'ارسال نشده',

                '',

            ];

        }


        $readParts = $report->reportParts->where('is_read', true)->count();

        $totalParts = $report->reportParts->count();

        $unreadParts = $totalParts - $readParts;


        $totalTests = $report->reportParts->sum(fn($p) => $p->programPart?->test_count ?? 0);

        $doneTests = $report->reportParts->sum('tests_done');

        $undoneTests = $totalTests - $doneTests;


        $ratingLabels = DailyReport::RATINGS;

        $statusLabels = [

            'pending' => 'در انتظار بررسی',

            'approved' => 'تایید شده',

            'rejected' => 'رد شده',

        ];


        return [

            $this->row,

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
