<?php

namespace App\Imports;

use App\Models\CcSubject;
use App\Models\SchoolStudentGrade;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

/**
 * ثبت گروهی نمرات ماهانه از روی قالب اکسلِ SchoolGradesTemplateExport.
 * فقط دانش‌آموزانِ تحت مشاورهٔ همان مشاور و دروسِ معتبر پذیرفته می‌شوند.
 * ستون‌ها: 0=شناسه دانش‌آموز، 4=شناسه درس، 6=فعالیت کلاسی، 7=امتحان، 8=نظر دبیر
 */
class SchoolGradesImport implements ToCollection
{
    public array $validRows = [];
    public array $invalidRows = [];

    public function __construct(
        protected ?int $advisorId,
        protected string $jalaliMonth,
    ) {}

    public function collection(Collection $rows): void
    {
        $advisedStudentIds = Student::where('advisor_id', $this->advisorId)->pluck('id')->all();

        foreach ($rows->skip(1) as $index => $row) {
            $rowNumber = $index + 2;

            $studentId = (int) $this->digits((string) ($row[0] ?? ''));
            $subjectId = (int) $this->digits((string) ($row[4] ?? ''));
            $activityRaw = $this->digits((string) ($row[6] ?? ''));
            $examRaw     = $this->digits((string) ($row[7] ?? ''));
            $comment     = trim((string) ($row[8] ?? ''));

            // ردیف کاملاً خالی نادیده گرفته می‌شود.
            if ($activityRaw === '' && $examRaw === '' && $comment === '') {
                continue;
            }

            $errors = [];

            if (!$studentId || !in_array($studentId, $advisedStudentIds, true)) {
                $errors[] = 'دانش‌آموز جزو دانش‌آموزان تحت مشاورهٔ شما نیست';
            }
            if (!$subjectId || !CcSubject::whereKey($subjectId)->exists()) {
                $errors[] = 'شناسهٔ درس معتبر نیست';
            }
            if ($activityRaw !== '' && (!is_numeric($activityRaw) || $activityRaw < 0 || $activityRaw > 20)) {
                $errors[] = 'فعالیت کلاسی باید عددی بین ۰ تا ۲۰ باشد';
            }
            if ($examRaw !== '' && (!is_numeric($examRaw) || $examRaw < 0 || $examRaw > 20)) {
                $errors[] = 'امتحان باید عددی بین ۰ تا ۲۰ باشد';
            }

            $payload = [
                'student_id'      => $studentId,
                'cc_subject_id'   => $subjectId,
                'class_activity'  => $activityRaw !== '' ? $activityRaw : null,
                'exam'            => $examRaw !== '' ? $examRaw : null,
                'teacher_comment' => $comment !== '' ? $comment : null,
            ];

            if ($errors) {
                $this->invalidRows[] = ['row' => $rowNumber, 'errors' => $errors, 'data' => $payload];
            } else {
                $this->validRows[] = $payload;
            }
        }
    }

    public function save(): int
    {
        $count = 0;
        DB::transaction(function () use (&$count) {
            foreach ($this->validRows as $row) {
                SchoolStudentGrade::updateOrCreate(
                    [
                        'student_id'    => $row['student_id'],
                        'cc_subject_id' => $row['cc_subject_id'],
                        'jalali_month'  => $this->jalaliMonth,
                    ],
                    [
                        'class_activity'       => $row['class_activity'],
                        'exam'                 => $row['exam'],
                        'teacher_comment'      => $row['teacher_comment'],
                        'recorded_by_admin_id' => $this->advisorId,
                        'score'                => $row['exam'] ?? $row['class_activity'] ?? 0,
                        'scale'                => '20',
                        'recorded_at'          => now()->toDateString(),
                    ]
                );
                $count++;
            }
        });

        return $count;
    }

    private function digits(string $value): string
    {
        $value = trim($value);
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        $value = str_replace($persian, $english, $value);
        $value = str_replace($arabic, $english, $value);
        // اجازهٔ اعشار برای نمره
        return preg_replace('/[^0-9.]/', '', $value) ?? '';
    }
}
