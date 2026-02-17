<?php

namespace App\Exports;

use App\Models\ContactDocumentation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContactDocumentationExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected int $adminId;
    protected ?int $studentId;
    protected ?string $status;

    protected int $row = 0;

    public function __construct(int $adminId, ?int $studentId = null, ?string $status = null)
    {
        $this->adminId   = $adminId;
        $this->studentId = $studentId;
        $this->status    = $status;
    }

    public function collection()
    {
        $query = ContactDocumentation::with(['student.user.personalInformation'])
            ->where('admin_id', $this->adminId);

        if ($this->studentId) {
            $query->where('student_id', $this->studentId);
        }
        if ($this->status) {
            $query->where('contact_status', $this->status);
        }

        return $query->orderBy('contact_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ردیف',
            'نام و نام خانوادگی دانش‌آموز',
            'عنوان',
            'توضیحات',
            'وضعیت تماس',
            'تاریخ تماس',
            'شخص پاسخگو',
            'تاریخ ثبت',
        ];
    }

    public function map($record): array
    {
        $this->row++;

        $studentName = $record->student?->user?->personalInformation?->name
            ?? $record->student?->user?->name
            ?? 'نامشخص';

        return [
            $this->row,
            $studentName,
            $record->title,
            $record->description ?? '',
            ContactDocumentation::CONTACT_STATUS[$record->contact_status] ?? $record->contact_status,
            jdate($record->contact_date)->format('Y/m/d'),
            ContactDocumentation::RESPONDENT[$record->respondent] ?? $record->respondent,
            jdate($record->created_at)->format('Y/m/d'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);

        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1d4ed8'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}
