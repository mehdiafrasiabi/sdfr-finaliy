<?php

namespace App\Livewire\Manager\Installment;

use App\Models\Installment;
use App\Models\InstallmentPlan;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * مدیریت اقساط دانش‌آموزان:
 *   - مشاهدهٔ طرح‌های اقساطی و وضعیت هر قسط.
 *   - ثبت دستی پرداخت یک قسط (برای مواقع استثنا/پرداخت خارج از درگاه).
 *   - فعال‌سازی دستی طرحِ در انتظار (پیش‌پرداخت دستی).
 */
class Index extends Component
{
    use WithPagination;

    public ?int   $selectedPlanId = null;
    public string $manualNote     = '';

    public function selectPlan(int $id): void
    {
        $this->selectedPlanId = $id;
        $this->manualNote = '';
    }

    public function markInstallmentPaid(int $installmentId): void
    {
        $inst = Installment::findOrFail($installmentId);
        if ($inst->isPaid()) {
            return;
        }

        $inst->update([
            'status'          => Installment::STATUS_PAID,
            'paid_manually'   => true,
            'manual_note'     => $this->manualNote ?: null,
            'manual_admin_id' => Auth::guard('manager')->id() ?? Auth::guard('admin')->id(),
            'paid_at'         => Carbon::now(),
        ]);

        $inst->plan?->refreshCompletion();
        $this->manualNote = '';
        session()->flash('success', 'پرداخت قسط به‌صورت دستی ثبت شد.');
    }

    public function activatePlan(int $planId): void
    {
        $plan = InstallmentPlan::findOrFail($planId);
        if ($plan->status !== InstallmentPlan::STATUS_PENDING) {
            return;
        }

        $student = Student::firstOrCreate(
            ['user_id' => $plan->user_id],
            ['is_trial' => false],
        );

        $student->update([
            'is_trial'       => false,
            'access_ends_at' => $plan->access_ends_at,
        ]);

        $plan->update([
            'status'     => InstallmentPlan::STATUS_ACTIVE,
            'student_id' => $student->id,
        ]);

        session()->flash('success', 'طرح اقساطی به‌صورت دستی فعال شد.');
    }

    public function render()
    {
        $plans = InstallmentPlan::with(['user', 'installments'])
            ->latest('id')
            ->paginate(15);

        $selectedPlan = $this->selectedPlanId
            ? InstallmentPlan::with(['user', 'installments'])->find($this->selectedPlanId)
            : null;

        return view('livewire.manager.installment.index', [
            'plans'        => $plans,
            'selectedPlan' => $selectedPlan,
        ])->layout('layouts.manager.app');
    }
}
