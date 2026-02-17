<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContactDocumentationTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected array $students;

    public function __construct(array $students)
    {
        $this->students = $students;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->students as $student) {
            $rows[] = [
                $student['name'],  // نام و نام خانوادگی (read-only info)
                '',                // عنوان
                '',                // توضیحات
                'موفق',           // وضعیت تماس (موفق / ناموفق)
                '',                // تاریخ تماس (فرمت: 1403/01/15)
                'پدر',            // شخص پاسخگو (پدر / مادر / دانش‌آموز / سایر)
            ];
        }

        // اگر دانش‌آموزی نداشت یک ردیف نمونه اضافه کن
        if (empty($rows)) {
            $rows[] = ['نام دانش‌آموز نمونه', 'عنوان تماس', 'توضیحات تماس', 'موفق', '1403/01/15', 'پدر'];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'نام و نام خانوادگی دانش‌آموز',
            'عنوان *',
            'توضیحات',
            'وضعیت تماس * (موفق / ناموفق)',
            'تاریخ تماس * (مثال: 1403/01/15)',
            'شخص پاسخگو * (پدر / مادر / دانش‌آموز / سایر)',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 25,
            'C' => 35,
            'D' => 30,
            'E' => 28,
            'F' => 38,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);

        // Header style
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1e3a5f'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(40);

        // Name column (A) – light blue, read info
        $lastRow = max(count($this->students) + 1, 2);
        if ($lastRow > 1) {
            $sheet->getStyle("A2:A{$lastRow}")->applyFromArray([
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'dbeafe'],
                ],
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ]);

            // Status & respondent columns hints
            $sheet->getStyle("D2:D{$lastRow}")->applyFromArray([
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'fef9c3'],
                ],
            ]);
            $sheet->getStyle("F2:F{$lastRow}")->applyFromArray([
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'fef9c3'],
                ],
            ]);
        }

        // Borders
        $sheet->getStyle("A1:F{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(
            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        );

        return [];
    }
}
