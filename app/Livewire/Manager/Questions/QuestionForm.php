<?php

namespace App\Livewire\Manager\Questions;



use App\Models\Question;

use App\Models\QuestionContent;

use App\Models\QuestionOption;

use App\Models\Subject;

use App\Traits\UploadFile;

use Illuminate\Support\Facades\DB;

use Livewire\Component;

use Livewire\WithFileUploads;



class QuestionForm extends Component

{

    use WithFileUploads, UploadFile;



    // Question basic info

    public ?string $questionCode = null;

    public ?int $questionId = null;

    public string $subject_id = '';

    public string $difficulty = 'medium';

    public string $direction = 'rtl';



    // Question content

    public string $body = '';

    public string $explanation = '';



    // Options (4 options)

    public array $options = [

        1 => ['content' => '', 'is_correct' => false],

        2 => ['content' => '', 'is_correct' => false],

        3 => ['content' => '', 'is_correct' => false],

        4 => ['content' => '', 'is_correct' => false],

    ];



    public bool $isEditMode = false;



    protected function rules(): array

    {

        return [

            'subject_id' => 'required|exists:subjects,id',

            'difficulty' => 'required|in:easy,medium,hard,special',

            'direction' => 'required|in:rtl,ltr',

            'body' => 'required|string|min:10',

            'explanation' => 'nullable|string',

            'options.1.content' => 'required|string',

            'options.2.content' => 'required|string',

            'options.3.content' => 'required|string',

            'options.4.content' => 'required|string',

        ];

    }



    protected function messages(): array

    {

        return [

            'subject_id.required' => 'انتخاب درس الزامی است.',

            'subject_id.exists' => 'درس انتخاب شده معتبر نیست.',

            'difficulty.required' => 'انتخاب سطح سختی الزامی است.',

            'direction.required' => 'انتخاب جهت نمایش الزامی است.',

            'body.required' => 'متن سوال الزامی است.',

            'body.min' => 'متن سوال باید حداقل ۱۰ کاراکتر باشد.',

            'options.1.content.required' => 'گزینه ۱ الزامی است.',

            'options.2.content.required' => 'گزینه ۲ الزامی است.',

            'options.3.content.required' => 'گزینه ۳ الزامی است.',

            'options.4.content.required' => 'گزینه ۴ الزامی است.',

        ];

    }



    public function mount(?string $code = null): void

    {

        if ($code) {

            $this->loadQuestion($code);

        }

    }



    protected function loadQuestion(string $code): void

    {

        $question = Question::with(['content', 'options', 'subject'])

            ->where('code', $code)

            ->firstOrFail();



        $this->isEditMode = true;

        $this->questionId = $question->id;

        $this->questionCode = $question->code;

        $this->subject_id = (string) $question->subject_id;

        $this->difficulty = $question->difficulty;

        $this->direction = $question->direction;

        $this->body = $question->content?->body ?? '';

        $this->explanation = $question->content?->explanation ?? '';



        foreach ($question->options as $option) {

            $this->options[$option->option_number] = [

                'content' => $option->content,

                'is_correct' => $option->is_correct,

            ];

        }

    }



    public function setCorrectOption(int $optionNumber): void

    {

        foreach ($this->options as $key => $option) {

            $this->options[$key]['is_correct'] = ($key === $optionNumber);

        }

    }



    public function save(): void

    {
        $this->validate();



        // Check at least one correct option

        $hasCorrectOption = collect($this->options)->contains('is_correct', true);

        if (!$hasCorrectOption) {

            $this->addError('options', 'لطفاً یک گزینه را به عنوان پاسخ صحیح انتخاب کنید.');

            return;

        }



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



        $question = Question::create([

            'code' => $code,

            'subject_id' => $this->subject_id,

            'difficulty' => $this->difficulty,

            'direction' => $this->direction,

        ]);



        QuestionContent::create([

            'question_id' => $question->id,

            'body' => $this->body,

            'explanation' => $this->explanation,

        ]);



        foreach ($this->options as $number => $option) {

            QuestionOption::create([

                'question_id' => $question->id,

                'option_number' => $number,

                'content' => $option['content'],

                'is_correct' => $option['is_correct'],

            ]);

        }



        $this->questionCode = $code;

    }



    protected function updateQuestion(): void

    {

        $question = Question::findOrFail($this->questionId);



        $question->update([

            'subject_id' => $this->subject_id,

            'difficulty' => $this->difficulty,

            'direction' => $this->direction,

        ]);



        $question->content()->updateOrCreate(

            ['question_id' => $question->id],

            [

                'body' => $this->body,

                'explanation' => $this->explanation,

            ]

        );



        foreach ($this->options as $number => $option) {

            QuestionOption::updateOrCreate(

                [

                    'question_id' => $question->id,

                    'option_number' => $number,

                ],

                [

                    'content' => $option['content'],

                    'is_correct' => $option['is_correct'],

                ]

            );

        }

    }



    protected function resetForm(): void

    {

        $this->reset([

            'subject_id', 'difficulty', 'direction',

            'body', 'explanation', 'questionCode', 'questionId'

        ]);

        $this->difficulty = 'medium';

        $this->direction = 'rtl';

        $this->options = [

            1 => ['content' => '', 'is_correct' => false],

            2 => ['content' => '', 'is_correct' => false],

            3 => ['content' => '', 'is_correct' => false],

            4 => ['content' => '', 'is_correct' => false],

        ];

        $this->isEditMode = false;

    }



    public function render()

    {

        $subjects = Subject::active()->orderBy('name')->get();

        $difficulties = [

            'easy' => 'آسان',

            'medium' => 'متوسط',

            'hard' => 'سخت',

            'special' => 'ویژه',

        ];

        $directions = [

            'rtl' => 'از راست به چپ',

            'ltr' => 'از چپ به راست',

        ];



        return view('livewire.manager.questions.question-form', compact(

            'subjects', 'difficulties', 'directions'

        ))->layout('layouts.manager.app');

    }

}
