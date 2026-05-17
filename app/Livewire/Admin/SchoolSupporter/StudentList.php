<?php

namespace App\Livewire\Admin\SchoolSupporter;

use App\Models\School;
use App\Models\SchoolParentContact;
use Hekmatinasser\Verta\Verta;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public School $school;
    public string $search = '';

    public function mount(School $school): void
    {
        $this->ensureAccess($school);
        $this->school = $school;
    }

    protected function ensureAccess(School $school): void
    {
        $admin = auth('admin')->user();
        if ($admin->hasRole('super admin')) return;
        abort_unless($admin->supportedSchools()->where('schools.id', $school->id)->exists(), 403);
    }

    public function render()
    {
        // فقط دانش‌آموزانی که این پشتیبان روی آن‌ها به‌عنوان پشتیبان مدرسه تعیین شده
        // (یا اگر تعیین نشده، همه‌ی دانش‌آموزان مدرسه را نشان بدهیم تا قابل اساین باشند)
        $admin = auth('admin')->user();
        $isSuper = $admin->hasRole('super admin');

        $students = $this->school->students()
            ->with('user')
            ->when(!$isSuper, fn($q) => $q->where(function ($q) use ($admin) {
                $q->where('school_supporter_id', $admin->id)
                  ->orWhereNull('school_supporter_id');
            }))
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('national_code', 'like', "%{$this->search}%")
                      ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")
                                                      ->orWhere('mobile', 'like', "%{$this->search}%"));
                });
            })
            ->paginate(15);

        // محاسبه تعداد تماس‌های ماه جلالی جاری برای هر دانش‌آموز
        $v = Verta::now();
        $year = $v->year;
        $month = $v->month;
        $jStart = Verta::parse(sprintf('%04d-%02d-01 00:00:00', $year, $month))->datetime();
        $jEnd   = (clone $jStart)->modify('+1 month -1 second');

        $contactCounts = SchoolParentContact::query()
            ->whereIn('student_id', $students->pluck('id'))
            ->whereBetween('contacted_at', [$jStart, $jEnd])
            ->selectRaw('student_id, COUNT(*) as c')
            ->groupBy('student_id')
            ->pluck('c', 'student_id');

        return view('livewire.admin.school-supporter.student-list', [
            'students'      => $students,
            'contactCounts' => $contactCounts,
        ])->layout('layouts.admin.app');
    }
}
