<?php

namespace App\Livewire\Admin\AcquisitionSupporter;

use App\Models\AcquisitionContact;
use App\Models\DailyReport;
use App\Models\StudyPartSession;
use App\Models\TrialWeek;
use App\Models\WeeklyProgram;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentDetail extends Component
{
    public TrialWeek $trialWeek;

    // فرم ثبت تماس
    public string $contactType       = '';
    public bool   $contactAnswered   = true;
    public string $contactNotes      = '';
    public ?int   $predictionPct     = null;
    public string $attractionPlan    = '';
    public bool   $showContactForm   = false;

    public function mount(int $id): void
    {
        $this->trialWeek = TrialWeek::with([
            'user.personalInformation',
            'acquisitionContacts.admin',
            'student',
        ])
            ->where('acquisition_supporter_id', Auth::guard('admin')->id())
            ->findOrFail($id);
    }

    public function openContactForm(string $type): void
    {
        $this->contactType     = $type;
        $this->contactAnswered = true;
        $this->contactNotes    = '';
        $this->predictionPct   = null;
        $this->attractionPlan  = '';
        $this->showContactForm = true;
    }

    public function closeContactForm(): void
    {
        $this->showContactForm = false;
        $this->reset(['contactType', 'contactNotes', 'predictionPct', 'attractionPlan']);
    }

    public function saveContact(): void
    {
        $rules = [
            'contactType'    => ['required', 'in:initial,secondary,supplementary'],
            'contactAnswered' => ['boolean'],
            'contactNotes'   => ['nullable', 'string', 'max:2000'],
        ];

        if ($this->contactType === AcquisitionContact::TYPE_SECONDARY) {
            $rules['predictionPct'] = ['required', 'integer', 'min:0', 'max:100'];
            $rules['attractionPlan'] = ['nullable', 'string', 'max:5000'];
        }

        $this->validate($rules, [
            'contactType.required'     => 'نوع تماس الزامی است.',
            'predictionPct.required'   => 'درصد پیش‌بینی برای تماس ثانویه الزامی است.',
            'predictionPct.min'        => 'درصد پیش‌بینی نمی‌تواند کمتر از ۰ باشد.',
            'predictionPct.max'        => 'درصد پیش‌بینی نمی‌تواند بیشتر از ۱۰۰ باشد.',
        ]);

        // بررسی ترتیب تماس‌ها
        $existing = $this->trialWeek->acquisitionContacts;

        if ($this->contactType === AcquisitionContact::TYPE_SECONDARY &&
            $existing->where('type', AcquisitionContact::TYPE_INITIAL)->isEmpty()) {
            $this->addError('contactType', 'ابتدا باید تماس اولیه را ثبت کنید.');
            return;
        }

        if ($this->contactType === AcquisitionContact::TYPE_SUPPLEMENTARY) {
            if ($existing->where('type', AcquisitionContact::TYPE_INITIAL)->isEmpty() ||
                $existing->where('type', AcquisitionContact::TYPE_SECONDARY)->isEmpty()) {
                $this->addError('contactType', 'برای تماس جانبی، تماس اولیه و ثانویه هر دو باید ثبت شده باشند.');
                return;
            }
        }

        AcquisitionContact::create([
            'trial_week_id'         => $this->trialWeek->id,
            'admin_id'              => Auth::guard('admin')->id(),
            'type'                  => $this->contactType,
            'answered'              => $this->contactAnswered,
            'notes'                 => $this->contactNotes ?: null,
            'prediction_percentage' => $this->contactType === AcquisitionContact::TYPE_SECONDARY
                ? $this->predictionPct : null,
            'attraction_plan'       => $this->contactType === AcquisitionContact::TYPE_SECONDARY
                ? ($this->attractionPlan ?: null) : null,
            'contacted_at'          => now(),
        ]);

        $this->trialWeek->load('acquisitionContacts.admin');
        $this->closeContactForm();
        session()->flash('success', 'تماس با موفقیت ثبت شد.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $student = $this->trialWeek->student;

        // برنامهٔ هفتگی که سیستم/مشاور برای دانش‌آموز ساخته (آخرین برنامه)
        $program = null;
        // گزارش‌های روزانه‌ای که دانش‌آموز ثبت کرده
        $reports = collect();
        // جلسات مطالعه (ساعت مطالعه ثبت‌شده)
        $studySessions = collect();
        $totalStudySeconds = 0;

        if ($student) {
            $program = WeeklyProgram::with(['parts', 'advisor'])
                ->where('student_id', $student->id)
                ->orderByDesc('is_active')
                ->latest('start_date')
                ->first();

            $reports = DailyReport::with(['detail', 'feedback', 'weeklyProgram'])
                ->where('student_id', $student->id)
                ->orderByDesc('report_date')
                ->limit(30)
                ->get();

            $studySessions = StudyPartSession::with(['programPart', 'weeklyProgram'])
                ->where('student_id', $student->id)
                ->orderByDesc('started_at')
                ->limit(50)
                ->get();

            $totalStudySeconds = (int) StudyPartSession::where('student_id', $student->id)
                ->where('is_completed', true)
                ->sum('duration_seconds');
        }

        return view('livewire.admin.acquisition-supporter.student-detail', [
            'program'           => $program,
            'reports'           => $reports,
            'studySessions'     => $studySessions,
            'totalStudySeconds' => $totalStudySeconds,
            'hasStudent'        => (bool) $student,
        ])->layout('layouts.admin.app');
    }
}
