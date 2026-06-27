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
 *   - مشاهدهٔ طرح‌های اقساطی و وضعیت هر قسط (با فیلتر وضعیت).
 *   - ثبت دستی پرداخت یک قسط (مودال + یادداشت) برای پرداخت‌های خارج از درگاه.
 *   - فعال‌سازی دستی طرحِ در انتظار (پیش‌پرداخت دستی) با تأیید مودال.
 *   - اعلان‌ها با dispatch (Toast/SweetAlert).
 */
class Index extends Component
{
    use WithPagination;

    public ?int   $selectedPlanId = null;
    public string $statusFilter   = 'all'; // all|pending|active|completed|defaulted

    // مودال ثبت دستی قسط
    public ?int   $payInstallmentId = null;
    public string $manualNote       = '';

    // مودال فعال‌سازی طرح
    public ?int $activatePlanId = null;

    public const STATUS_LABELS = [
        'pending'   => 'در انتظار پیش‌پرداخت',
        'active'    => 'فعال',
        'completed' => 'تسویه‌شده',
        'defaulted' => 'نکول',
    ];

    public function selectPlan(int $id): void
    {
        $this->selectedPlanId = $id;
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    // ─────────── ثبت دستی قسط ───────────

    public function openPayModal(int $installmentId): void
    {
        $this->payInstallmentId = $installmentId;
        $this->manualNote = '';
    }

    public function closePayModal(): void
    {
        $this->payInstallmentId = null;
        $this->manualNote = '';
    }

    public function confirmPay(): void
    {
        $inst = $this->payInstallmentId ? Installment::find($this->payInstallmentId) : null;
        if (! $inst) {
            $this->dispatch('error', 'قسط یافت نشد.');
            return;
        }
        if ($inst->isPaid()) {
            $this->dispatch('warning', 'این قسط قبلاً پرداخت شده است.');
            $this->closePayModal();
            return;
        }

        $inst->update([
            'status'          => Installment::STATUS_PAID,
            'paid_manually'   => true,
            'manual_note'     => trim($this->manualNote) ?: null,
            'manual_admin_id' => Auth::guard('manager')->id() ?? Auth::guard('admin')->id(),
            'paid_at'         => Carbon::now(),
        ]);

        $inst->plan?->refreshCompletion();
        $this->closePayModal();
        $this->dispatch('success', 'پرداخت قسط به‌صورت دستی ثبت شد.');
    }

    // ─────────── فعال‌سازی دستی طرح ───────────

    public function openActivateModal(int $planId): void
    {
        $this->activatePlanId = $planId;
    }

    public function closeActivateModal(): void
    {
        $this->activatePlanId = null;
    }

    public function confirmActivate(): void
    {
        $plan = $this->activatePlanId ? InstallmentPlan::find($this->activatePlanId) : null;
        if (! $plan || $plan->status !== InstallmentPlan::STATUS_PENDING) {
            $this->dispatch('error', 'طرح نامعتبر است یا قبلاً فعال شده.');
            $this->closeActivateModal();
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

        $this->closeActivateModal();
        $this->dispatch('success', 'طرح اقساطی به‌صورت دستی فعال شد.');
    }

    public function render()
    {
        $plans = InstallmentPlan::with(['user', 'installments'])
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->latest('id')
            ->paginate(15);

        $selectedPlan = $this->selectedPlanId
            ? InstallmentPlan::with(['user', 'installments'])->find($this->selectedPlanId)
            : null;

        $payInstallment = $this->payInstallmentId
            ? Installment::with('plan')->find($this->payInstallmentId)
            : null;

        $activatePlan = $this->activatePlanId
            ? InstallmentPlan::with('user')->find($this->activatePlanId)
            : null;

        return view('livewire.manager.installment.index', [
            'plans'          => $plans,
            'selectedPlan'   => $selectedPlan,
            'payInstallment' => $payInstallment,
            'activatePlan'   => $activatePlan,
        ])->layout('layouts.manager.app');
    }
}
