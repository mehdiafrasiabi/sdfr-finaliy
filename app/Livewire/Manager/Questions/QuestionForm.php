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

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;

use Intervention\Image\ImageManager;

use Intervention\Image\Drivers\Gd\Driver;

use Livewire\Component;

use Livewire\WithFileUploads;


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

    public int $questionCount = 1;

    public array $questionImages = [];

    public array $explanationImages = [];

    public array $questionCorrectOptions = [];

    public array $questionDifficulties = [];


    public bool $isEditMode = false;


    protected function rules(): array

    {

        $rules = [

            'educationLevelId' => 'required|exists:education_levels,id',

            'gradeId'          => 'required|exists:cc_grades,id',

            'subjectId'        => 'required|exists:cc_subjects,id',

            'chapterId'        => 'required|exists:cc_chapters,id',

            'topicId'          => 'required|exists:cc_topics,id',

        ];


        if ($this->isEditMode) {

            $rules['difficulty']    = 'required|in:easy,medium,hard,special';

            $rules['correctOption'] = 'required|integer|min:1|max:4';

            if (!$this->existingQuestionImage) {

                $rules['questionImage'] = 'required|image|max:10240';

            } else {

                $rules['questionImage'] = 'nullable|image|max:10240';

            }

            $rules['explanationImage'] = 'nullable|image|max:10240';

        } else {

            for ($i = 0; $i < $this->questionCount; $i++) {

                $rules["questionImages.$i"]        = 'required|image|max:10240';

                $rules["explanationImages.$i"]     = 'nullable|image|max:10240';

                $rules["questionCorrectOptions.$i"] = 'required|integer|min:1|max:4';

                $rules["questionDifficulties.$i"]  = 'required|in:easy,medium,hard,special';

            }

        }


        return $rules;

    }


    protected function messages(): array

    {

        return [

            'educationLevelId.required'         => 'انتخاب دوره تحصیلی الزامی است.',

            'gradeId.required'                  => 'انتخاب پایه الزامی است.',

            'subjectId.required'                => 'انتخاب درس الزامی است.',

            'chapterId.required'                => 'انتخاب فصل الزامی است.',

            'topicId.required'                  => 'انتخاب مبحث الزامی است.',

            'difficulty.required'               => 'انتخاب سطح سختی الزامی است.',

            'correctOption.required'            => 'انتخاب گزینه صحیح الزامی است.',

            'correctOption.min'                 => 'گزینه صحیح باید بین ۱ تا ۴ باشد.',

            'correctOption.max'                 => 'گزینه صحیح باید بین ۱ تا ۴ باشد.',

            'questionImage.required'            => 'آپلود عکس سوال الزامی است.',

            'questionImage.image'               => 'فایل باید تصویر باشد.',

            'questionImage.max'                 => 'حجم تصویر نباید بیشتر از ۱۰ مگابایت باشد.',

            'questionImages.*.required'         => 'آپلود عکس سوال الزامی است.',

            'questionImages.*.image'            => 'فایل باید تصویر باشد.',

            'questionImages.*.max'              => 'حجم تصویر نباید بیشتر از ۱۰ مگابایت باشد.',

            'explanationImages.*.image'         => 'فایل باید تصویر باشد.',

            'explanationImages.*.max'           => 'حجم تصویر نباید بیشتر از ۱۰ مگابایت باشد.',

            'questionCorrectOptions.*.required' => 'انتخاب گزینه صحیح الزامی است.',

            'questionCorrectOptions.*.min'      => 'گزینه صحیح باید بین ۱ تا ۴ باشد.',

            'questionCorrectOptions.*.max'      => 'گزینه صحیح باید بین ۱ تا ۴ باشد.',

            'questionDifficulties.*.required'   => 'انتخاب سطح سختی الزامی است.',

        ];

    }


    public function mount(?string $code = null): void

    {

        $this->educationLevels = EducationLevel::where('is_active', true)->orderBy('order')->get();

        $this->questionCorrectOptions = [1];

        $this->questionDifficulties   = ['medium'];


        if ($code) {

            $this->loadQuestion($code);

        }

    }


    protected function loadQuestion(string $code): void

    {

        $question = Question::with(['content', 'topic.chapter.subject.grade.educationLevel'])

            ->where('code', $code)

            ->firstOrFail();


        $this->isEditMode   = true;

        $this->questionId   = $question->id;

        $this->questionCode = $question->code;

        $this->difficulty   = $question->difficulty;

        $this->correctOption = $question->correct_option ?? 1;


        if ($question->topic) {

            $topic         = $question->topic;

            $chapter       = $topic->chapter;

            $subject       = $chapter->subject;

            $grade         = $subject->grade;

            $educationLevel = $grade->educationLevel;


            $this->educationLevelId = (string)$educationLevel->id;

            $this->updatedEducationLevelId($this->educationLevelId);


            $this->gradeId = (string)$grade->id;

            $this->updatedGradeId($this->gradeId);


            if ($subject->cc_field_id) {

                $this->fieldId = (string)$subject->cc_field_id;

                $this->updatedFieldId($this->fieldId);

            }


            $this->subjectId = (string)$subject->id;

            $this->updatedSubjectId($this->subjectId);


            $this->chapterId = (string)$chapter->id;

            $this->updatedChapterId($this->chapterId);


            $this->topicId = (string)$topic->id;

        }


        if ($question->content) {

            $this->existingQuestionImage    = $question->content->question_image_url;

            $this->existingExplanationImage = $question->content->explanation_image_url;

        }

    }


    // Cascade dropdowns

    public function updatedEducationLevelId($value): void

    {

        $this->reset(['gradeId', 'fieldId', 'subjectId', 'chapterId', 'topicId']);

        $this->grades   = [];

        $this->fields   = [];

        $this->subjects = [];

        $this->chapters = [];

        $this->topics   = [];


        if ($value) {

            $this->grades = CcGrade::where('education_level_id', $value)

                ->where('is_active', true)

                ->orderBy('order')

                ->get();

        }

    }


    public function updatedGradeId($value): void

    {

        $this->reset(['fieldId', 'subjectId', 'chapterId', 'topicId']);

        $this->fields   = [];

        $this->subjects = [];

        $this->chapters = [];

        $this->topics   = [];


        if ($value) {

            $grade = CcGrade::find($value);

            if ($grade && $grade->grade_number >= 10) {

                $this->fields = CcField::where('is_active', true)->orderBy('order')->get();

            } else {

                $this->subjects = CcSubject::where('cc_grade_id', $value)->orderBy('order')->get();

            }

        }

    }


    public function updatedFieldId($value): void

    {

        $this->reset(['subjectId', 'chapterId', 'topicId']);

        $this->subjects = [];

        $this->chapters = [];

        $this->topics   = [];


        if ($value && $this->gradeId) {

            $this->subjects = CcSubject::where('cc_grade_id', $this->gradeId)

                ->where(function ($q) use ($value) {

                    $q->where('cc_field_id', $value)->orWhereNull('cc_field_id');

                })

                ->orderBy('order')

                ->get();

        }

    }


    public function updatedSubjectId($value): void

    {

        $this->reset(['chapterId', 'topicId']);

        $this->chapters = [];

        $this->topics   = [];


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


    public function updatedQuestionCount($value): void

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

        $this->questionDifficulties   = array_slice($this->questionDifficulties, 0, $count);

        $this->questionImages         = array_slice($this->questionImages, 0, $count);

        $this->explanationImages      = array_slice($this->explanationImages, 0, $count);

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

        $this->validate();


        DB::transaction(function () {

            if ($this->isEditMode) {

                $this->updateQuestion();

            } else {

                $this->createQuestions();

            }

        });


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

        $code       = Question::generateUniqueCode();

        $folderHash = sha1($code . now()->timestamp . uniqid());


        $question = Question::create([

            'code'          => $code,

            'subject_id'    => $this->subjectId,

            'cc_topic_id'   => $this->topicId,

            'difficulty'    => $diff,

            'correct_option' => $correctOpt,

        ]);


        $questionImageName    = $this->processAndSaveImage($questionImg, $folderHash);

        $explanationImageName = null;


        if ($explanationImg) {

            $explanationImageName = $this->processAndSaveImage($explanationImg, $folderHash);

        }


        QuestionContent::create([

            'question_id'      => $question->id,

            'question_image'   => $questionImageName,

            'folder_hash'      => $folderHash,

            'explanation_image' => $explanationImageName,

            'body'             => '',

            'explanation'      => '',

        ]);

    }


    protected function updateQuestion(): void

    {

        $question = Question::findOrFail($this->questionId);


        $question->update([

            'subject_id'    => $this->subjectId,

            'cc_topic_id'   => $this->topicId,

            'difficulty'    => $this->difficulty,

            'correct_option' => $this->correctOption,

        ]);


        $content    = $question->content;

        $folderHash = $content->folder_hash ?? sha1($question->code . now()->timestamp . uniqid());

        $data       = ['folder_hash' => $folderHash];


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

            $data['body']        = '';

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


        $manager  = new ImageManager(new Driver());

        $filename = sha1($photo->getClientOriginalName() . now()->timestamp . uniqid()) . '.webp';

        $image    = $manager->read($photo->getRealPath());


        // عرض ثابت ۱۰۸۰ پیکسل، ارتفاع متناسب (نسبت حفظ می‌شود)

        $image->scaleDown(1080);


        $image->toWebp(90)->save($path . '/' . $filename);


        // پاکسازی فایل موقت

        $realPath = $photo->getRealPath();

        if ($realPath && file_exists($realPath)) {

            @unlink($realPath);

        }


        return $filename;

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

            'educationLevelId', 'gradeId', 'fieldId', 'subjectId', 'chapterId', 'topicId',

            'questionImage', 'explanationImage', 'existingQuestionImage', 'existingExplanationImage',

            'questionImages', 'explanationImages', 'questionCorrectOptions', 'questionDifficulties',

        ]);

        $this->difficulty             = 'medium';

        $this->correctOption          = 1;

        $this->questionCount          = 1;

        $this->questionCorrectOptions = [1];

        $this->questionDifficulties   = ['medium'];

        $this->grades   = [];

        $this->fields   = [];

        $this->subjects = [];

        $this->chapters = [];

        $this->topics   = [];

        $this->isEditMode = false;

    }


    public function render()

    {

        $difficulties = [

            'easy'    => 'آسان',

            'medium'  => 'متوسط',

            'hard'    => 'سخت',

            'special' => 'ویژه',

        ];


        $showFieldSelect = false;

        if ($this->gradeId) {

            $grade           = CcGrade::find($this->gradeId);

            $showFieldSelect = $grade && $grade->grade_number >= 10;

        }


        return view('livewire.manager.questions.question-form', compact(

            'difficulties', 'showFieldSelect'

        ))->layout('layouts.manager.app');

    }

}
