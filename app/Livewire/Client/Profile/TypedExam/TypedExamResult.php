<?php


namespace App\Livewire\Client\Profile\TypedExam;
use App\Models\Student;
use App\Models\TypedExamAttempt;
use App\Models\TypedExamAnalysisUpload;
use App\Traits\UploadFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
class TypedExamResult extends Component
{
    use WithFileUploads,UploadFile;
    public int $attemptId;
    public ?TypedExamAttempt $attempt = null;
    public bool $canViewResult = false;
    public bool $canViewAnswerKey = false;
    // View mode: 'report' or 'answersheet'
    public string $viewMode = 'report';
    // Filter for answer sheet
    public string $answerFilter = 'all';
    // Analysis uploads
    public $analysisFiles = [];
    public array $uploadedPreviews = [];
    protected $rules = [
        'analysisFiles.*' => 'image|max:20480', // 20MB max
    ];
    protected $messages = [
        'analysisFiles.*.image' => 'فایل‌های آپلودی باید تصویر باشند.',
        'analysisFiles.*.max' => 'حداکثر حجم هر فایل ۲۰ مگابایت است.',
    ];
    public function mount(int $attemptId): void
    {
        $this->attemptId = $attemptId;
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        $this->attempt = TypedExamAttempt::with([
            'assignment.typedExam.settings',
            'assignment.typedExam.questions.content',
            'assignment.typedExam.questions.options',
            'assignment.typedExam.questions.subject',
            'assignment.time',
            'answers',
            'studentOrders',
            'analysisUploads',
        ])
            ->where('id', $attemptId)
            ->where('student_id', $student->id)
            ->firstOrFail();
        if (!$this->attempt->is_finished) {
            abort(403, 'آزمون هنوز تکمیل نشده است.');
        }
        $this->checkVisibility();
    }
    protected function checkVisibility(): void
    {
        $settings = $this->attempt->assignment->typedExam->settings;
        $assignmentTime = $this->attempt->assignment->time;
        if (!$settings) {
            $this->canViewResult = true;
            $this->canViewAnswerKey = true;
            return;
        }
        $now = now();
        $examEnded = false;
        if ($assignmentTime) {
            $endDateTime = $assignmentTime->end_date->format('Y-m-d') . ' ' . $assignmentTime->end_time;
            $examEnded = $now->gt($endDateTime);
        }
        // Result visibility
        if ($settings->result_visibility === 'immediately') {
            $this->canViewResult = true;
        } elseif ($settings->result_visibility === 'after_exam_end' && $examEnded) {
            $this->canViewResult = true;
        }
        // Answer key visibility
        if ($settings->answer_key_visibility === 'immediately') {
            $this->canViewAnswerKey = true;
        } elseif ($settings->answer_key_visibility === 'after_exam_end' && $examEnded) {
            $this->canViewAnswerKey = true;
        }
    }
    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode;
    }
    public function setAnswerFilter(string $filter): void
    {
        $this->answerFilter = $filter;
    }
    public function updatedAnalysisFiles(): void
    {
        $this->validate();
        $this->uploadedPreviews = [];
        foreach ($this->analysisFiles as $file) {
            $this->uploadedPreviews[] = $file->temporaryUrl();
        }
    }
    public function removePreview(int $index): void
    {
        if (isset($this->analysisFiles[$index])) {
            unset($this->analysisFiles[$index]);
            $this->analysisFiles = array_values($this->analysisFiles);
        }
        if (isset($this->uploadedPreviews[$index])) {
            unset($this->uploadedPreviews[$index]);
            $this->uploadedPreviews = array_values($this->uploadedPreviews);
        }
    }


    public function uploadAnalysis(): void
    {
        $this->validate();

        if (empty($this->analysisFiles)) {
            $this->addError('analysisFiles', 'لطفاً حداقل یک فایل انتخاب کنید.');
            return;
        }

        if (! $this->attempt->canUploadAnalysis()) {
            $this->addError('analysisFiles', 'امکان آپلود تحلیل در این وضعیت وجود ندارد.');
            return;
        }

        // اگر قبلاً رد شده، فایل‌های قدیمی را پاک کن
        if ($this->attempt->isAnalysisRejected()) {
            foreach ($this->attempt->analysisUploads as $upload) {
                Storage::disk('public')->delete($upload->file_path);
                $upload->delete();
            }
        }

        // می‌تونی folder را هرچی خواستی بذاری، من گذاشتم analysis
        $folder    = 'analysis';
        $studentId = $this->attempt->student_id; // یا $this->attempt->student->id اگر رابطه داری

        foreach ($this->analysisFiles as $file) {

            // استفاده از trait برای تبدیل و ذخیره WebP
            $relativePath = $this->uploadImageInWebpFormatExamAnalisis(
                $file,
                $studentId,
                1600,  // width
                1600,  // height
                $folder
            );

            if (! $relativePath) {
                continue;
            }

            $fullPath = public_path($relativePath);
            $fileSize = file_exists($fullPath) ? filesize($fullPath) : null;

            TypedExamAnalysisUpload::create([
                'attempt_id'    => $this->attempt->id,
                'file_path'     => $relativePath,                     // همون مسیر نسبی
                'original_name' => $file->getClientOriginalName(),
                'file_size'     => $fileSize,
            ]);
            // حذف فایل temp لایووایر بعد از ذخیره
            if ($file instanceof \Livewire\TemporaryUploadedFile) {
                $file->delete();
            }
        }
        // آپدیت وضعیت به pending
        $this->attempt->update(['analysis_status' => 'pending']);
        // خالی کردن state کامپوننت
        $this->analysisFiles    = [];
        $this->uploadedPreviews = [];
        // ریفرش attempt
        $this->attempt->refresh();
        session()->flash('success', 'تحلیل شما با موفقیت آپلود شد و در انتظار بررسی است.');
    }
    public function deleteAnalysisFile(int $uploadId): void
    {
        $upload = TypedExamAnalysisUpload::where('id', $uploadId)
            ->where('attempt_id', $this->attempt->id)
            ->first();
        if ($upload && $this->attempt->canUploadAnalysis()) {
            Storage::disk('public')->delete($upload->file_path);
            $upload->delete();
            $this->attempt->refresh();
        }
    }
    protected function getSystemAnalysis(): string
    {
        $score = $this->attempt->score ?? 0;
        $correct = $this->attempt->correct_count;
        $wrong = $this->attempt->wrong_count;
        $unanswered = $this->attempt->unanswered_count;
        $total = $correct + $wrong + $unanswered;
        if ($score >= 80) {
            return "عالی! عملکرد شما در این آزمون بسیار خوب بوده است. شما {$correct} سوال از {$total} سوال را درست پاسخ دادید. به همین روند ادامه دهید و برای آزمون‌های بعدی آماده شوید.";
        } elseif ($score >= 60) {
            return "خوب است! عملکرد شما قابل قبول است. شما {$correct} سوال از {$total} سوال را درست پاسخ دادید. با تمرین بیشتر می‌توانید نتایج بهتری کسب کنید.";
        } elseif ($score >= 40) {
            $message = "متوسط. شما {$correct} سوال از {$total} سوال را درست پاسخ دادید.";
            if ($unanswered > 0) {
                $message .= " توصیه می‌شود به سوالات بدون پاسخ ({$unanswered} سوال) توجه ویژه داشته باشید.";
            }
            if ($wrong > $correct) {
                $message .= " سوالات غلط ({$wrong} سوال) نشان می‌دهد که نیاز به مرور مجدد مطالب دارید.";
            }
            return $message;
        } else {
            return "نیاز به تلاش بیشتر دارید. شما فقط {$correct} سوال از {$total} سوال را درست پاسخ دادید. پیشنهاد می‌شود مباحث درسی را مجدداً مرور کنید و تمرینات بیشتری انجام دهید.";
        }
    }
    public function render()
    {
        $exam = $this->attempt->assignment->typedExam;
        $answers = $this->attempt->answers->keyBy('question_id');
        $stats = [
            'score' => $this->attempt->score,
            'correct' => $this->attempt->correct_count,
            'wrong' => $this->attempt->wrong_count,
            'unanswered' => $this->attempt->unanswered_count,
            'duration' => $this->attempt->formatted_duration,
            'total' => $exam->questions->count(),
            'started_at' => $this->attempt->started_at,
            'submitted_at' => $this->attempt->submitted_at,
        ];
        $systemAnalysis = $this->getSystemAnalysis();
        $questionsWithAnswers = null;
        if ($this->canViewAnswerKey) {
            $questionsWithAnswers = $exam->questions->map(function ($question) use ($answers) {
                $answer = $answers->get($question->id);
                $correctOption = $question->options->firstWhere('is_correct', true);
                $selectedOption = $question->options->firstWhere('option_number', $answer?->selected_option);
                return [
                    'question' => $question,
                    'selected_option' => $answer?->selected_option,
                    'is_correct' => $answer?->is_correct,
                    'correct_option_number' => $correctOption?->option_number,
                    'correct_option' => $correctOption,
                    'selected_option_obj' => $selectedOption,
                ];
            });
            // Apply filter
            if ($this->answerFilter !== 'all') {
                $questionsWithAnswers = $questionsWithAnswers->filter(function ($qa) {
                    return match ($this->answerFilter) {
                        'correct' => $qa['is_correct'] === true,
                        'wrong' => $qa['is_correct'] === false,
                        'unanswered' => $qa['selected_option'] === null,
                        default => true,
                    };
                });
            }
        }
        return view('livewire.client.profile.typed-exam.typed-exam-result', compact(
            'exam', 'stats', 'questionsWithAnswers', 'systemAnalysis'
        ))->layout('layouts.client.app');
    }
}


