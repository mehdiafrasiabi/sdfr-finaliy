<?php

namespace App\Livewire\Admin\Student;

use App\Exports\StudentsByAdminExport;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination, SEOTools;
    public $search = ''; // جستجو در نام دانش‌آموز
    public string $fieldFilter = '';
    public string $gradeFilter = '';

    public const FIELD_NONE = 'none';
    public const FIELD_LABELS = [
        'math' => 'ریاضی',
        'experimental' => 'تجربی',
        'human' => 'انسانی',
        self::FIELD_NONE => 'بدون رشته',
    ];

    public const GRADE_LABELS = [
        '9' => 'نهم',
        '10' => 'دهم',
        '11' => 'یازدهم',
        '12' => 'دوازدهم',
    ];

    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('دانش آموزان');
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFieldFilter()
    {
        $this->gradeFilter = '';
        $this->resetPage();
    }

    public function updatingGradeFilter()
    {
        $this->resetPage();
    }

    public function getFieldOptionsProperty(): array
    {
        return self::FIELD_LABELS;
    }

    public function getGradeOptionsProperty(): array
    {
        if ($this->fieldFilter === self::FIELD_NONE) {
            return ['9' => self::GRADE_LABELS['9']];
        }

        if (in_array($this->fieldFilter, ['math', 'experimental', 'human'], true)) {
            return array_intersect_key(self::GRADE_LABELS, array_flip(['10', '11', '12']));
        }

        return [];
    }

    public function resolveUserPictureUrl($user): ?string
    {
        $raw = $user?->picture ?: $user?->profile?->picture;

        if (! $raw) {
            return null;
        }

        $picture = ltrim($raw, '/');
        if (file_exists(public_path($picture))) {
            return asset($picture);
        }

        $legacyPath = "user/img/{$user->id}/{$raw}";
        if (file_exists(public_path($legacyPath))) {
            return asset($legacyPath);
        }

        return null;
    }
    public function render()
    {
        $admin = auth('admin')->user();
        $studentsQuery = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation',
                'user.personalInformation.state',
                'user.personalInformation.city',
                'user.profile',
                'advisor',
            ]);

        $studentsQuery->when($this->fieldFilter, function ($query) {
            $query->whereHas('user.personalInformation', function ($personalQuery) {
                if ($this->fieldFilter === self::FIELD_NONE) {
                    $personalQuery->where('grade', '9')
                        ->where(function ($q) {
                            $q->whereNull('field')->orWhere('field', '');
                        });

                    return;
                }

                $personalQuery->where('field', $this->fieldFilter);
            });
        });

        $studentsQuery->when($this->gradeFilter, function ($query) {
            $query->whereHas('user.personalInformation', function ($personalQuery) {
                $personalQuery->where('grade', $this->gradeFilter);
            });
        });

        // مدیر مدرسه فقط دانش‌آموزان مدرسهٔ خود را می‌بیند؛ سایر ادمین‌ها دانش‌آموزان تحت مشاورهٔ خود.
        $isSchoolManager = $admin?->hasRole('school-manager') && $admin->school_id;

        if ($isSchoolManager) {
            $studentsQuery->where('school_id', $admin->school_id);

            if ($this->search) {
                $studentsQuery->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('mobile', 'like', '%' . $this->search . '%');
                });
            }
        } else {
            $studentsQuery->where('advisor_id', $admin?->id);

            if ($this->search) {
                $studentsQuery->whereHas('user.personalInformation', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            }
        }

        $students = $studentsQuery->paginate(10);
        return view('livewire.admin.student.index', [
            'students'        => $students,
            'isSchoolManager' => $isSchoolManager,
            'fieldOptions'    => $this->fieldOptions,
            'gradeOptions'    => $this->gradeOptions,
        ])->layout('layouts.admin.app');
    }


}
