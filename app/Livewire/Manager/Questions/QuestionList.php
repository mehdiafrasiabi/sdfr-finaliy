<?php

namespace App\Livewire\Manager\Questions;


use App\Models\Question;

use App\Models\Subject;

use Livewire\Component;

use Livewire\WithPagination;


class QuestionList extends Component

{

    use WithPagination;


// Filters

    public string $filterSubject = '';

    public string $filterDifficulty = '';

    public string $filterCode = '';

    public string $filterKeyword = '';


// For accordion state

    public array $expandedQuestions = [];


    protected $queryString = [

        'filterSubject' => ['except' => ''],

        'filterDifficulty' => ['except' => ''],

        'filterCode' => ['except' => ''],

        'filterKeyword' => ['except' => ''],

    ];


    public function updatingFilterSubject(): void

    {

        $this->resetPage();

    }


    public function updatingFilterDifficulty(): void

    {

        $this->resetPage();

    }


    public function updatingFilterCode(): void

    {

        $this->resetPage();

    }


    public function updatingFilterKeyword(): void

    {

        $this->resetPage();

    }


    public function toggleExpand(int $questionId): void

    {

        if (in_array($questionId, $this->expandedQuestions)) {

            $this->expandedQuestions = array_diff($this->expandedQuestions, [$questionId]);

        } else {

            $this->expandedQuestions[] = $questionId;

        }

    }


    public function deleteQuestion(int $questionId): void

    {

        $question = Question::find($questionId);


        if ($question) {

// Soft delete - only sets deleted_at

            $question->delete();

            $this->dispatch('success', 'سوال با موفقیت حذف شد.');

        }

    }


    public function clearFilters(): void

    {

        $this->reset(['filterSubject', 'filterDifficulty', 'filterCode', 'filterKeyword']);

        $this->resetPage();

    }


    public function render()

    {

        $query = Question::with(['subject', 'content', 'options'])
            ->latest();


// Apply filters

        if ($this->filterSubject) {

            $query->where('subject_id', $this->filterSubject);

        }


        if ($this->filterDifficulty) {

            $query->where('difficulty', $this->filterDifficulty);

        }


        if ($this->filterCode) {

            $query->where('code', 'like', "%{$this->filterCode}%");

        }


        if ($this->filterKeyword) {

            $query->searchKeyword($this->filterKeyword);

        }


        $questions = $query->paginate(10);

        $subjects = Subject::active()->orderBy('name')->get();


        $difficulties = [

            'easy' => 'آسان',

            'medium' => 'متوسط',

            'hard' => 'سخت',

            'special' => 'ویژه',

        ];


        return view('livewire.manager.questions.question-list', compact(

            'questions', 'subjects', 'difficulties'

        ))->layout('layouts.manager.app');

    }

}
