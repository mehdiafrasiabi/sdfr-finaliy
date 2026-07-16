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
use App\Models\Subject;
use App\Traits\UploadFile;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class QuestionForm extends Component
{
    use WithFileUploads, UploadFile;

    protected const QUESTION_IMAGE_WIDTH = 1080;

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
    // Optional second destination for shared books across grades/fields
    public bool $duplicateForSecondCourse = false;
    public string $secondEducationLevelId = '';
    public string $secondGradeId = '';
    public string $secondFieldId = '';
    public string $secondSubjectId = '';
    public string $secondChapterId = '';
    public string $secondTopicId = '';
    public bool $secondIsComprehensive = false;
    public $secondGrades = [];
    public $secondFields = [];
    public $secondSubjects = [];
    public $secondChapters = [];
    public $secondTopics = [];
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
        if ($this->hasSecondCourse()) {
            $rules['secondEducationLevelId'] = 'required|exists:education_levels,id';
            $rules['secondGradeId'] = 'required|exists:cc_grades,id';
            $rules['secondSubjectId'] = 'required|exists:cc_subjects,id';
            $rules['secondChapterId'] = 'required|exists:cc_chapters,id';
            $rules['secondTopicId'] = $this->secondIsComprehensive ? 'nullable|exists:cc_topics,id' : 'required|exists:cc_topics,id';

            if ($this->secondShouldShowFieldSelect()) {
                $rules['secondFieldId'] = 'required|exists:cc_fields,id';
            }
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
            'secondEducationLevelId.required' => 'انتخاب دوره تحصیلی مقصد دوم الزامی است.',
            'secondGradeId.required' => 'انتخاب پایه مقصد دوم الزامی است.',
            'secondFieldId.required' => 'انتخاب رشته مقصد دوم الزامی است.',
            'secondSubjectId.required' => 'انتخاب درس مقصد دوم الزامی است.',
            'secondChapterId.required' => 'انتخاب فصل مقصد دوم الزامی است.',
            'secondTopicId.required' => 'انتخاب مبحث مقصد دوم الزامی است.',
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

    public function updatedDuplicateForSecondCourse($value): void
    {
        if (!$value) {
            $this->resetSecondCourse();
        }
    }

    public function updatedSecondEducationLevelId($value): void
    {
        $this->reset(['secondGradeId', 'secondFieldId', 'secondSubjectId', 'secondChapterId', 'secondTopicId', 'secondIsComprehensive']);
        $this->secondGrades = [];
        $this->secondFields = [];
        $this->secondSubjects = [];
        $this->secondChapters = [];
        $this->secondTopics = [];

        if ($value) {
            if ($this->educationLevelNeedsField($value)) {
                $this->secondFields = CcField::where('is_active', true)->orderBy('order')->get();
            } else {
                $this->secondGrades = CcGrade::where('education_level_id', $value)
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('cc_field_id')->orWhere('grade_number', '<', 10);
                    })
                    ->orderBy('order')
                    ->get();
            }
        }
    }

    public function updatedSecondFieldId($value): void
    {
        $this->reset(['secondGradeId', 'secondSubjectId', 'secondChapterId', 'secondTopicId', 'secondIsComprehensive']);
        $this->secondGrades = [];
        $this->secondSubjects = [];
        $this->secondChapters = [];
        $this->secondTopics = [];

        if ($value && $this->secondEducationLevelId) {
            $this->secondGrades = CcGrade::where('education_level_id', $this->secondEducationLevelId)
                ->where('cc_field_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedSecondGradeId($value): void
    {
        $this->reset(['secondSubjectId', 'secondChapterId', 'secondTopicId', 'secondIsComprehensive']);
        $this->secondSubjects = [];
        $this->secondChapters = [];
        $this->secondTopics = [];

        if ($value) {
            $this->secondSubjects = $this->subjectsForGrade($value);
        }
    }

    public function updatedSecondSubjectId($value): void
    {
        $this->reset(['secondChapterId', 'secondTopicId', 'secondIsComprehensive']);
        $this->secondChapters = [];
        $this->secondTopics = [];

        if ($value) {
            $this->secondChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedSecondChapterId($value): void
    {
        $this->reset(['secondTopicId']);
        $this->secondTopics = [];

        if ($value) {
            $this->secondTopics = CcTopic::where('cc_chapter_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedSecondTopicId($value): void
    {
        if ($value) {
            $this->secondIsComprehensive = false;
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

    public function setSecondComprehensiveMode(): void
    {
        $this->secondIsComprehensive = true;
        $this->secondTopicId = '';
    }

    public function setSecondTopicMode(): void
    {
        $this->secondIsComprehensive = false;
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

        if ($this->hasSecondCourse() && $this->secondCourseMatchesPrimary()) {
            $this->addError('secondSubjectId', 'مقصد دوم باید با دسته‌بندی اصلی متفاوت باشد.');
            return;
        }

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
            if ($exception instanceof QuestionImageProcessingException) {
                $message = $exception->userMessage();
            } elseif ($exception instanceof QueryException && str_contains($exception->getMessage(), 'subject_id_foreign')) {
                $message = 'درس انتخاب‌شده در جدول subjects پیدا نشد. لطفا نگاشت درس را بررسی کنید.';
            } elseif ($exception instanceof QueryException && str_contains($exception->getMessage(), 'cc_chapter_id')) {
                $message = 'ستون فصل سوال در دیتابیس پیدا نشد. لطفا migrationهای پروژه را اجرا کنید.';
            }
            $this->dispatch('error', $message);
            return;
        }
        $message = $this->isEditMode
            ? 'سوال با موفقیت ویرایش شد.'
            : ($this->createdQuestionCount() > 1
                ? "{$this->createdQuestionCount()} سوال با موفقیت ایجاد شد."
                : 'سوال با موفقیت ایجاد شد.');
        $this->dispatch('success', $message);
        if (!$this->isEditMode) {
            $this->resetForm();
        }
    }

    protected function createQuestions(): void
    {
        $targets = $this->questionTargets();

        for ($i = 0; $i < $this->questionCount; $i++) {
            [$sourceFolderHash, $questionImageName, $explanationImageName] = $this->storeUploadedQuestionAssets(
                $this->questionImages[$i],
                $this->explanationImages[$i] ?? null,
                $i + 1
            );

            foreach ($targets as $targetIndex => $target) {
                $folderHash = $sourceFolderHash;

                if ($targetIndex > 0) {
                    $folderHash = sha1(Question::generateUniqueCode() . now()->timestamp . uniqid());
                    $this->copyQuestionAssets($sourceFolderHash, $folderHash, $questionImageName, $explanationImageName);
                }

                $this->createQuestionRecord(
                    $target,
                    $folderHash,
                    $questionImageName,
                    $explanationImageName,
                    $this->questionCorrectOptions[$i],
                    $this->questionDifficulties[$i]
                );
            }
        }
        $this->questionCode = null; // reset; multiple questions created
    }

    protected function storeUploadedQuestionAssets($questionImg, $explanationImg, int $questionNumber): array
    {
        $code = Question::generateUniqueCode();
        $folderHash = sha1($code . now()->timestamp . uniqid());
        $questionImageName = $this->processAndSaveImage($questionImg, $folderHash, "سوال {$questionNumber}، عکس سوال");
        $explanationImageName = null;

        if ($explanationImg) {
            $explanationImageName = $this->processAndSaveImage($explanationImg, $folderHash, "سوال {$questionNumber}، عکس پاسخ تشریحی");
        }

        return [$folderHash, $questionImageName, $explanationImageName];
    }

    protected function createQuestionRecord(array $target, string $folderHash, string $questionImageName, ?string $explanationImageName, int $correctOpt, string $diff): void
    {
        $subjectId = $this->resolveLegacySubjectId((int) $target['subject_id']);

        $question = Question::create([
            'code' => Question::generateUniqueCode(),
            'subject_id' => $subjectId,
            'cc_chapter_id' => $target['cc_chapter_id'],
            'cc_topic_id' => $target['cc_topic_id'],
            'difficulty' => $diff,
            'correct_option' => $correctOpt,
        ]);

        QuestionContent::create([
            'question_id' => $question->id,
            'question_image' => $questionImageName,
            'folder_hash' => $folderHash,
            'explanation_image' => $explanationImageName,
            'body' => '',
            'explanation' => '',
        ]);
    }

    protected function copyQuestionAssets(string $sourceFolderHash, string $targetFolderHash, string $questionImageName, ?string $explanationImageName): void
    {
        $sourcePath = public_path("questions/{$sourceFolderHash}");
        $targetPath = public_path("questions/{$targetFolderHash}");

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        foreach (array_filter([$questionImageName, $explanationImageName]) as $filename) {
            $sourceFile = "{$sourcePath}/{$filename}";
            $targetFile = "{$targetPath}/{$filename}";

            if (!File::exists($sourceFile) || !File::copy($sourceFile, $targetFile)) {
                throw new \UnexpectedValueException('Question image copy failed.');
            }

            $this->savedImagePaths[] = $targetFile;
        }
    }

    protected function updateQuestion(): void
    {
        $question = Question::findOrFail($this->questionId);
        $subjectId = $this->resolveLegacySubjectId((int) $this->subjectId);
        $question->update([
            'subject_id' => $subjectId,
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
            $data['question_image'] = $this->processAndSaveImage($this->questionImage, $folderHash, 'ویرایش سوال، عکس سوال');
        }
        if ($this->explanationImage) {
            if ($content && $content->explanation_image) {
                $oldPath = public_path("questions/{$content->folder_hash}/{$content->explanation_image}");
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            $data['explanation_image'] = $this->processAndSaveImage($this->explanationImage, $folderHash, 'ویرایش سوال، عکس پاسخ تشریحی');
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
    protected function processAndSaveImage($photo, string $folderHash, ?string $label = null): string
    {
        $label ??= 'تصویر سوال';
        $path = public_path("questions/{$folderHash}");
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        $manager = new ImageManager(new Driver());
        $filename = sha1($photo->getClientOriginalName() . now()->timestamp . uniqid()) . '.webp';
        $realPath = $photo->getRealPath();
        $finalPath = $path . '/' . $filename;

        if (!$realPath || !File::exists($realPath)) {
            throw new QuestionImageProcessingException(
                $label,
                'فایل موقت تصویر پیدا نشد. تصویر را دوباره انتخاب کنید و بعد از کامل شدن آپلود، ذخیره را بزنید.'
            );
        }

        try {
            $image = $manager->read($realPath);
            // عرض ثابت ۱۰۸۰ پیکسل، ارتفاع متناسب (نسبت حفظ می‌شود)
            $image->scale(width: self::QUESTION_IMAGE_WIDTH);
            $image->toWebp(90)->save($finalPath);
        } catch (Throwable $exception) {
            if (File::exists($finalPath)) {
                File::delete($finalPath);
            }

            throw new QuestionImageProcessingException(
                $label,
                'تصویر قابل خواندن یا تبدیل نبود. اگر فایل اسکرین‌شات است، یک بار آن را با فرمت JPG یا PNG ذخیره و دوباره آپلود کنید.',
                previous: $exception
            );
        }

        $this->ensureProcessedImage($finalPath, $label);
        $this->savedImagePaths[] = $finalPath;

        return $filename;
    }

    protected function ensureProcessedImage(string $path, string $label): void
    {
        $info = getimagesize($path);

        if (!$info) {
            File::delete($path);
            throw new QuestionImageProcessingException($label, 'فایل خروجی ساخته شد، اما به عنوان تصویر معتبر خوانده نشد.');
        }

        $mime = $info['mime'] ?? null;
        $width = (int)($info[0] ?? 0);

        if ($mime !== 'image/webp' || $width !== self::QUESTION_IMAGE_WIDTH) {
            File::delete($path);
            throw new QuestionImageProcessingException(
                $label,
                "خروجی باید WebP با عرض " . self::QUESTION_IMAGE_WIDTH . " پیکسل باشد، اما خروجی {$width} پیکسل و {$mime} شد."
            );
        }
    }

    /**
     * نگهداری متد قدیمی برای سازگاری با trait
     */
    protected function uploadQuestionImage($photo, string $folderHash, string $type = 'question'): string
    {
        return $this->processAndSaveImage($photo, $folderHash, $type === 'explanation' ? 'عکس پاسخ تشریحی' : 'عکس سوال');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'difficulty', 'correctOption', 'questionCode', 'questionId',
            'educationLevelId', 'gradeId', 'fieldId', 'subjectId', 'chapterId', 'topicId', 'isComprehensive',
            'duplicateForSecondCourse', 'secondEducationLevelId', 'secondGradeId', 'secondFieldId', 'secondSubjectId',
            'secondChapterId', 'secondTopicId', 'secondIsComprehensive',
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
        $this->secondGrades = [];
        $this->secondFields = [];
        $this->secondSubjects = [];
        $this->secondChapters = [];
        $this->secondTopics = [];
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
        $showFieldSelect = $this->shouldShowFieldSelect();
        $secondShowFieldSelect = $this->secondShouldShowFieldSelect();
        $createdQuestionCount = $this->createdQuestionCount();

        return view('livewire.manager.questions.question-form', compact(
            'difficulties', 'showFieldSelect', 'secondShowFieldSelect', 'createdQuestionCount'
        ))->layout('layouts.manager.app');
    }

    protected function shouldShowFieldSelect(): bool
    {
        return $this->educationLevelNeedsField($this->educationLevelId);
    }

    protected function secondShouldShowFieldSelect(): bool
    {
        return $this->educationLevelNeedsField($this->secondEducationLevelId);
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
        $this->subjects = $this->subjectsForGrade($gradeId);
    }

    protected function subjectsForGrade($gradeId)
    {
        $grade = CcGrade::find($gradeId);
        if (!$grade) {
            return collect();
        }

        return CcSubject::where('cc_grade_id', $gradeId)
            ->when($grade->cc_field_id, function ($query) use ($grade) {
                $query->where(function ($q) use ($grade) {
                    $q->where('cc_field_id', $grade->cc_field_id)->orWhereNull('cc_field_id');
                });
            })
            ->orderBy('order')
            ->get();
    }

    public function temporaryPreviewUrl($file): ?string
    {
        if (!$file instanceof TemporaryUploadedFile) {
            return null;
        }

        try {
            return $file->isPreviewable() ? $file->temporaryUrl() : null;
        } catch (Throwable) {
            return null;
        }
    }

    protected function hasSecondCourse(): bool
    {
        return !$this->isEditMode && $this->duplicateForSecondCourse;
    }

    protected function questionTargets(): array
    {
        $targets = [$this->targetPayload($this->subjectId, $this->chapterId, $this->topicId, $this->isComprehensive)];

        if ($this->hasSecondCourse()) {
            $targets[] = $this->targetPayload(
                $this->secondSubjectId,
                $this->secondChapterId,
                $this->secondTopicId,
                $this->secondIsComprehensive
            );
        }

        return $targets;
    }

    protected function targetPayload($subjectId, $chapterId, $topicId, bool $isComprehensive): array
    {
        return [
            'subject_id' => $subjectId,
            'cc_chapter_id' => $chapterId,
            'cc_topic_id' => $isComprehensive ? null : $topicId,
        ];
    }

    protected function resolveLegacySubjectId(int $ccSubjectId): int
    {
        $ccSubject = CcSubject::find($ccSubjectId);

        if (!$ccSubject) {
            throw new \RuntimeException('درس انتخاب‌شده پیدا نشد.');
        }

        $legacyName = $this->resolveLegacySubjectName($ccSubject->name);

        return (int) Subject::firstOrCreate(
            ['name' => $legacyName],
            ['is_active' => true]
        )->id;
    }

    protected function resolveLegacySubjectName(string $ccSubjectName): string
    {
        $name = str_replace(["\u{200C}", "\u{200D}"], '', $ccSubjectName);
        $name = trim(preg_replace('/\d+$/u', '', $name));
        $name = preg_replace('/\s+/u', ' ', $name) ?: $ccSubjectName;

        $map = [
            'دین و زندگی' => 'دین و زندگی',
            'دینی' => 'دین و زندگی',
            'زیست' => 'زیست‌شناسی',
            'فارسی' => 'ادبیات فارسی',
            'زبان انگلیسی' => 'زبان انگلیسی',
            'زبان' => 'زبان انگلیسی',
            'حسابان' => 'حسابان',
            'هندسه' => 'هندسه',
            'گسسته' => 'گسسته',
            'فیزیک' => 'فیزیک',
            'شیمی' => 'شیمی',
            'ریاضی و آمار' => 'آمار و احتمال',
            'آمار' => 'آمار و احتمال',
            'ریاضی' => 'ریاضی',
            'فلسفه' => 'فلسفه و منطق',
            'منطق' => 'فلسفه و منطق',
            'جامعه' => 'جامعه‌شناسی',
            'روانشناسی' => 'روانشناسی',
            'تاریخ' => 'تاریخ',
            'جغرافیا' => 'جغرافیا',
            'اقتصاد' => 'اقتصاد',
            'سلامت' => 'سلامت و بهداشت',
            'کارگاه کارآفرینی' => 'اقتصاد',
            'تفکر و سواد رسانه' => 'علوم اجتماعی',
            'مدیریت خانواده' => 'علوم اجتماعی',
            'آمادگی دفاعی' => 'علوم اجتماعی',
            'انسان و محیط زیست' => 'علوم اجتماعی',
            'علوم و فنون ادبی' => 'ادبیات فارسی',
        ];

        foreach ($map as $needle => $legacyName) {
            if (str_contains($name, $needle)) {
                return $legacyName;
            }
        }

        return $name;
    }

    protected function secondCourseMatchesPrimary(): bool
    {
        return $this->targetPayload($this->subjectId, $this->chapterId, $this->topicId, $this->isComprehensive)
            == $this->targetPayload($this->secondSubjectId, $this->secondChapterId, $this->secondTopicId, $this->secondIsComprehensive);
    }

    protected function createdQuestionCount(): int
    {
        return (int)$this->questionCount * ($this->hasSecondCourse() ? 2 : 1);
    }

    protected function resetSecondCourse(): void
    {
        $this->secondEducationLevelId = '';
        $this->secondGradeId = '';
        $this->secondFieldId = '';
        $this->secondSubjectId = '';
        $this->secondChapterId = '';
        $this->secondTopicId = '';
        $this->secondIsComprehensive = false;
        $this->secondGrades = [];
        $this->secondFields = [];
        $this->secondSubjects = [];
        $this->secondChapters = [];
        $this->secondTopics = [];
    }
}

class QuestionImageProcessingException extends \RuntimeException
{
    public function __construct(
        protected string $imageLabel,
        protected string $reason,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct("{$imageLabel}: {$reason}", $code, $previous);
    }

    public function userMessage(): string
    {
        return "مشکل تبدیل تصویر در {$this->imageLabel}: {$this->reason} راهنما: حجم تصویر حداکثر ۱۰ مگابایت باشد، فایل را با فرمت JPG/PNG/WebP معمولی دوباره ذخیره کنید، و قبل از ذخیره نهایی صبر کنید آپلود کامل شود.";
    }
}
