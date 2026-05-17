<?php

namespace App\Livewire\Client\School;

use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\SchoolReport;
use App\Models\SchoolReportPart;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ReportCreate extends Component
{
    use SEOTools;

    public ?int $studentFieldId = null;
    public ?int $studentGradeNumber = null;
    public array $allowedGradeIds = [];

    public string $reportDate = ''; // Y-m-d

    public array $parts = [];

    public bool $modalOpen = false;
    public int  $editingPartIndex = -1;

    public ?int $modalGradeId = null;
    public ?int $modalSubjectId = null;
    public ?int $modalChapterId = null;
    public string $modalSearch = '';
    public int $modalStudyHours = 0;
    public int $modalStudyMinutes = 0;
    public int $modalMobileHours = 0;
    public int $modalMobileMinutes = 0;

    public function mount(): void
    {
        $this->seo()->setTitle('ثبت گزارش جدید');
        abort_unless(auth()->user()?->isSchoolStudent(), 403);

        $this->reportDate = now()->toDateString();

        $student = auth()->user()->student;
        $this->studentGradeNumber = (int) $student->grade;

        if ($student->field) {
            $field = CcField::where('slug', $student->field)
                ->orWhere('name', $student->field)
                ->first();
            $this->studentFieldId = $field?->id;
        }

        if ($this->studentGradeNumber) {
            $query = CcGrade::where('is_active', true)
                ->where('grade_number', '<=', $this->studentGradeNumber);
            if ($this->studentFieldId) {
                $query->where(function ($q) {
                    $q->where('cc_field_id', $this->studentFieldId)
                      ->orWhereNull('cc_field_id');
                });
            }
            $this->allowedGradeIds = $query->pluck('id')->toArray();
        }
    }

    public function openModal(int $index = -1): void
    {
        $this->editingPartIndex = $index;
        $this->resetModalFields();
        if ($index >= 0 && isset($this->parts[$index])) {
            $p = $this->parts[$index];
            $this->modalSubjectId = $p['cc_subject_id'];
            $this->modalChapterId = $p['cc_chapter_id'];
            $subject = CcSubject::find($p['cc_subject_id']);
            $this->modalGradeId = $subject?->cc_grade_id;
            $this->modalStudyHours = intdiv($p['study_minutes'], 60);
            $this->modalStudyMinutes = $p['study_minutes'] % 60;
            $this->modalMobileHours = intdiv($p['mobile_minutes'], 60);
            $this->modalMobileMinutes = $p['mobile_minutes'] % 60;
        }
        $this->modalOpen = true;
    }

    public function closeModal(): void
    {
        $this->modalOpen = false;
        $this->resetModalFields();
    }

    public function resetModalFields(): void
    {
        $this->modalGradeId = null;
        $this->modalSubjectId = null;
        $this->modalChapterId = null;
        $this->modalSearch = '';
        $this->modalStudyHours = 0;
        $this->modalStudyMinutes = 0;
        $this->modalMobileHours = 0;
        $this->modalMobileMinutes = 0;
    }

    public function selectSearchResult(string $type, int $id): void
    {
        if ($type === 'chapter') {
            $chapter = CcChapter::with('subject')->find($id);
            if (!$chapter) return;
            $this->modalSubjectId = $chapter->cc_subject_id;
            $this->modalGradeId = $chapter->subject?->cc_grade_id;
            $this->modalChapterId = $chapter->id;
        }
        $this->modalSearch = '';
    }

    public function savePart(): void
    {
        $studyMinutes  = ($this->modalStudyHours * 60) + $this->modalStudyMinutes;
        $mobileMinutes = ($this->modalMobileHours * 60) + $this->modalMobileMinutes;

        Validator::make([
            'cc_subject_id' => $this->modalSubjectId,
            'cc_chapter_id' => $this->modalChapterId,
            'study_minutes' => $studyMinutes,
            'mobile_minutes' => $mobileMinutes,
        ], [
            'cc_subject_id'  => 'required|exists:cc_subjects,id',
            'cc_chapter_id'  => 'required|exists:cc_chapters,id',
            'study_minutes'  => 'required|integer|min:1|max:1440',
            'mobile_minutes' => 'required|integer|min:0|max:1440',
        ], [
            '*.required'    => 'فیلد ضروری است',
            'study_minutes.min' => 'مدت مطالعه باید حداقل ۱ دقیقه باشد',
        ])->validate();

        $payload = [
            'cc_subject_id'  => $this->modalSubjectId,
            'cc_chapter_id'  => $this->modalChapterId,
            'study_minutes'  => $studyMinutes,
            'mobile_minutes' => $mobileMinutes,
        ];

        if ($this->editingPartIndex >= 0) {
            $this->parts[$this->editingPartIndex] = $payload;
        } else {
            $this->parts[] = $payload;
        }
        $this->closeModal();
    }

    public function removePart(int $index): void
    {
        if (isset($this->parts[$index])) {
            array_splice($this->parts, $index, 1);
        }
    }

    public function submit(): void
    {
        Validator::make([
            'report_date' => $this->reportDate,
            'parts'       => $this->parts,
        ], [
            'report_date' => 'required|date|before_or_equal:today',
            'parts'       => 'required|array|min:1',
        ], [
            'report_date.before_or_equal' => 'تاریخ گزارش نمی‌تواند در آینده باشد',
            'parts.min'                   => 'حداقل یک پارت اضافه کنید',
        ])->validate();

        $student = auth()->user()->student;

        DB::transaction(function () use ($student) {
            $totalStudy = collect($this->parts)->sum('study_minutes');
            $totalMobile = collect($this->parts)->sum('mobile_minutes');

            $report = SchoolReport::updateOrCreate(
                ['student_id' => $student->id, 'report_date' => $this->reportDate],
                [
                    'status'               => SchoolReport::STATUS_PENDING,
                    'total_study_minutes'  => $totalStudy,
                    'total_mobile_minutes' => $totalMobile,
                ]
            );

            $report->parts()->delete();
            foreach ($this->parts as $p) {
                SchoolReportPart::create(array_merge($p, ['school_report_id' => $report->id]));
            }
        });

        session()->flash('success', 'گزارش با موفقیت ثبت شد');
        $this->redirectRoute('client.profile.school.report.index', navigate: true);
    }

    public function getGradesProperty()
    {
        if (empty($this->allowedGradeIds)) return collect();
        return CcGrade::whereIn('id', $this->allowedGradeIds)
            ->where('is_active', true)
            ->orderBy('grade_number')
            ->get();
    }

    public function getSubjectsProperty()
    {
        if (!$this->modalGradeId) return collect();
        $q = CcSubject::where('cc_grade_id', $this->modalGradeId);
        if ($this->studentFieldId) {
            $q->where(function ($q) {
                $q->where('cc_field_id', $this->studentFieldId)
                  ->orWhereNull('cc_field_id');
            });
        }
        return $q->orderBy('order')->get();
    }

    public function getChaptersProperty()
    {
        if (!$this->modalSubjectId) return collect();
        return CcChapter::where('cc_subject_id', $this->modalSubjectId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public function getSearchResultsProperty()
    {
        $term = trim($this->modalSearch);
        if (mb_strlen($term) < 2) return collect();

        $subjectIds = CcSubject::whereIn('cc_grade_id', $this->allowedGradeIds ?: [0])
            ->when($this->studentFieldId, function ($q) {
                $q->where(function ($q) {
                    $q->where('cc_field_id', $this->studentFieldId)
                      ->orWhereNull('cc_field_id');
                });
            })
            ->pluck('id');

        return CcChapter::with('subject.grade')
            ->whereIn('cc_subject_id', $subjectIds)
            ->where('is_active', true)
            ->where('name', 'like', "%{$term}%")
            ->limit(20)
            ->get()
            ->map(fn($c) => [
                'id'    => $c->id,
                'label' => trim(($c->subject?->grade?->name ?? '') . ' » ' . ($c->subject?->name ?? '') . ' » ' . $c->name, ' »'),
            ]);
    }

    public function render()
    {
        return view('livewire.client.school.report-create', [
            'grades'        => $this->grades,
            'subjects'      => $this->subjects,
            'chapters'      => $this->chapters,
            'searchResults' => $this->searchResults,
        ])->layout('layouts.client.app');
    }
}
