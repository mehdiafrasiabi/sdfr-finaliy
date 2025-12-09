<?php

namespace App\Livewire\Manager\Exam;

use App\Helpers\FileHelper;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class ExamForm extends Component
{
    use WithFileUploads;

    public string $grade = 'twelfth';
    public string $major = 'math';
    public string $title = '';
    public string $level = 'medium';
    public string $shuffle_mode = 'none';
    public string $number_of_questions = '';
    public $pdf_file;
    public $solution_pdf_file;
    public string $duration_minutes = '';
    public $start_time;
    public $end_time;
    public $examKeys = [];

    public array $gradeOptions = [
        'tenth' => 'دهم',
        'eleventh' => 'یازدهم',
        'twelfth' => 'دوازدهم',
    ];

    public array $majorOptions = [
        'tenth' => [
            'math' => 'ریاضی',
            'experimental' => 'تجربی',
            'humanities' => 'انسانی',
        ],
        'eleventh' => [
            'math' => 'ریاضی',
            'experimental' => 'تجربی',
            'humanities' => 'انسانی',
        ],
        'twelfth' => [
            'math' => 'ریاضی',
            'experimental' => 'تجربی',
            'humanities' => 'انسانی',
        ],
    ];

    protected function rules()
    {
        return [
            'grade' => 'required|in:tenth,eleventh,twelfth',
            'major' => 'required|in:math,experimental,humanities',
            'title' => 'required|string|max:255',
            'level' => 'required|in:easy,medium,hard,comprehensive',
            'shuffle_mode' => 'required|in:none,questions,options,both',
            'number_of_questions' => 'required|integer|min:1',
            'pdf_file' => 'required|file|mimes:pdf|max:10240',
            'solution_pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'duration_minutes' => 'required|integer|min:1|max:180',
            'examKeys.*.correct_option' => 'required|in:1,2,3,4',
        ];
    }

    protected function messages()
    {
        return [
            'title.required' => 'وارد کردن عنوان آزمون الزامی است.',
            'pdf_file.required' => 'فایل سوالات الزامی است.',
            'pdf_file.mimes' => 'فرمت فایل سوالات باید PDF باشد.',
            'pdf_file.max' => 'حداکثر حجم فایل سوالات ۱۰ مگابایت است.',
            'solution_pdf_file.mimes' => 'فرمت فایل پاسخنامه باید PDF باشد.',
            'duration_minutes.required' => 'مدت زمان آزمون الزامی است.',
            'examKeys.*.correct_option.required' => 'لطفاً گزینه صحیح را برای همه سوالات انتخاب کنید.',
        ];
    }

    public function mount()
    {
        $this->major = array_key_first($this->majorOptions[$this->grade]) ?? 'math';
        $this->generateExamKeys();
    }

    public function updatedGrade(): void
    {
        $this->major = array_key_first($this->majorOptions[$this->grade]) ?? 'math';
    }

    public function updatedNumberOfQuestions()
    {
        $this->generateExamKeys();
    }

    public function generateExamKeys()
    {
        $this->examKeys = [];
        if ($this->number_of_questions > 0) {
            for ($i = 1; $i <= $this->number_of_questions; $i++) {
                $this->examKeys[$i] = ['question_number' => $i, 'correct_option' => ''];
            }
        }
    }

    public function saveExam()
    {
        $this->validate();

        DB::transaction(function () {
            // 🔹 ساخت پوشه اصلی exams در public_html
            $hashedName = md5('exam-' . time() . '-' . uniqid());

            // 🔹 آپلود فایل سوالات
            $questionPath = FileHelper::uploadToPublicHtml($this->pdf_file, "exam/{$hashedName}/questions");

            // 🔹 آپلود فایل پاسخنامه (اختیاری)
            $solutionPath = $this->solution_pdf_file
                ? FileHelper::uploadToPublicHtml($this->solution_pdf_file, "exam/{$hashedName}/solutions")
                : null;

            // 🔹 ذخیره در دیتابیس
            $exam = Exam::create([
                'grade' => $this->grade,
                'major' => $this->major,
                'title' => $this->title,
                'level' => $this->level,
                'shuffle_mode' => $this->shuffle_mode,
                'number_of_questions' => $this->number_of_questions,
                'duration_minutes' => $this->duration_minutes,
                'pdf_path' => $questionPath,
                'solution_pdf_path' => $solutionPath,
                'admin_id' => auth()->id(),
            ]);

            // 🔹 ثبت کلیدها
            foreach ($this->examKeys as $key) {
                $exam->examKeys()->create([
                    'question_number' => $key['question_number'],
                    'correct_option' => $key['correct_option'],
                ]);
            }

            // 🔹 حذف فایل موقت Livewire
            if ($this->pdf_file?->getRealPath() && file_exists($this->pdf_file->getRealPath())) {
                @unlink($this->pdf_file->getRealPath());
            }

            if ($this->solution_pdf_file?->getRealPath() && file_exists($this->solution_pdf_file->getRealPath())) {
                @unlink($this->solution_pdf_file->getRealPath());
            }
        });

        $this->dispatch('success', 'آزمون با موفقیت ایجاد شد.');
        $this->reset(['title', 'level', 'number_of_questions', 'pdf_file', 'solution_pdf_file', 'duration_minutes', 'examKeys', 'shuffle_mode']);
        $this->grade = 'twelfth';
        $this->major = array_key_first($this->majorOptions[$this->grade]) ?? 'math';
    }

    public function render()
    {
        return view('livewire.manager.exam.exam-form')->layout('layouts.manager.app');
    }

}
