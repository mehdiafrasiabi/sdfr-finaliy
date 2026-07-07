<?php
namespace App\Livewire\Admin\Classification;
use App\Models\ClassificationProject;
use App\Models\Student;
use App\Models\StudentClassification;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination, SEOTools;
    public $search = '';
    public array $studentDashboardData = [];

    public function mount()
    {
        $this->seoConfig();
        $this->loadStudentDashboardData();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('داشبورد طبقه‌بندی');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function loadStudentDashboardData()
    {
        $admin = Auth::user();
        $studentIds = Student::where('advisor_id', $admin->id)->pluck('user_id');

        // Find current or last ended project
        $project = ClassificationProject::active()->where('start_at', '<=', now())->where('end_at', '>=', now())->first()
            ?? ClassificationProject::active()->ended()->latest('end_at')->first();

        if (!$project) {
            $this->studentDashboardData = ['error' => 'پروژه فعالی برای تحلیل یافت نشد.'];
            return;
        }

        // 1. دانش آموزان که طبقه بندی کردند / انجام ندادند
        $totalStudents = $studentIds->count();
        $classifiedStudentsCount = StudentClassification::whereIn('user_id', $studentIds)
            ->where('classification_project_id', $project->id)
            ->distinct('user_id')
            ->count();
        $unclassifiedStudentsCount = $totalStudents - $classifiedStudentsCount;

        // 2. تاریخ بعدی طبقه بندی
        $nextProject = ClassificationProject::active()->upcoming()->orderBy('start_at')->first();

        // 3. میانگین (توزیع) امتیاز طبقه بندی
        $ratingDistribution = StudentClassification::whereIn('user_id', $studentIds)
            ->where('classification_project_id', $project->id)
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        // 4. نقاط قوت / نقاط ضعف
        $classifications = StudentClassification::whereIn('user_id', $studentIds)
            ->where('classification_project_id', $project->id)
            ->with(['ratable.subject.grade']) // Eager load relations
            ->get();

        $subjectRatings = [];

        foreach ($classifications as $classification) {
            if (!$classification->ratable) continue;

            $key = '';
            $name = '';
            $isSpecialized = false;

            if ($classification->ratable_type === 'App\\Models\\CcChapter') {
                $subject = $classification->ratable->subject;
                if (!$subject) continue;
                $key = $subject->id . '_' . $classification->ratable->id;
                $name = $subject->name . ' - ' . $classification->ratable->name;
                $isSpecialized = $subject->type == 'specialized';
            } elseif ($classification->ratable_type === 'App\\Models\\CcSubject') {
                $subject = $classification->ratable;
                $key = $subject->id . '_0';
                $name = $subject->name;
                $isSpecialized = $subject->type == 'specialized';
            }

            if(empty($key)) continue;

             if (!isset($subjectRatings[$key])) {
                $subjectRatings[$key] = ['name' => $name, 'is_specialized' => $isSpecialized, 'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
            }
            if (isset($subjectRatings[$key][$classification->rating])) {
                $subjectRatings[$key][$classification->rating]++;
            }
        }

        $strengths = collect($subjectRatings)->sortByDesc('A')->take(5);
        $weaknesses = collect($subjectRatings)
            ->filter(fn($item) => $item['is_specialized']) // Filter only specialized for weaknesses as requested
            ->sortByDesc('C')->take(5);
        $generalWeaknesses = collect($subjectRatings)
            ->filter(fn($item) => !$item['is_specialized'])
            ->sortByDesc('C')->take(5);


        $this->studentDashboardData = [
            'projectName' => $project->name,
            'totalStudents' => $totalStudents,
            'classifiedStudentsCount' => $classifiedStudentsCount,
            'unclassifiedStudentsCount' => $unclassifiedStudentsCount,
            'nextClassificationDate' => $nextProject ? $nextProject->start_at : null,
            'ratingDistribution' => $ratingDistribution,
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
            'generalWeaknesses' => $generalWeaknesses,
        ];
    }


    public function render()
    {
        // This part remains for the projects list
        $upcomingProjects = ClassificationProject::active()->upcoming()->count();
        $endedProjects = ClassificationProject::active()->ended()->count();
        $nextProject = ClassificationProject::active()
            ->where('start_at', '>', now())
            ->orderBy('start_at')
            ->first();
        $daysToNext = $nextProject ? now()->diffInDays($nextProject->start_at) : null;
        $projects = ClassificationProject::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.classification.dashboard', [
            'upcomingProjects' => $upcomingProjects,
            'endedProjects' => $endedProjects,
            'daysToNext' => $daysToNext,
            'projects' => $projects,
        ])->layout('layouts.admin.app');
    }
}
