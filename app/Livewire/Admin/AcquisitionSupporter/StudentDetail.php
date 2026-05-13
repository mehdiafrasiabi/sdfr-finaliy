<?php

namespace App\Livewire\Admin\AcquisitionSupporter;

use App\Models\AcquisitionCall;
use App\Models\TrialWeek;
use App\Services\AcquisitionCallService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StudentDetail extends Component
{
    public TrialWeek $trial;

    public string $activeTab = 'info'; // info|calls|program|study|reports|exams

    // ثبت تماس
    public string $callType = '';
    public string $callDescription = '';
    public string $callStatus = ''; // no_answer|answered

    // پیش‌بینی + پلن جذب
    public ?int $predictionPercent = null;
    public string $attractionPlan = '';

    public function mount($id): void
    {
        $admin = Auth::guard('admin')->user();
        $this->trial = TrialWeek::with(['user.personalInformation', 'acquisitionCalls', 'student'])
            ->where('id', $id)
            ->where('supporter_id', $admin?->id)
            ->firstOrFail();
    }

    public function logCall(): void
    {
        $this->validate([
            'callType'        => 'required|in:initial,secondary,side',
            'callStatus'      => 'required|in:no_answer,answered',
            'callDescription' => $this->callStatus === 'answered' ? 'required|string|min:3' : 'nullable|string',
        ], [
            'callType.required'        => 'نوع تماس را انتخاب کنید.',
            'callStatus.required'      => 'نتیجه تماس را انتخاب کنید.',
            'callDescription.required' => 'برای تماس موفق، توضیحات الزامی است.',
        ]);

        AcquisitionCall::create([
            'trial_week_id'            => $this->trial->id,
            'acquisition_supporter_id' => Auth::guard('admin')->id(),
            'type'                     => $this->callType,
            'status'                   => $this->callStatus,
            'description'              => $this->callDescription,
            'called_at'                => now(),
        ]);

        $this->reset(['callType', 'callDescription', 'callStatus']);
        $this->trial->refresh();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'تماس ثبت شد.']);
    }

    public function submitPrediction(): void
    {
        $this->validate([
            'predictionPercent' => 'required|integer|min:0|max:100',
            'attractionPlan'    => 'nullable|string',
        ], [
            'predictionPercent.required' => 'درصد پیش‌بینی الزامی است.',
        ]);

        // فقط روی آخرین تماس secondary answered ذخیره می‌کنیم
        $call = $this->trial->acquisitionCalls()
            ->where('type', AcquisitionCall::TYPE_SECONDARY)
            ->where('status', AcquisitionCall::STATUS_ANSWERED)
            ->latest('called_at')
            ->first();

        if (!$call) {
            $this->addError('predictionPercent', 'ابتدا تماس ثانویه را با موفقیت ثبت کنید.');
            return;
        }

        $call->update([
            'prediction_percent' => $this->predictionPercent,
            'attraction_plan'    => $this->attractionPlan,
        ]);

        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'پیش‌بینی ذخیره شد.']);
    }

    #[Layout('layouts.admin.app')]
    public function render()
    {
        $service = app(AcquisitionCallService::class);
        $student = $this->trial->student;

        $weeklyProgram = $student?->weeklyPrograms()->latest()->first();
        $programParts = $weeklyProgram?->parts()->orderBy('day_of_week')->orderBy('part_order')->get() ?? collect();
        $studySessions = $student?->studySessions()->latest()->take(20)->get() ?? collect();
        $reports = $student?->dailyReports()->latest()->take(20)->get() ?? collect();
        $typedExams = $student?->typedExamAssignments()->with('attempts')->latest()->take(20)->get() ?? collect();

        return view('livewire.admin.acquisition-supporter.student-detail', [
            'nextCall'      => $service->nextCallType($this->trial),
            'secondaryDue'  => $service->secondaryCallDue($this->trial),
            'inactive'      => $service->isStudentInactive($this->trial),
            'weeklyProgram' => $weeklyProgram,
            'programParts'  => $programParts,
            'studySessions' => $studySessions,
            'reports'       => $reports,
            'typedExams'    => $typedExams,
        ]);
    }
}
