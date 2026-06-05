<?php

namespace App\Livewire\Admin\Student\SmartReportCard;

use App\Models\SmartReportCard;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class Index extends Component
{
    use WithPagination, SEOTools;

    public string $search = '';

    // Bulk activation modal state
    public bool $bulkModalOpen = false;
    public int $bulkStep = 1;
    public array $bulkSelectedStudents = [];
    public array $bulkSelectedMonths = [];
    public int $bulkSelectedYear = 1404;
    public string $bulkStudentSearch = '';
    public bool $bulkActivate = true;

    public function mount(): void
    {
        $this->seo()->setTitle('کارنامه هوشمند');
        $this->bulkSelectedYear = (int) Jalalian::now()->getYear();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ============ Bulk activation methods ============

    public function openBulkModal(): void
    {
        $this->resetBulkState();
        $this->bulkModalOpen = true;
    }

    public function closeBulkModal(): void
    {
        $this->bulkModalOpen = false;
        $this->resetBulkState();
    }

    private function resetBulkState(): void
    {
        $this->bulkStep = 1;
        $this->bulkSelectedStudents = [];
        $this->bulkSelectedMonths = [];
        $this->bulkSelectedYear = (int) Jalalian::now()->getYear();
        $this->bulkStudentSearch = '';
        $this->bulkActivate = true;
    }

    public function toggleBulkStudent(int $id): void
    {
        if (in_array($id, $this->bulkSelectedStudents, true)) {
            $this->bulkSelectedStudents = array_values(array_filter(
                $this->bulkSelectedStudents,
                fn($v) => $v !== $id
            ));
        } else {
            $this->bulkSelectedStudents[] = $id;
        }
    }

    public function selectAllBulkStudents(): void
    {
        $this->bulkSelectedStudents = $this->getBulkStudentsQuery()->pluck('id')->all();
    }

    public function clearBulkStudents(): void
    {
        $this->bulkSelectedStudents = [];
    }

    public function toggleBulkMonth(int $month): void
    {
        if ($month < 1 || $month > 12) {
            return;
        }
        if (in_array($month, $this->bulkSelectedMonths, true)) {
            $this->bulkSelectedMonths = array_values(array_filter(
                $this->bulkSelectedMonths,
                fn($v) => $v !== $month
            ));
        } else {
            $this->bulkSelectedMonths[] = $month;
        }
    }

    public function toggleAllBulkMonths(): void
    {
        if (count($this->bulkSelectedMonths) === 12) {
            $this->bulkSelectedMonths = [];
        } else {
            $this->bulkSelectedMonths = range(1, 12);
        }
    }

    public function changeBulkYear(int $year): void
    {
        $current = (int) Jalalian::now()->getYear();
        if ($year < $current - 1 || $year > $current + 1) {
            return;
        }
        $this->bulkSelectedYear = $year;
    }

    public function goToBulkStep(int $step): void
    {
        if ($step === 2 && empty($this->bulkSelectedStudents)) {
            $this->dispatch('warning', 'حداقل یک دانش‌آموز را انتخاب کنید.');
            return;
        }
        if ($step < 1 || $step > 2) {
            return;
        }
        $this->bulkStep = $step;
    }

    public function submitBulk(): void
    {
        if (empty($this->bulkSelectedStudents)) {
            $this->dispatch('warning', 'حداقل یک دانش‌آموز را انتخاب کنید.');
            return;
        }
        if (empty($this->bulkSelectedMonths)) {
            $this->dispatch('warning', 'حداقل یک ماه را انتخاب کنید.');
            return;
        }

        $adminId = auth()->id();

        // Re-query to enforce ownership
        $ownedStudentIds = Student::query()
            ->whereIn('id', $this->bulkSelectedStudents)
            ->where(function ($q) use ($adminId) {
                $q->where('advisor_id', $adminId);
            })
            ->pluck('id')
            ->all();

        if (empty($ownedStudentIds)) {
            $this->dispatch('warning', 'دانش‌آموز معتبری انتخاب نشده است.');
            return;
        }

        $count = 0;
        foreach ($ownedStudentIds as $studentId) {
            foreach ($this->bulkSelectedMonths as $month) {
                $range = SmartReportCard::jalaliMonthRange($this->bulkSelectedYear, (int) $month);

                SmartReportCard::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'jalali_year' => $this->bulkSelectedYear,
                        'jalali_month' => (int) $month,
                    ],
                    [
                        'admin_id' => $adminId,
                        'start_date' => $range['start']->toDateString(),
                        'end_date' => $range['end']->toDateString(),
                        'is_active' => $this->bulkActivate,
                        'activated_at' => $this->bulkActivate ? now() : null,
                    ]
                );
                $count++;
            }
        }

        $verb = $this->bulkActivate ? 'فعال' : 'غیرفعال';
        $this->dispatch('success', "{$count} کارنامه با موفقیت {$verb} شد.");
        $this->closeBulkModal();
    }

    private function getBulkStudentsQuery()
    {
        $adminId = auth()->id();

        return Student::query()
            ->with(['user.personalInformation', 'user.profile'])
            ->where(function ($q) use ($adminId) {
                $q->where('advisor_id', $adminId);
            })
            ->when($this->bulkStudentSearch !== '', function ($q) {
                $q->whereHas('user.personalInformation', function ($qq) {
                    $qq->where('name', 'like', '%' . $this->bulkStudentSearch . '%');
                });
            });
    }

    public function render()
    {
        $adminId = auth()->id();

        $studentsQuery = Student::query()
            ->with([
                'user.personalInformation',
                'user.profile',
            ])
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

        // Bulk modal: list of advisor's students for selection
        $bulkStudents = $this->bulkModalOpen
            ? $this->getBulkStudentsQuery()->orderBy('id', 'desc')->get()
            : collect();

        $currentJalaliYear = (int) Jalalian::now()->getYear();

        return view('livewire.admin.student.smart-report-card.index', [
            'students' => $students,
            'activeCardCounts' => $activeCardCounts,
            'bulkStudents' => $bulkStudents,
            'currentJalaliYear' => $currentJalaliYear,
            'monthNames' => SmartReportCard::MONTH_NAMES,
        ])->layout('layouts.admin.app');
    }
}
