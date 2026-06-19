<?php

namespace App\Livewire\Admin\EducationalManager\PhoneAcquisition\Leads;

use App\Models\Admin;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * مدیر آموزشی — اختصاص دستهٔ روزانهٔ شماره‌ها به مشاور جذب تلفنی.
 * هنگام اختصاص، شماره‌های تکراری (اختصاص فعال) شناسایی و گزارش می‌شوند.
 */
class Assign extends Component
{
    use WithPagination;

    public string $search = '';
    public $selectedConsultant = '';
    public array $selectedLeadIds = [];

    /** خلاصهٔ شماره‌های تکراری پس از آخرین اختصاص. */
    public array $duplicateInfo = [];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function assignBatch(): void
    {
        $this->validate([
            'selectedConsultant' => ['required', 'exists:admins,id'],
            'selectedLeadIds'    => ['required', 'array', 'min:1'],
        ], [
            'selectedConsultant.required' => 'مشاور جذب تلفنی را انتخاب کنید.',
            'selectedLeadIds.required'    => 'حداقل یک شماره را انتخاب کنید.',
            'selectedLeadIds.min'         => 'حداقل یک شماره را انتخاب کنید.',
        ]);

        $consultantId = (int) $this->selectedConsultant;
        $assigned = 0;
        $duplicates = [];
        $skipped = 0;

        DB::transaction(function () use ($consultantId, &$assigned, &$duplicates, &$skipped) {
            $leads = PhoneLead::withCount('assignments')
                ->whereIn('id', $this->selectedLeadIds)
                ->get();

            foreach ($leads as $lead) {
                if ($lead->status === PhoneLead::STATUS_DEAD || $lead->status === PhoneLead::STATUS_CLOSED) {
                    $skipped++;
                    continue;
                }

                $hasActive = $lead->assignments()
                    ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
                    ->exists();

                if ($hasActive) {
                    $duplicates[] = [
                        'mobile' => $lead->mobile,
                        'times'  => $lead->assignments_count,
                    ];
                    continue;
                }

                PhoneLeadAssignment::create([
                    'phone_lead_id' => $lead->id,
                    'admin_id'      => $consultantId,
                    'assigned_by'   => Auth::guard('admin')->id(),
                    'status'        => PhoneLeadAssignment::STATUS_ACTIVE,
                    'assigned_at'   => now(),
                ]);
                $assigned++;
            }
        });

        $this->duplicateInfo = $duplicates;
        $this->selectedLeadIds = [];

        $parts = ["{$assigned} شماره اختصاص یافت"];
        if (count($duplicates) > 0) {
            $parts[] = count($duplicates) . ' شماره تکراری (اختصاص فعال) نادیده گرفته شد';
        }
        if ($skipped > 0) {
            $parts[] = "{$skipped} شماره بسته/خاکستری نادیده گرفته شد";
        }

        if (count($duplicates) > 0) {
            $this->dispatch('warning', implode(' — ', $parts));
        } else {
            $this->dispatch('success', implode(' — ', $parts));
        }
    }

    public function render()
    {
        $consultants = Admin::role('مشاور جذب تلفنی')->orderBy('name')->get();

        $leads = PhoneLead::query()
            ->with(['activeAssignment.consultant:id,name'])
            ->where('status', PhoneLead::STATUS_ACTIVE)
            ->when($this->search, fn ($q) => $q->where(function ($s) {
                $s->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%");
            }))
            ->withCount('assignments')
            ->latest()
            ->paginate(20);

        return view('livewire.admin.educational-manager.phone-acquisition.leads.assign', [
            'consultants' => $consultants,
            'leads'       => $leads,
        ])->layout('layouts.admin.app');
    }
}
