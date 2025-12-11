<?php

namespace App\Livewire\Manager\Exam;

use App\Models\QuestionBank;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionManager extends Component
{
    use WithPagination;

    public string $grade = 'twelfth';
    public string $major = 'math';
    public string $level = 'easy';
    public string $question = '';
    public array $options = [
        'option_one' => '',
        'option_two' => '',
        'option_three' => '',
        'option_four' => '',
    ];
    public string $correct_option = '';
    public string $answer_explanation = '';

    protected function rules(): array
    {
        return [
            'grade' => 'required|in:tenth,eleventh,twelfth',
            'major' => 'required|in:math,experimental,humanities',
            'level' => 'required|in:easy,medium,hard,special',
            'question' => 'required|string|min:10',
            'options.option_one' => 'nullable|string',
            'options.option_two' => 'nullable|string',
            'options.option_three' => 'nullable|string',
            'options.option_four' => 'nullable|string',
            'correct_option' => 'nullable|in:1,2,3,4',
            'answer_explanation' => 'nullable|string',
        ];
    }

    public function save(): void
    {
        $this->validate();

        QuestionBank::create([
            'grade' => $this->grade,
            'major' => $this->major,
            'level' => $this->level,
            'question' => $this->question,
            'option_one' => $this->options['option_one'] ?? null,
            'option_two' => $this->options['option_two'] ?? null,
            'option_three' => $this->options['option_three'] ?? null,
            'option_four' => $this->options['option_four'] ?? null,
            'correct_option' => $this->correct_option ?: null,
            'answer_explanation' => $this->answer_explanation ?: null,
            'created_by' => auth()->id(),
        ]);

        $this->reset([
            'question',
            'options',
            'correct_option',
            'answer_explanation',
        ]);

        $this->options = [
            'option_one' => '',
            'option_two' => '',
            'option_three' => '',
            'option_four' => '',
        ];

        session()->flash('success', 'سوال با موفقیت ذخیره شد.');
        $this->resetPage();
    }

    public function render()
    {
        $questions = QuestionBank::latest()->paginate(10);

        return view('livewire.manager.exam.question-manager', [
            'questions' => $questions,
        ])->layout('layouts.manager.app');
    }
}
