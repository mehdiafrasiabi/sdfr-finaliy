<?php


namespace App\Livewire\Manager\Questions;

use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\CcTopic;
use App\Models\EducationLevel;
use App\Models\Question;
use App\Models\QuestionContent;
use App\Traits\UploadFile;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class QuestionForm extends Component
{
    use WithFileUploads, UploadFile;

    // Question basic info (edit mode)
    public ?string $questionCode = null;
    public ?int $questionId = null;
    public string $difficulty = 'medium';
    public int $correctOption = 1;
    // Hierarchical filter
    public string $educationLevelId = '';
    public string $gradeId = '';
    public string $fieldId = '';
    public string $subjectId = '';
    public string $chapterId = '';
    public string $topicId = '';
    public bool $isComprehensive = false;
    // Collections for dropdowns
    public $educationLevels = [];
    public $grades = [];
    public $fields = [];
    public $subjects = [];
    public $chapters = [];
    public $topics = [];
    // Edit mode - single image uploads
    public $questionImage;
    public $explanationImage;
    public ?string $existingQuestionImage = null;
    public ?string $existingExplanationImage = null;
    // Create mode - multi-question
    public $questionCount = 1;
    public array $questionImages = [];
    public array $explanationImages = [];
    public array $questionCorrectOptions = [];
    public array $questionDifficulties = [];
    public bool $isEditMode = false;
    protected array $savedImagePaths = [];

    protected function rules(): array
    {
        $rules = [
            'educationLevelId' => 'required|exists:education_levels,id',
            'gradeId' => 'required|exists:cc_grades,id',
            'subjectId' => 'required|exists:cc_subjects,id',
            'chapterId' => 'required|exists:cc_chapters,id',
            'topicId' => $this->isComprehensive ? 'nullable|exists:cc_topics,id' : 'required|exists:cc_topics,id',
        ];
        if ($this->shouldShowFieldSelect()) {
            $rules['fieldId'] = 'required|exists:cc_fields,id';
        }
        if ($this->isEditMode) {
            $rules['difficulty'] = 'required|in:easy,medium,hard,special';
            $rules['correctOption'] = 'required|integer|min:1|max:4';
            if (!$this->existingQuestionImage) {
                $rules['questionImage'] = 'required|image|max:10240';
            } else {
                $rules['questionImage'] = 'nullable|image|max:10240';
            }
            $rules['explanationImage'] = 'nullable|image|max:10240';
        } else {
            for ($i = 0; $i < $this->questionCount; $i++) {
                $rules["questionImages.$i"] = 'required|image|max:10240';
                $rules["explanationImages.$i"] = 'nullable|image|max:10240';
                $rules["questionCorrectOptions.$i"] = 'required|integer|min:1|max:4';
                $rules["questionDifficulties.$i"] = 'required|in:easy,medium,hard,special';
            }
        }
        return $rules;
    }

    protected function messages(): array
    {
        return [
            'educationLevelId.required' => 'انتخاب دوره تحصیلی الزامی است.',
            'gradeId.required' => 'انتخاب پایه الزامی است.',
            'fieldId.required' => 'انتخاب رشته الزامی است.',
            'subjectId.required' => 'انتخاب درس الزامی است.',
            'chapterId.required' => 'انتخاب فصل الزامی است.',
            'topicId.required' => 'انتخاب مبحث الزامی است.',
            'difficulty.required' => 'انتخاب سطح سختی الزامی است.',
            'correctOption.required' => 'انتخاب گزینه صحیح الزامی است.',
            'correctOption.min' => 'گزینه صحیح باید بین ۱ تا ۴ باشد.',
            'correctOption.max' => 'گزینه صحیح باید بین ۱ تا ۴ باشد.',
            'questionImage.required' => 'آپلود عکس سوال الزامی است.',
            'questionImage.image' => 'فایل باید تصویر باشد.',
            'questionImage.max' => 'حجم تصویر نباید بیشتر از ۱۰ مگابایت باشد.',
            'questionImages.*.required' => 'آپلود عکس سوال الزامی است.',
            'questionImages.*.image' => 'فایل باید تصویر باشد.',
            'questionImages.*.max' => 'حجم تصویر نباید بیشتر از ۱۰ مگابایت باشد.',
            'explanationImages.*.image' => 'فایل باید تصویر باشد.',
            'explanationImages.*.max' => 'حجم تصویر نباید بیشتر از ۱۰ مگابایت باشد.',
            'questionCorrectOptions.*.required' => 'انتخاب گزینه صحیح الزامی است.',
            'questionCorrectOptions.*.min' => 'گزینه صحیح باید بین ۱ تا ۴ باشد.',
            'questionCorrectOptions.*.max' => 'گزینه صحیح باید بین ۱ تا ۴ باشد.',
            'questionDifficulties.*.required' => 'انتخاب سطح سختی الزامی است.',
        ];
    }

    public function mount(?string $code = null): void
    {
        $this->educationLevels = EducationLevel::where('is_active', true)->orderBy('order')->get();
        $this->questionCorrectOptions = [1];
        $this->questionDifficulties = ['medium'];
        if ($code) {
            $this->loadQuestion($code);
        }
    }

    protected function loadQuestion(string $code): void
    {
        $question = Question::with([
            'content',
            'topic.chapter.subject.grade.educationLevel',
            'chapter.subject.grade.educationLevel',
        ])
            ->where('code', $code)
            ->firstOrFail();
        $this->isEditMode = true;
        $this->questionId = $question->id;
        $this->questionCode = $question->code;
        $this->difficulty = $question->difficulty;
        $this->correctOption = $question->correct_option ?? 1;
        $chapter = $question->topic?->chapter ?? $question->chapter;
        if ($chapter) {
            $topic = $question->topic;
            $subject = $chapter->subject;
            $grade = $subject->grade;
            $educationLevel = $grade->educationLevel;
            $this->educationLevelId = (string)$educationLevel->id;
            $this->updatedEducationLevelId($this->educationLevelId);

            $fieldId = $grade->cc_field_id ?: $subject->cc_field_id;
            if ($fieldId) {
                $this->fieldId = (string)$fieldId;
                $this->updatedFieldId($this->fieldId);
            }

            $this->gradeId = (string)$grade->id;
            $this->updatedGradeId($this->gradeId);
            $this->subjectId = (string)$subject->id;
            $this->updatedSubjectId($this->subjectId);
            $this->chapterId = (string)$chapter->id;
            $this->updatedChapterId($this->chapterId);
            $this->isComprehensive = !$topic;
            $this->topicId = $topic ? (string)$topic->id : '';
        }
        if ($question->content) {
            $this->existingQuestionImage = $question->content->question_image_url;
            $this->existingExplanationImage = $question->content->explanation_image_url;
        }
    }

    // Cascade dropdowns
    public function updatedEducationLevelId($value): void
    {
        $this->reset(['gradeId', 'fieldId', 'subjectId', 'chapterId', 'topicId', 'isComprehensive']);
        $this->grades = [];
        $this->fields = [];
        $this->subjects = [];
        $this->chapters = [];
        $this->topics = [];
        if ($value) {
            if ($this->educationLevelNeedsField($value)) {
                $this->fields = CcField::where('is_active', true)->orderBy('order')->get();
            } else {
                $this->grades = CcGrade::where('education_level_id', $value)
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('cc_field_id')->orWhere('grade_number', '<', 10);
                    })
                    ->orderBy('order')
                    ->get();
            }
        }
    }

    public function updatedGradeId($value): void
    {
        $this->reset(['subjectId', 'chapterId', 'topicId', 'isComprehensive']);
        $this->subjects = [];
        $this->chapters = [];
        $this->topics = [];
        if ($value) {
            $this->loadSubjectsForGrade($value);
        }
    }

    public function updatedFieldId($value): void
    {
        $this->reset(['gradeId', 'subjectId', 'chapterId', 'topicId', 'isComprehensive']);
        $this->grades = [];
        $this->subjects = [];
        $this->chapters = [];
        $this->topics = [];
        if ($value && $this->educationLevelId) {
            $this->grades = CcGrade::where('education_level_id', $this->educationLevelId)
                ->where('cc_field_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedSubjectId($value): void
    {
        $this->reset(['chapterId', 'topicId', 'isComprehensive']);
        $this->chapters = [];
        $this->topics = [];
        if ($value) {
            $this->chapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedChapterId($value): void
    {
        $this->reset(['topicId']);
        $this->topics = [];
        if ($value) {
            $this->topics = CcTopic::where('cc_chapter_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedTopicId($value): void
    {
        if ($value) {
            $this->isComprehensive = false;
        }
    }

    public function updatedQuestionCount($value): void
    {
        if ($value === '' || $value === null) {
            return;
        }

        $this->syncQuestionCount($value);
    }

    public function normalizeQuestionCount(): void
    {
        $this->syncQuestionCount($this->questionCount);
    }

    public function incrementQuestionCount(): void
    {
        $this->syncQuestionCount((int)$this->questionCount + 1);
    }

    public function decrementQuestionCount(): void
    {
        $this->syncQuestionCount((int)$this->questionCount - 1);
    }

    public function setComprehensiveMode(): void
    {
        $this->isComprehensive = true;
        $this->topicId = '';
    }

    public function setTopicMode(): void
    {
        $this->isComprehensive = false;
    }

    protected function syncQuestionCount($value): void
    {
        $count = max(1, min(20, (int)$value));
        $this->questionCount = $count;
        for ($i = 0; $i < $count; $i++) {
            if (!isset($this->questionCorrectOptions[$i])) {
                $this->questionCorrectOptions[$i] = 1;
            }
            if (!isset($this->questionDifficulties[$i])) {
                $this->questionDifficulties[$i] = 'medium';
            }
        }
        $this->questionCorrectOptions = array_slice($this->questionCorrectOptions, 0, $count);
        $this->questionDifficulties = array_slice($this->questionDifficulties, 0, $count);
        $this->questionImages = array_slice($this->questionImages, 0, $count);
        $this->explanationImages = array_slice($this->explanationImages, 0, $count);
    }

    // Edit mode image removal
    public function removeQuestionImage(): void
    {
        $this->questionImage = null;
    }

    public function removeExplanationImage(): void
    {
        $this->explanationImage = null;
    }

    // Create mode image removal
    public function removeQuestionImageAt(int $index): void
    {
        unset($this->questionImages[$index]);
        $this->questionImages = array_values($this->questionImages);
    }

    public function removeExplanationImageAt(int $index): void
    {
        unset($this->explanationImages[$index]);
        $this->explanationImages = array_values($this->explanationImages);
    }

    public function save(): void
    {
        $this->normalizeQuestionCount();
        $this->validate();

        $this->savedImagePaths = [];
        try {
            DB::transaction(function () {
                if ($this->isEditMode) {
                    $this->updateQuestion();
                } else {
                    $this->createQuestions();
                }
            });
        } catch (Throwable $exception) {
            foreach ($this->savedImagePaths as $path) {
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            report($exception);
            $message = 'ذخیره سوال کامل نشد. لطفا دوباره تلاش کنید یا لاگ خطا را بررسی کنید.';
            if ($exception instanceof \RuntimeException) {
                $message = 'یکی از تصاویر به WebP با عرض ۱۰۸۰ تبدیل نشد. لطفا تصویر را دوباره بررسی و آپلود کنید.';
            } elseif ($exception instanceof QueryException && str_contains($exception->getMessage(), 'cc_chapter_id')) {
                $message = 'ستون فصل سوال در دیتابیس پیدا نشد. لطفا migrationهای پروژه را اجرا کنید.';
            }
            $this->dispatch('error', $message);
            return;
        }
        $message = $this->isEditMode
            ? 'سوال با موفقیت ویرایش شد.'
            : ($this->questionCount > 1
                ? "{$this->questionCount} سوال با موفقیت ایجاد شد."
                : 'سوال با موفقیت ایجاد شد.');
        $this->dispatch('success', $message);
        if (!$this->isEditMode) {
            $this->resetForm();
        }
    }

    protected function createQuestions(): void
    {
        for ($i = 0; $i < $this->questionCount; $i++) {
            $this->createSingleQuestion(
                $this->questionImages[$i],
                $this->explanationImages[$i] ?? null,
                $this->questionCorrectOptions[$i],
                $this->questionDifficulties[$i]
            );
        }
        $this->questionCode = null; // reset; multiple questions created
    }

    protected function createSingleQuestion($questionImg, $explanationImg, int $correctOpt, string $diff): void
    {
        $code = Question::generateUniqueCode();
        $folderHash = sha1($code . now()->timestamp . uniqid());
        $question = Question::create([
            'code' => $code,
            'subject_id' => $this->subjectId,
            'cc_chapter_id' => $this->chapterId,
            'cc_topic_id' => $this->isComprehensive ? null : $this->topicId,
            'difficulty' => $diff,
            'correct_option' => $correctOpt,
        ]);
        $questionImageName = $this->processAndSaveImage($questionImg, $folderHash);
        $explanationImageName = null;
        if ($explanationImg) {
            $explanationImageName = $this->processAndSaveImage($explanationImg, $folderHash);
        }
        QuestionContent::create([
            'question_id' => $question->id,
            'question_image' => $questionImageName,
            'folder_hash' => $folderHash,
            'explanation_image' => $explanationImageName,
            'body' => '',
            'explanation' => '',
        ]);
    }

    protected function updateQuestion(): void
    {
        $question = Question::findOrFail($this->questionId);
        $question->update([
            'subject_id' => $this->subjectId,
            'cc_chapter_id' => $this->chapterId,
            'cc_topic_id' => $this->isComprehensive ? null : $this->topicId,
            'difficulty' => $this->difficulty,
            'correct_option' => $this->correctOption,
        ]);
        $content = $question->content;
        $folderHash = $content->folder_hash ?? sha1($question->code . now()->timestamp . uniqid());
        $data = ['folder_hash' => $folderHash];
        if ($this->questionImage) {
            if ($content && $content->question_image) {
                $oldPath = public_path("questions/{$content->folder_hash}/{$content->question_image}");
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            $data['question_image'] = $this->processAndSaveImage($this->questionImage, $folderHash);
        }
        if ($this->explanationImage) {
            if ($content && $content->explanation_image) {
                $oldPath = public_path("questions/{$content->folder_hash}/{$content->explanation_image}");
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            $data['explanation_image'] = $this->processAndSaveImage($this->explanationImage, $folderHash);
        }
        if ($content) {
            $content->update($data);
        } else {
            $data['question_id'] = $question->id;
            $data['body'] = '';
            $data['explanation'] = '';
            QuestionContent::create($data);
        }
    }

    /**
     * پردازش و ذخیره تصویر: عرض ثابت ۱۰۸۰ پیکسل، کیفیت WebP ۹۰
     */
    protected function processAndSaveImage($photo, string $folderHash): string
    {
        $path = public_path("questions/{$folderHash}");
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        $manager = new ImageManager(new Driver());
        $filename = sha1($photo->getClientOriginalName() . now()->timestamp . uniqid()) . '.webp';
        $realPath = $photo->getRealPath();
        $image = $manager->read($realPath);
        // عرض ثابت ۱۰۸۰ پیکسل، ارتفاع متناسب (نسبت حفظ می‌شود)
        $image->scale(1080);
        $image->toWebp(90)->save($path . '/' . $filename);
        $finalPath = $path . '/' . $filename;
        $this->ensureProcessedImage($finalPath);
        $this->savedImagePaths[] = $finalPath;
        // پاکسازی فایل موقت
        if ($realPath && file_exists($realPath)) {
            @unlink($realPath);
        }
        return $filename;
    }

    protected function ensureProcessedImage(string $path): void
    {
        $info = getimagesize($path);
        if (!$info || ($info['mime'] ?? null) !== 'image/webp' || (int)$info[0] !== 1080) {
            File::delete($path);
            throw new \RuntimeException('Question image was not converted to required WebP width.');
        }
    }

    /**
     * نگهداری متد قدیمی برای سازگاری با trait
     */
    protected function uploadQuestionImage($photo, string $folderHash, string $type = 'question'): string
    {
        return $this->processAndSaveImage($photo, $folderHash);
    }

    protected function resetForm(): void
    {
        $this->reset([
            'difficulty', 'correctOption', 'questionCode', 'questionId',
            'educationLevelId', 'gradeId', 'fieldId', 'subjectId', 'chapterId', 'topicId', 'isComprehensive',
            'questionImage', 'explanationImage', 'existingQuestionImage', 'existingExplanationImage',
            'questionImages', 'explanationImages', 'questionCorrectOptions', 'questionDifficulties',
        ]);
        $this->difficulty = 'medium';
        $this->correctOption = 1;
        $this->questionCount = 1;
        $this->questionCorrectOptions = [1];
        $this->questionDifficulties = ['medium'];
        $this->grades = [];
        $this->fields = [];
        $this->subjects = [];
        $this->chapters = [];
        $this->topics = [];
        $this->isEditMode = false;
    }

    public function render()
    {
        $difficulties = [
            'easy' => 'آسان',
            'medium' => 'متوسط',
            'hard' => 'سخت',
            'special' => 'ویژه',
        ];
        $showFieldSelect = false;
        if ($this->gradeId) {
            $grade = CcGrade::find($this->gradeId);
            $showFieldSelect = $grade && $grade->grade_number >= 10;
        }
        $showFieldSelect = $this->shouldShowFieldSelect();
        return view('livewire.manager.questions.question-form', compact(
            'difficulties', 'showFieldSelect'
        ))->layout('layouts.manager.app');
    }

    protected function shouldShowFieldSelect(): bool
    {
        return $this->educationLevelNeedsField($this->educationLevelId);
    }

    protected function educationLevelNeedsField($educationLevelId): bool
    {
        if (!$educationLevelId) {
            return false;
        }

        return CcGrade::where('education_level_id', $educationLevelId)
            ->where('is_active', true)
            ->where('grade_number', '>=', 10)
            ->exists();
    }

    protected function loadSubjectsForGrade($gradeId): void
    {
        $grade = CcGrade::find($gradeId);
        if (!$grade) {
            return;
        }

        $this->subjects = CcSubject::where('cc_grade_id', $gradeId)
            ->when($grade->cc_field_id, function ($query) use ($grade) {
                $query->where(function ($q) use ($grade) {
                    $q->where('cc_field_id', $grade->cc_field_id)->orWhereNull('cc_field_id');
                });
            })
            ->orderBy('order')
            ->get();
    }
}
