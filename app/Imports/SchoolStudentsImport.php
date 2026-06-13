<?php

namespace App\Imports;

use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class SchoolStudentsImport implements ToCollection
{
    protected int $schoolId;

    public array $validRows = [];
    public array $invalidRows = [];

    protected array $gradeMap = [
        '9' => '9', '10' => '10', '11' => '11', '12' => '12',
        'نهم' => '9', 'دهم' => '10', 'یازدهم' => '11', 'دوازدهم' => '12',
    ];

    protected array $fieldMap = [
        'math' => 'math', 'experimental' => 'experimental', 'human' => 'human',
        'ریاضی' => 'math', 'تجربی' => 'experimental', 'انسانی' => 'human',
    ];

    protected array $pursuerMap = [
        'father' => 'father', 'mother' => 'mother',
        'پدر' => 'father', 'مادر' => 'mother',
    ];

    public function __construct(int $schoolId)
    {
        $this->schoolId = $schoolId;
    }

    public function collection(Collection $rows): void
    {
        // skip header
        $existingMobiles = User::pluck('mobile')->filter()->map(fn($m) => (string) $m)->all();
        $existingNationalCodes = Student::whereNotNull('national_code')->pluck('national_code')->all();

        $seenMobiles = [];
        $seenNationalCodes = [];

        foreach ($rows->skip(1) as $index => $row) {
            $rowNumber = $index + 2; // +1 for header, +1 for 1-based index

            $name           = trim((string) ($row[0] ?? ''));
            $nationalCode   = $this->digits((string) ($row[1] ?? ''));
            $mobile         = $this->digits((string) ($row[2] ?? ''));
            $fatherMobile   = $this->digits((string) ($row[3] ?? ''));
            $motherMobile   = $this->digits((string) ($row[4] ?? ''));
            $gradeRaw       = trim((string) ($row[5] ?? ''));
            $fieldRaw       = trim((string) ($row[6] ?? ''));
            $pursuerRaw     = trim((string) ($row[7] ?? ''));

            $errors = [];

            if ($name === '') {
                $errors[] = 'نام و نام خانوادگی ضروری است';
            }

            if (!preg_match('/^\d{10}$/', $nationalCode)) {
                $errors[] = 'کدملی باید ۱۰ رقم باشد';
            } elseif (in_array($nationalCode, $existingNationalCodes, true)) {
                $errors[] = 'کدملی قبلاً ثبت شده است';
            } elseif (in_array($nationalCode, $seenNationalCodes, true)) {
                $errors[] = 'کدملی در همین فایل تکراری است';
            }

            if (!preg_match('/^09\d{9}$/', $mobile)) {
                $errors[] = 'تلفن دانش‌آموز معتبر نیست (باید با 09 شروع و ۱۱ رقم باشد)';
            } elseif (in_array($mobile, $existingMobiles, true)) {
                $errors[] = 'تلفن دانش‌آموز قبلاً در سامانه ثبت شده است';
            } elseif (in_array($mobile, $seenMobiles, true)) {
                $errors[] = 'تلفن دانش‌آموز در همین فایل تکراری است';
            }

            if ($fatherMobile !== '' && !preg_match('/^0\d{10}$/', $fatherMobile)) {
                $errors[] = 'تلفن پدر معتبر نیست';
            }
            if ($motherMobile !== '' && !preg_match('/^0\d{10}$/', $motherMobile)) {
                $errors[] = 'تلفن مادر معتبر نیست';
            }

            $grade = $this->gradeMap[$gradeRaw] ?? null;
            if (!$grade) {
                $errors[] = 'پایه معتبر نیست (مجاز: 9/10/11/12 یا نهم/دهم/یازدهم/دوازدهم)';
            }

            // برای پایه نهم رشته معنا ندارد و اختیاری است.
            $field = $this->fieldMap[$fieldRaw] ?? null;
            if ($grade === '9') {
                $field = null;
            } elseif (!$field) {
                $errors[] = 'رشته معتبر نیست (مجاز: math/experimental/human یا ریاضی/تجربی/انسانی)';
            }

            $pursuer = $this->pursuerMap[$pursuerRaw] ?? null;
            if (!$pursuer) {
                $errors[] = 'پیگیر آموزشی معتبر نیست (مجاز: father/mother یا پدر/مادر)';
            }

            if ($pursuer === 'father' && $fatherMobile === '') {
                $errors[] = 'وقتی پیگیر «پدر» است، تلفن پدر باید پر باشد';
            }
            if ($pursuer === 'mother' && $motherMobile === '') {
                $errors[] = 'وقتی پیگیر «مادر» است، تلفن مادر باید پر باشد';
            }

            $payload = [
                'name'                => $name,
                'national_code'       => $nationalCode,
                'mobile'              => $mobile,
                'father_mobile'       => $fatherMobile ?: null,
                'mother_mobile'       => $motherMobile ?: null,
                'grade'               => $grade,
                'field'               => $field,
                'educational_pursuer' => $pursuer,
            ];

            if ($errors) {
                $this->invalidRows[] = [
                    'row'    => $rowNumber,
                    'errors' => $errors,
                    'data'   => $payload,
                ];
            } else {
                $seenMobiles[] = $mobile;
                $seenNationalCodes[] = $nationalCode;
                $this->validRows[] = $payload;
            }
        }
    }

    public function save(): int
    {
        $school = School::with('advisors')->find($this->schoolId);
        if (!$school) {
            return 0;
        }

        $autoAdvisorId = null;
        if ($school->advisors->count() === 1) {
            $autoAdvisorId = $school->advisors->first()->id;
        }

        $count = 0;
        DB::transaction(function () use ($school, $autoAdvisorId, &$count) {
            foreach ($this->validRows as $row) {
                $user = User::create([
                    'name'     => $row['name'],
                    'mobile'   => $row['mobile'],
                    'password' => Hash::make($row['national_code']),
                ]);

                Student::create([
                    'user_id'             => $user->id,
                    'school_id'           => $school->id,
                    'advisor_id'          => $autoAdvisorId,
                    'national_code'       => $row['national_code'],
                    'father_mobile'       => $row['father_mobile'],
                    'mother_mobile'       => $row['mother_mobile'],
                    'grade'               => $row['grade'],
                    'field'               => $row['field'],
                    'educational_pursuer' => $row['educational_pursuer'],
                ]);

                $count++;
            }
        });

        return $count;
    }

    private function digits(string $value): string
    {
        $value = trim($value);
        // Convert Persian/Arabic digits to English
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        $value = str_replace($persian, $english, $value);
        $value = str_replace($arabic, $english, $value);
        // strip non-digits
        return preg_replace('/\D/', '', $value) ?? '';
    }
}
