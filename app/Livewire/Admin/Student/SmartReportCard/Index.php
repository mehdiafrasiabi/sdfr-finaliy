<?php

namespace App\Livewire\Admin\Student\SmartReportCard;

use App\Models\GeneralSetting;
use App\Models\SmartReportCard;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, SEOTools;

    public string $search = '';

    public function mount(): void
    {
        $this->seo()->setTitle('کارنامه هوشمند');
    }

    public function boot(): void
    {
        $this->ensureSmartReportCardAccess();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $adminId = auth()->id();

        $studentsQuery = Student::query()
            ->with([
                'user.personalInformation',
                'user.profile',
            ])
            ->where('is_trial', false)
            ->where(function ($q) use ($adminId) {
                $q->where('advisor_id', $adminId);
            });

        if ($this->search !== '') {
            $studentsQuery->whereHas('user.personalInformation', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $students = $studentsQuery->paginate(10);

        $studentIds = $students->pluck('id')->all();
        $activeCardCounts = SmartReportCard::whereIn('student_id', $studentIds)
            ->where('is_active', true)
            ->selectRaw('student_id, count(*) as total')
            ->groupBy('student_id')
            ->pluck('total', 'student_id')
            ->all();

        return view('livewire.admin.student.smart-report-card.index', [
            'students' => $students,
            'activeCardCounts' => $activeCardCounts,
        ])->layout('layouts.admin.app');
    }

    private function ensureSmartReportCardAccess(): void
    {
        $isEnabled = (bool) GeneralSetting::query()->value('smart_report_card_enabled');

        abort_unless($isEnabled, 403, 'دسترسی به کارنامه هوشمند توسط manager غیرفعال است.');
    }
}
