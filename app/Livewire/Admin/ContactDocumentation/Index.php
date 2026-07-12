<?php

namespace App\Livewire\Admin\ContactDocumentation;

use App\Models\ContactDocumentation;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, SEOTools;

    public string $search = '';
    public string $filterStatus = '';
    public string $filterStudent = '';

    public function mount(): void
    {
        $this->seo()
            ->setTitle('گزارش تماس دانش آموزان');
    }

    private function getStudents()
    {
        return Student::with(['user.personalInformation'])
            ->where('advisor_id', auth('admin')->id())
            ->get();
    }

    private function getFilteredQuery()
    {
        $query = ContactDocumentation::where('admin_id', auth('admin')->id());

        if ($this->filterStudent) {
            $query->where('student_id', $this->filterStudent);
        }

        if ($this->filterStatus) {
            $query->where('contact_status', $this->filterStatus);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhereHas('student.user.personalInformation', function ($studentQuery) {
                        $studentQuery->where('name', 'like', "%{$this->search}%")
                            ->orWhere('name_full', 'like', "%{$this->search}%");
                    });
            });
        }

        return $query;
    }

    private function buildDashboard(Collection $records): array
    {
        $successful = $records->where('contact_status', 'successful')->count();
        $unsuccessful = $records->where('contact_status', 'unsuccessful')->count();
        $connected = $records->where('connected', true)->count();
        $notConnected = $records->where('connected', false)->count();
        $makeup = $records->filter(fn ($record) => str_contains((string) $record->title, 'جبرانی'))->count();
        $finalConfirmation = $records
            ->where('title', ContactDocumentation::TITLE_FINAL_CONFIRMATION)
            ->count();
        $totalTalkSeconds = (int) $records->where('connected', true)->sum('talk_duration_seconds');

        $successfulByHour = $records
            ->where('connected', true)
            ->map(function ($record) {
                $time = $record->answered_at ?? $record->created_at;

                return $time ? (int) $time->format('H') : null;
            })
            ->filter(fn ($hour) => $hour !== null)
            ->countBy()
            ->sortKeys();

        $totalAnsweredWithTime = $successfulByHour->sum();
        $responseHours = collect(range(0, 23))->map(function (int $hour) use ($successfulByHour, $totalAnsweredWithTime) {
            $count = (int) ($successfulByHour[$hour] ?? 0);

            return [
                'hour' => $hour,
                'label' => sprintf('%02d:00', $hour),
                'count' => $count,
                'percent' => $totalAnsweredWithTime > 0 ? round(($count / $totalAnsweredWithTime) * 100) : 0,
            ];
        });

        $weightedMinutes = $records
            ->where('connected', true)
            ->map(function ($record) {
                $time = $record->answered_at ?? $record->created_at;

                return $time ? ((int) $time->format('H') * 60) + (int) $time->format('i') : null;
            })
            ->filter(fn ($minutes) => $minutes !== null);

        $averageMinutes = $weightedMinutes->isNotEmpty()
            ? (int) round($weightedMinutes->avg())
            : null;

        return [
            'successful' => $successful,
            'unsuccessful' => $unsuccessful,
            'makeup' => $makeup,
            'final_confirmation' => $finalConfirmation,
            'total' => $records->count(),
            'connected' => $connected,
            'not_connected' => $notConnected,
            'talk_minutes' => intdiv($totalTalkSeconds, 60),
            'talk_seconds_remainder' => $totalTalkSeconds % 60,
            'average_response_time' => $averageMinutes !== null
                ? sprintf('%02d:%02d', intdiv($averageMinutes, 60), $averageMinutes % 60)
                : '—',
            'response_hours' => $responseHours,
            'top_response_hours' => $responseHours
                ->filter(fn ($row) => $row['count'] > 0)
                ->sortByDesc('count')
                ->take(3)
                ->values(),
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStudent(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'filterStatus', 'filterStudent']);
        $this->resetPage();
    }

    public function render()
    {
        $dashboardRecords = $this->getFilteredQuery()
            ->with(['student.user.personalInformation'])
            ->latest('contact_date')
            ->latest('id')
            ->get();

        $records = $this->getFilteredQuery()
            ->with(['student.user.personalInformation'])
            ->latest('contact_date')
            ->latest('id')
            ->paginate(15);

        return view('livewire.admin.contact-documentation.index', [
            'records' => $records,
            'students' => $this->getStudents(),
            'dashboard' => $this->buildDashboard($dashboardRecords),
        ])->layout('layouts.admin.app');
    }
}
