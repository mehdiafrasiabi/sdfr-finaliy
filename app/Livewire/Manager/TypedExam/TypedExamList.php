<?php


namespace App\Livewire\Manager\TypedExam;


use App\Models\TypedExam;

use Livewire\Component;

use Livewire\WithPagination;


class TypedExamList extends Component

{

    use WithPagination;


    public string $filterTitle = '';

    public string $filterAcademicYear = '';

    public string $sortOrder = 'desc';


    protected $queryString = [

        'filterTitle' => ['except' => ''],

        'filterAcademicYear' => ['except' => ''],

        'sortOrder' => ['except' => 'desc'],

    ];


    public function updatingFilterTitle(): void

    {

        $this->resetPage();

    }


    public function updatingFilterAcademicYear(): void

    {

        $this->resetPage();

    }


    public function togglePublish(int $examId): void

    {

        $exam = TypedExam::find($examId);

        if ($exam) {

            $exam->update(['is_published' => !$exam->is_published]);

            $this->dispatch('success', $exam->is_published ? 'آزمون منتشر شد.' : 'آزمون از انتشار خارج شد.');

        }

    }


    public function deleteExam(int $examId): void

    {

        $exam = TypedExam::find($examId);


        if ($exam) {

            // Check if exam has any attempts

            $hasAttempts = $exam->assignments()->whereHas('attempts')->exists();


            if ($hasAttempts) {

                $this->dispatch('error', 'این آزمون دارای شرکت‌کننده است و قابل حذف نیست.');

                return;

            }


            $exam->delete();

            $this->dispatch('success', 'آزمون با موفقیت حذف شد.');

        }

    }


    public function clearFilters(): void

    {

        $this->reset(['filterTitle', 'filterAcademicYear', 'sortOrder']);

        $this->sortOrder = 'desc';

        $this->resetPage();

    }


    public function render()

    {

        $query = TypedExam::with(['settings', 'questions', 'creator'])
            ->withCount('questions');


        if ($this->filterTitle) {

            $query->where('title', 'like', "%{$this->filterTitle}%");

        }


        if ($this->filterAcademicYear) {

            $query->where('academic_year', $this->filterAcademicYear);

        }


        $query->orderBy('created_at', $this->sortOrder);


        $exams = $query->paginate(10);


        $academicYears = [

            '1404-1405' => '۱۴۰۴-۱۴۰۵',

            '1405-1406' => '۱۴۰۵-۱۴۰۶',

        ];


        $difficulties = [

            'easy' => 'آسان',

            'medium' => 'متوسط',

            'hard' => 'سخت',

            'comprehensive' => 'جامع',

        ];


        return view('livewire.manager.typed-exam.typed-exam-list', compact(

            'exams', 'academicYears', 'difficulties'

        ))->layout('layouts.manager.app');

    }

}
