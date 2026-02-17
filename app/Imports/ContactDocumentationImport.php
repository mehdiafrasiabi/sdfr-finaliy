<?php

namespace App\Imports;

use App\Models\ContactDocumentation;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Morilog\Jalali\Jalalian;

class ContactDocumentationImport implements ToCollection
{
    protected int $adminId;
    protected Collection $students; // students keyed by name

    public array $validRows   = [];
    public array $invalidRows = [];

    // Map Persian values to enum values
    protected array $statusMap = [
        'موفق'   => 'successful',
        'ناموفق' => 'unsuccessful',
    ];

    protected array $respondentMap = [
        'پدر'       => 'father',
        'مادر'      => 'mother',
        'دانش‌آموز' => 'student',
        'دانش آموز' => 'student',
        'سایر'      => 'other',
    ];

    public function __construct(int $adminId, Collection $students)
    {
        $this->adminId  = $adminId;
        $this->students = $students->keyBy(fn($s) => trim($s->user?->personalInformation?->name ?? $s->user?->name ?? ''));
    }

    public function collection(Collection $rows)
    {
        // Skip header row (row index 0)
        foreach ($rows->skip(1) as $index => $row) {
            $rowNumber = $index + 2; // human-readable row number

            $studentName   = trim($row[0] ?? '');
            $title         = trim($row[1] ?? '');
            $description   = trim($row[2] ?? '');
            $statusRaw     = trim($row[3] ?? '');
            $dateRaw       = trim($row[4] ?? '');
            $respondentRaw = trim($row[5] ?? '');

            $errors = [];

            // Validate student name
            $student = $this->students->get($studentName);
            if (!$student) {
                $errors[] = "دانش‌آموز «{$studentName}» یافت نشد";
            }

            // Validate title
            if (empty($title)) {
                $errors[] = 'عنوان خالی است';
            }

            // Validate status
            $contactStatus = $this->statusMap[$statusRaw] ?? null;
            if (!$contactStatus) {
                $errors[] = "وضعیت تماس «{$statusRaw}» معتبر نیست (باید: موفق یا ناموفق)";
            }

            // Validate date (Jalali format Y/m/d)
            $contactDate = null;
            if (empty($dateRaw)) {
                $errors[] = 'تاریخ تماس خالی است';
            } else {
                try {
                    // Support both / and - separators
                    $normalised = str_replace('-', '/', $dateRaw);
                    $parts = explode('/', $normalised);
                    if (count($parts) !== 3) {
                        throw new \Exception('bad format');
                    }
                    $jalali = Jalalian::fromFormat('Y/m/d', $normalised);
                    $contactDate = $jalali->toCarbon()->format('Y-m-d');
                } catch (\Exception $e) {
                    $errors[] = "تاریخ «{$dateRaw}» معتبر نیست (فرمت صحیح: 1403/01/15)";
                }
            }

            // Validate respondent
            $respondent = $this->respondentMap[$respondentRaw] ?? null;
            if (!$respondent) {
                $errors[] = "شخص پاسخگو «{$respondentRaw}» معتبر نیست (باید: پدر / مادر / دانش‌آموز / سایر)";
            }

            $rowData = [
                'row'           => $rowNumber,
                'student_name'  => $studentName,
                'title'         => $title,
                'description'   => $description,
                'contact_status'=> $statusRaw,
                'contact_date'  => $dateRaw,
                'respondent'    => $respondentRaw,
            ];

            if (!empty($errors)) {
                $rowData['errors'] = $errors;
                $this->invalidRows[] = $rowData;
            } else {
                $rowData['student_id']      = $student->id;
                $rowData['contact_status_val'] = $contactStatus;
                $rowData['contact_date_val']   = $contactDate;
                $rowData['respondent_val']     = $respondent;
                $this->validRows[] = $rowData;
            }
        }
    }

    /**
     * Persist valid rows into the database.
     */
    public function save(): int
    {
        $count = 0;
        foreach ($this->validRows as $row) {
            ContactDocumentation::create([
                'admin_id'       => $this->adminId,
                'student_id'     => $row['student_id'],
                'title'          => $row['title'],
                'description'    => $row['description'] ?: null,
                'contact_status' => $row['contact_status_val'],
                'contact_date'   => $row['contact_date_val'],
                'respondent'     => $row['respondent_val'],
            ]);
            $count++;
        }
        return $count;
    }
}
