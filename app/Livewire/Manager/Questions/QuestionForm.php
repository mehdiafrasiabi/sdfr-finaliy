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


    // Question basic info

    public ?string $questionCode = null;

    public ?int $questionId = null;

    public string $difficulty = 'medium';

    public int $correctOption = 1; // گزینه صحیح (1-4)


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


    // Image uploads

    public $questionImage;

    public $explanationImage;


    // Existing images for edit mode

    public ?string $existingQuestionImage = null;

    public ?string $existingExplanationImage = null;


    public bool $isEditMode = false;


    protected function rules(): array

    {

        $rules = [

            'educationLevelId' => 'required|exists:education_levels,id',

            'gradeId' => 'required|exists:cc_grades,id',

            'subjectId' => 'required|exists:cc_subjects,id',

            'chapterId' => 'required|exists:cc_chapters,id',

            'topicId' => 'required|exists:cc_topics,id',

            'difficulty' => 'required|in:easy,medium,hard,special',

            'correctOption' => 'required|integer|min:1|max:4',

        ];


        // اگر در حالت ویرایش نیست یا عکس موجود ندارد، عکس سوال اجباری است

        if (!$this->isEditMode || !$this->existingQuestionImage) {

            $rules['questionImage'] = 'required|image|max:5120'; // max 5MB

        } else {

            $rules['questionImage'] = 'nullable|image|max:5120';

        }


        $rules['explanationImage'] = 'nullable|image|max:5120';


        return $rules;

    }


    protected function messages(): array

    {

        return [

            'educationLevelId.required' => 'انتخاب دوره تحصیلی الزامی است.',

            'gradeId.required' => 'انتخاب پایه الزامی است.',

            'subjectId.required' => 'انتخاب درس الزامی است.',

            'chapterId.required' => 'انتخاب فصل الزامی است.',

            'topicId.required' => 'انتخاب مبحث الزامی است.',

            'difficulty.required' => 'انتخاب سطح سختی الزامی است.',

            'correctOption.required' => 'انتخاب گزینه صحیح الزامی است.',

            'correctOption.min' => 'گزینه صحیح باید بین ۱ تا ۴ باشد.',

            'correctOption.max' => 'گزینه صحیح باید بین ۱ تا ۴ باشد.',

            'questionImage.required' => 'آپلود عکس سوال الزامی است.',

            'questionImage.image' => 'فایل باید تصویر باشد.',

            'questionImage.max' => 'حجم تصویر نباید بیشتر از ۵ مگابایت باشد.',

            'explanationImage.image' => 'فایل باید تصویر باشد.',

            'explanationImage.max' => 'حجم تصویر نباید بیشتر از ۵ مگابایت باشد.',

        ];

    }


    public function mount(?string $code = null): void

    {

        $this->educationLevels = EducationLevel::where('is_active', true)->orderBy('order')->get();


        if ($code) {

            $this->loadQuestion($code);

        }

    }


    protected function loadQuestion(string $code): void

    {

        $question = Question::with(['content', 'topic.chapter.subject.grade.educationLevel'])
            ->where('code', $code)
            ->firstOrFail();


        $this->isEditMode = true;

        $this->questionId = $question->id;

        $this->questionCode = $question->code;

        $this->difficulty = $question->difficulty;

        $this->correctOption = $question->correct_option ?? 1;


        // Load hierarchical data

        if ($question->topic) {

            $topic = $question->topic;

            $chapter = $topic->chapter;

            $subject = $chapter->subject;

            $grade = $subject->grade;

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


        // Load existing images

        if ($question->content) {

            $this->existingQuestionImage = $question->content->question_image_url;

            $this->existingExplanationImage = $question->content->explanation_image_url;

        }

    }


    // Cascade dropdowns

    public function updatedEducationLevelId($value): void

    {

        $this->reset(['gradeId', 'fieldId', 'subjectId', 'chapterId', 'topicId']);

        $this->grades = [];

        $this->fields = [];

        $this->subjects = [];

        $this->chapters = [];

        $this->topics = [];


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

        $this->fields = [];

        $this->subjects = [];

        $this->chapters = [];

        $this->topics = [];


        if ($value) {

            $grade = CcGrade::find($value);


            // اگر پایه ۱۰ یا بالاتر است، رشته‌ها را نمایش بده

            if ($grade && $grade->grade_number >= 10) {

                $this->fields = CcField::where('is_active', true)
                    ->orderBy('order')
                    ->get();

            } else {

                // برای پایه‌های زیر ۱۰، درس‌ها را مستقیماً نمایش بده

                $this->subjects = CcSubject::where('cc_grade_id', $value)
                    ->orderBy('order')
                    ->get();

            }

        }

    }


    public function updatedFieldId($value): void

    {

        $this->reset(['subjectId', 'chapterId', 'topicId']);

        $this->subjects = [];

        $this->chapters = [];

        $this->topics = [];


        if ($value && $this->gradeId) {

            $this->subjects = CcSubject::where('cc_grade_id', $this->gradeId)
                ->where(function ($q) use ($value) {

                    $q->where('cc_field_id', $value)
                        ->orWhereNull('cc_field_id');

                })
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedSubjectId($value): void

    {

        $this->reset(['chapterId', 'topicId']);

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


    public function removeQuestionImage(): void

    {

        $this->questionImage = null;

    }


    public function removeExplanationImage(): void

    {

        $this->explanationImage = null;

    }


    public function save(): void

    {

        $this->validate();


        DB::transaction(function () {

            if ($this->isEditMode) {

                $this->updateQuestion();

            } else {

                $this->createQuestion();

            }

        });


        $this->dispatch('success', $this->isEditMode ? 'سوال با موفقیت ویرایش شد.' : 'سوال با موفقیت ایجاد شد.');


        if (!$this->isEditMode) {

            $this->resetForm();

        }

    }


    protected function createQuestion(): void

    {

        $code = Question::generateUniqueCode();

        $folderHash = sha1($code . now()->timestamp . uniqid());


        $question = Question::create([

            'code' => $code,

            'subject_id' => $this->subjectId,

            'cc_topic_id' => $this->topicId,

            'difficulty' => $this->difficulty,

            'correct_option' => $this->correctOption,

        ]);


        // آپلود عکس‌ها

        $questionImageName = $this->uploadQuestionImage($this->questionImage, $folderHash);

        $explanationImageName = null;


        if ($this->explanationImage) {

            $explanationImageName = $this->uploadQuestionImage($this->explanationImage, $folderHash, 'explanation');

        }


        QuestionContent::create([

            'question_id' => $question->id,

            'question_image' => $questionImageName,

            'folder_hash' => $folderHash,

            'explanation_image' => $explanationImageName,

            'body' => '', // برای سازگاری با migration قبلی

            'explanation' => '',

        ]);


        $this->questionCode = $code;

    }


    protected function updateQuestion(): void

    {

        $question = Question::findOrFail($this->questionId);


        $question->update([

            'subject_id' => $this->subjectId,

            'cc_topic_id' => $this->topicId,

            'difficulty' => $this->difficulty,

            'correct_option' => $this->correctOption,

        ]);


        $content = $question->content;

        $folderHash = $content->folder_hash ?? sha1($question->code . now()->timestamp . uniqid());


        $data = ['folder_hash' => $folderHash];


        // آپلود عکس جدید سوال اگر وجود دارد

        if ($this->questionImage) {

            // حذف عکس قبلی

            if ($content && $content->question_image) {

                $oldPath = public_path("questions/{$content->folder_hash}/{$content->question_image}");

                if (File::exists($oldPath)) {

                    File::delete($oldPath);

                }

            }

            $data['question_image'] = $this->uploadQuestionImage($this->questionImage, $folderHash);

        }


        // آپلود عکس جدید پاسخ تشریحی اگر وجود دارد

        if ($this->explanationImage) {

            // حذف عکس قبلی

            if ($content && $content->explanation_image) {

                $oldPath = public_path("questions/{$content->folder_hash}/{$content->explanation_image}");

                if (File::exists($oldPath)) {

                    File::delete($oldPath);

                }

            }

            $data['explanation_image'] = $this->uploadQuestionImage($this->explanationImage, $folderHash, 'explanation');

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
     * آپلود و تبدیل عکس به WebP
     */

    protected function uploadQuestionImage($photo, string $folderHash, string $type = 'question'): string

    {

        $path = public_path("questions/{$folderHash}");


        if (!File::exists($path)) {

            File::makeDirectory($path, 0755, true);

        }


        $manager = new ImageManager(new Driver());


        // نام فایل hash شده

        $filename = sha1($photo->getClientOriginalName() . now()->timestamp . uniqid()) . '.webp';


        $image = $manager->read($photo->getRealPath());


        // محدود کردن سایز به 1200 پیکسل برای کیفیت خوب در همه سایزها

        $image->scaleDown(1200, 1200);


        $image->toWebp(85)->save($path . '/' . $filename);


        // حذف فایل temp

        if (file_exists($photo->getRealPath())) {

            unlink($photo->getRealPath());

        }


        return $filename;

    }


    protected function resetForm(): void

    {

        $this->reset([

            'difficulty', 'correctOption', 'questionCode', 'questionId',

            'educationLevelId', 'gradeId', 'fieldId', 'subjectId', 'chapterId', 'topicId',

            'questionImage', 'explanationImage', 'existingQuestionImage', 'existingExplanationImage'

        ]);

        $this->difficulty = 'medium';

        $this->correctOption = 1;

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


        // بررسی نیاز به نمایش فیلد رشته

        $showFieldSelect = false;

        if ($this->gradeId) {

            $grade = CcGrade::find($this->gradeId);

            $showFieldSelect = $grade && $grade->grade_number >= 10;

        }


        return view('livewire.manager.questions.question-form', compact(

            'difficulties', 'showFieldSelect'

        ))->layout('layouts.manager.app');

    }

}
