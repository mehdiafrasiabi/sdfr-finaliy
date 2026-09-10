<?php


namespace App\Livewire\Client\Profile\TypedExam;
use App\Models\Student;
use App\Models\TypedExamAttempt;
use App\Models\TypedExamAnalysisUpload;
use App\Traits\UploadFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
            'answers.question.content',
            'answers.question.options',
            'answers.question.subject',
            'studentOrders.question.content',
            'studentOrders.question.options',
            'studentOrders.question.subject',
            'analysisUploads',
        ])
            ->where('id', $attemptId)
            ->where('student_id', $student->id)
            ->firstOrFail();
        if (!$this->attempt->is_finished) {
            abort(403, 'آزمون هنوز تکمیل نشده است.');
        }

        $this->normalizeAttemptResults();
        $this->checkVisibility();
    }

    protected function normalizeAttemptResults(): void
    {
        $hasChanges = false;

        foreach ($this->attempt->answers as $answer) {
            $previous = $answer->is_correct;
            $answer->checkCorrectness();
            $answer->refresh();

            if ($previous !== $answer->is_correct) {
                $hasChanges = true;
            }
        }

        $freshScore = $this->attempt->calculateScore();

        if ((float) $this->attempt->score !== (float) $freshScore) {
            $this->attempt->update(['score' => $freshScore]);
            $hasChanges = true;
        }

        if ($hasChanges) {
            $this->attempt->refresh();
            $this->attempt->loadMissing([
                'assignment.typedExam.settings',
                'assignment.typedExam.questions.content',
                'assignment.typedExam.questions.options',
                'assignment.typedExam.questions.subject',
                'assignment.time',
                'answers.question.content',
                'answers.question.options',
                'answers.question.subject',
                'studentOrders.question.content',
                'studentOrders.question.options',
                'studentOrders.question.subject',
                'analysisUploads',
            ]);
        }
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
                File::delete($upload->full_path);
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
            File::delete($upload->full_path);
            $upload->delete();
            $this->attempt->refresh();
        }
    }

    protected function buildQuestionAnswerData($exam)
    {
        $answers = $this->attempt->answers->keyBy('question_id');
        $orders = $this->attempt->studentOrders->sortBy('question_order');

        if ($orders->isEmpty()) {
            // این تلاش هیچ رکورد ترتیبی ندارد (مثلاً مربوط به قبل از قابلیت درهم‌ریزی گزینه‌هاست).
            // نباید سوالات فعلیِ آزمون خوانده شود چون ممکن است بعداً ویرایش شده باشد؛
            // منبع معتبر همیشه answers است که دقیقاً همان سوالاتی‌ست که در لحظه شروع آزمون برای این دانش‌آموز ثبت شده.
            return $this->attempt->answers->sortBy('id')->values()->map(function ($answer) {
                $question = $answer->question;
                if (!$question) {
                    return null;
                }
                return $this->formatQuestionAnswer($question, $answer, [1, 2, 3, 4]);
            })->filter()->values();
        }

        return $orders->map(function ($order) use ($answers) {
            $question = $order->question;

            if (!$question) {
                return null;
            }

            $answer = $answers->get($question->id);
            return $this->formatQuestionAnswer(
                $question,
                $answer,
                array_map('intval', $order->options_order ?: [1, 2, 3, 4])
            );
        })->filter()->values();
    }

    protected function formatQuestionAnswer($question, $answer, array $optionsOrder): array
    {
        $selectedOptionNumber = $answer?->selected_option;
        $correctOptionNumber = $answer?->correctOptionNumber() ?? $question->correct_option_number;
        $selectedPosition = $selectedOptionNumber === null
            ? null
            : array_search((int) $selectedOptionNumber, $optionsOrder, true);
        $correctPosition = $correctOptionNumber === null
            ? null
            : array_search((int) $correctOptionNumber, $optionsOrder, true);
        $options = $question->options->keyBy('option_number');
        $orderedOptions = collect($optionsOrder)
            ->map(fn ($number) => $options->get($number))
            ->filter()
            ->values();

        return [
            'question' => $question,
            'ordered_options' => $orderedOptions,
            'selected_option' => $selectedOptionNumber,
            'selected_position' => is_int($selectedPosition) ? $selectedPosition + 1 : null,
            'is_correct' => $selectedOptionNumber === null
                ? null
                : (int) $correctOptionNumber === (int) $selectedOptionNumber,
            'correct_option_number' => $correctOptionNumber,
            'correct_position' => is_int($correctPosition) ? $correctPosition + 1 : null,
            'correct_option' => $options->get($correctOptionNumber),
            'selected_option_obj' => $options->get($selectedOptionNumber),
        ];
    }

    protected function getSystemAnalysis(array $stats): string
    {
        $score = $stats['score'] ?? 0;
        $correct = $stats['correct'];
        $wrong = $stats['wrong'];
        $unanswered = $stats['unanswered'];
        $total = $stats['total'];

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
        $questionsWithAnswers = $this->buildQuestionAnswerData($exam);

        // منبعِ واحدِ محاسبه‌ی آمار (صحیح/غلط/بدون‌پاسخ/درصد/نمره منفی): همین متد روی مدل، تا این
        // صفحه و بخشِ «آزمون‌های قبلاً شرکت‌شده»ی پنل مشاور همیشه دقیقاً همان عدد را نشان دهند.
        $resultStats = $this->attempt->computeResultStats();

        $stats = [
            'score' => $resultStats['score'],
            'correct' => $resultStats['correct'],
            'wrong' => $resultStats['wrong'],
            'unanswered' => $resultStats['unanswered'],
            'negative_penalty_count' => $resultStats['negative_penalty_count'],
            'negative_correct' => $resultStats['negative_correct'],
            'negative_score' => $resultStats['negative_score'],
            'duration' => $this->attempt->formatted_duration,
            'duration_seconds' => $this->attempt->duration_in_seconds,
            'total' => $resultStats['total'],
            'started_at' => $this->attempt->started_at,
            'submitted_at' => $this->attempt->submitted_at,
        ];

        $systemAnalysis = $this->getSystemAnalysis($stats);

        if ($this->canViewAnswerKey) {
            if ($this->answerFilter !== 'all') {
                $questionsWithAnswers = $questionsWithAnswers->filter(function ($qa) {
                    return match ($this->answerFilter) {
                        'correct' => $qa['is_correct'] === true,
                        'wrong' => $qa['is_correct'] === false,
                        'unanswered' => $qa['selected_option'] === null,
                        default => true,
                    };
                })->values();
            }
        } else {
            $questionsWithAnswers = null;
        }

        return view('livewire.client.profile.typed-exam.typed-exam-result', compact(
            'exam', 'stats', 'questionsWithAnswers', 'systemAnalysis'
        ))->layout('layouts.client.app');
    }
}
