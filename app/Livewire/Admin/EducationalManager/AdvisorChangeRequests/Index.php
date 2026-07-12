<?php

namespace App\Livewire\Admin\EducationalManager\AdvisorChangeRequests;

use App\Models\Admin;
use App\Models\AdvisorChangeRequest;
use App\Models\AdvisorOnboarding;
use App\Models\Conversation;
use App\Models\GeneralSetting;
use App\Services\NotificationService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, SEOTools;

    public string $search = '';
    public ?int $selectedRequestId = null;
    public array $newAdvisor = [];
    public array $rejectReason = [];

    public function mount(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin?->hasRole('educational-manager') || $admin?->hasRole('super admin'), 403);

        $this->seo()->setTitle('درخواست‌های جابجایی مشاور');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectRequest(int $requestId): void
    {
        $this->selectedRequestId = $requestId;
    }

    public function closeDetail(): void
    {
        $this->selectedRequestId = null;
    }

    public function cancelByStudent(int $requestId): void
    {
        $request = AdvisorChangeRequest::with('student')->find($requestId);
        if (! $this->isPending($request)) {
            $this->dispatch('warning', 'این درخواست دیگر قابل بررسی نیست.');
            return;
        }

        $request->update([
            'status' => AdvisorChangeRequest::STATUS_CANCELLED_BY_STUDENT,
            'reviewed_by' => auth('admin')->id(),
            'reviewed_at' => now(),
        ]);

        NotificationService::sendToStudent(
            $request->student_id,
            'درخواست جابجایی مشاور',
            'درخواست جابجایی مشاور شما به درخواست دانش‌آموز لغو شد. در صورت نیاز می‌توانید درخواست جدید ثبت کنید.'
        );

        $this->dispatch('success', 'درخواست به عنوان لغو شده ثبت شد.');
    }

    public function approve(int $requestId): void
    {
        $request = AdvisorChangeRequest::with('student')->find($requestId);
        if (! $this->isPending($request)) {
            $this->dispatch('warning', 'این درخواست دیگر قابل بررسی نیست.');
            return;
        }

        $advisorId = $this->newAdvisor[$requestId] ?? null;
        $advisor = $advisorId ? Admin::role('مشاور تحصیلی')->withCount('advisedStudents')->find($advisorId) : null;

        if (! $advisor) {
            $this->addError("newAdvisor.$requestId", 'مشاور جدید را انتخاب کنید.');
            return;
        }

        if ((int) $advisor->id === (int) $request->old_advisor_id) {
            $this->addError("newAdvisor.$requestId", 'مشاور جدید باید با مشاور فعلی متفاوت باشد.');
            return;
        }

        if ($this->advisorAtCapacity($advisor)) {
            $this->addError("newAdvisor.$requestId", 'ظرفیت این مشاور تکمیل است.');
            return;
        }

        DB::transaction(function () use ($request, $advisor) {
            $student = $request->student()->lockForUpdate()->first();

            $student->update(['advisor_id' => $advisor->id]);
            Conversation::where('student_id', $student->id)->update(['advisor_id' => $advisor->id]);

            $request->update([
                'status' => AdvisorChangeRequest::STATUS_APPROVED,
                'new_advisor_id' => $advisor->id,
                'reviewed_by' => auth('admin')->id(),
                'reviewed_at' => now(),
            ]);

            AdvisorOnboarding::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'advisor_id' => $advisor->id,
                    'advisor_selection_id' => null,
                    'contact_documentation_id' => null,
                    'status' => AdvisorOnboarding::STATUS_PENDING_CALL,
                    'group_link' => null,
                    'submitted_at' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'reject_reason' => null,
                ]
            );
        });

        unset($this->newAdvisor[$requestId]);

        NotificationService::sendToStudent(
            $request->student_id,
            'درخواست جابجایی مشاور',
            "با درخواست شما موافقت شد. مشاور جدید شما «{$advisor->name}» است."
        );

        $this->dispatch('success', 'درخواست تایید شد و مشاور دانش‌آموز تغییر کرد.');
    }

    public function reject(int $requestId): void
    {
        $request = AdvisorChangeRequest::with('student')->find($requestId);
        if (! $this->isPending($request)) {
            $this->dispatch('warning', 'این درخواست دیگر قابل بررسی نیست.');
            return;
        }

        $reason = trim((string) ($this->rejectReason[$requestId] ?? ''));
        if ($reason === '') {
            $this->addError("rejectReason.$requestId", 'علت رد درخواست الزامی است.');
            return;
        }

        $request->update([
            'status' => AdvisorChangeRequest::STATUS_REJECTED,
            'reject_reason' => $reason,
            'reviewed_by' => auth('admin')->id(),
            'reviewed_at' => now(),
        ]);

        unset($this->rejectReason[$requestId]);

        NotificationService::sendToStudent(
            $request->student_id,
            'درخواست جابجایی مشاور',
            'درخواست شما رد شد و امکان جابجایی وجود ندارد. علت: ' . $reason
        );

        $this->dispatch('success', 'درخواست رد شد و علت آن ثبت شد.');
    }

    protected function isPending(?AdvisorChangeRequest $request): bool
    {
        return $request && $request->status === AdvisorChangeRequest::STATUS_PENDING;
    }

    protected function advisorAtCapacity(Admin $advisor): bool
    {
        $cap = $advisor->student_capacity ?? (int) (GeneralSetting::query()->value('advisor_default_capacity') ?? 50);
        $count = $advisor->advised_students_count ?? $advisor->advisedStudents()->count();

        return $count >= $cap;
    }

    public function render()
    {
        $requestsQuery = AdvisorChangeRequest::query()
            ->with(['student.user.personalInformation', 'oldAdvisor', 'newAdvisor', 'reviewer'])
            ->when($this->search !== '', function ($q) {
                $q->where(function ($query) {
                    $query->whereHas('student.user', function ($user) {
                        $user->where('name', 'like', "%{$this->search}%")
                            ->orWhere('mobile', 'like', "%{$this->search}%");
                    })->orWhere('subject_other', 'like', "%{$this->search}%")
                        ->orWhere('request_text', 'like', "%{$this->search}%");
                });
            })
            ->latest();

        $requests = $requestsQuery->paginate(15);

        $selectedRequest = $this->selectedRequestId
            ? AdvisorChangeRequest::with(['student.user.personalInformation', 'oldAdvisor', 'newAdvisor', 'reviewer'])->find($this->selectedRequestId)
            : null;

        $defaultCapacity = (int) (GeneralSetting::query()->value('advisor_default_capacity') ?? 50);
        $advisors = Admin::role('مشاور تحصیلی')
            ->withCount('advisedStudents')
            ->orderBy('name')
            ->get()
            ->map(function (Admin $advisor) use ($defaultCapacity) {
                $advisor->cap = $advisor->student_capacity ?? $defaultCapacity;
                $advisor->remaining = max(0, $advisor->cap - (int) $advisor->advised_students_count);
                return $advisor;
            });

        return view('livewire.admin.educational-manager.advisor-change-requests.index', [
            'requests' => $requests,
            'selectedRequest' => $selectedRequest,
            'advisors' => $advisors,
            'pendingCount' => AdvisorChangeRequest::where('status', AdvisorChangeRequest::STATUS_PENDING)->count(),
        ])->layout('layouts.admin.app');
    }
}
