<?php

namespace App\Livewire\Admin\Classification;

use App\Models\ClassificationProject;

use App\Models\PersonalInformation;

use App\Models\Student;

use App\Models\StudentClassificationSubmission;

use App\Models\User;

use Artesaos\SEOTools\Traits\SEOTools;

use Livewire\Component;

use Livewire\WithPagination;


class Students extends Component

{

    use WithPagination, SEOTools;


    public ClassificationProject $project;

    public $search = '';

    public $filterStatus = 'all'; // all, submitted, pending


    public function mount(ClassificationProject $project)

    {

        $this->project = $project;

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('دانش‌آموزان - ' . $this->project->name);

    }


    public function updatingSearch()

    {

        $this->resetPage();

    }


    public function render()

    {


        $adminId = auth()->id();


        // Get all students with their submissions

        $studentsQuery = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation'
            ])
            ->where(function ($q) use ($adminId) {
                $q->where('supporter_id', $adminId)
                    ->orWhere('advisor_id', $adminId);
            });

        if ($this->search) {
            $studentsQuery->whereHas('user.personalInformation', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }


        $students = $studentsQuery->paginate(15);


        // Get submissions for this project

        $submissions = StudentClassificationSubmission::where('classification_project_id', $this->project->id)
            ->pluck('is_completed', 'user_id')
            ->toArray();


        // Count stats

        $totalStudents = $students->total();

        $submittedCount = StudentClassificationSubmission::where('classification_project_id', $this->project->id)
            ->where('is_completed', true)
            ->whereIn('user_id', Student::where('supporter_id', $adminId)->pluck('user_id'))
            ->count();


        return view('livewire.admin.classification.students', [

            'students' => $students,

            'submissions' => $submissions,

            'totalStudents' => $totalStudents,

            'submittedCount' => $submittedCount,

        ])->layout('layouts.admin.app');

    }

}
