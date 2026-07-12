<?php

namespace App\Livewire\Client\Profile\AdvisorChangeRequest;

use App\Models\AdvisorChangeRequest;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public string $subject = '';
    public string $subject_other = '';
    public string $request_text = '';

    public function mount(): void
    {
        $this->seo()->setTitle('درخواست جابجایی مشاور');
    }

    protected function rules(): array
    {
        return [
            'subject' => 'required|in:' . implode(',', array_keys(AdvisorChangeRequest::subjects())),
            'subject_other' => 'required_if:subject,' . AdvisorChangeRequest::SUBJECT_OTHER . '|nullable|string|min:3|max:255',
            'request_text' => 'required|string|min:10|max:2000',
        ];
    }

    protected function messages(): array
    {
        return [
            'subject.required' => 'موضوع درخواست را انتخاب کنید.',
            'subject.in' => 'موضوع انتخاب‌شده معتبر نیست.',
            'subject_other.required_if' => 'وقتی موضوع «سایر» است، عنوان موضوع را بنویسید.',
            'subject_other.min' => 'موضوع سفارشی حداقل ۳ کاراکتر باشد.',
            'request_text.required' => 'متن درخواست الزامی است.',
            'request_text.min' => 'متن درخواست حداقل ۱۰ کاراکتر باشد.',
            'request_text.max' => 'متن درخواست حداکثر ۲۰۰۰ کاراکتر باشد.',
        ];
    }

    public function submit(): void
    {
        $student = Auth::user()?->student()->with('advisor')->first();

        if (! $student || ! $student->advisor_id) {
            $this->dispatch('warning', 'برای ثبت درخواست، ابتدا باید مشاور فعال داشته باشید.');
            return;
        }

        $latest = $student->advisorChangeRequests()->latest()->first();
        if ($latest && $latest->status !== AdvisorChangeRequest::STATUS_CANCELLED_BY_STUDENT) {
            $this->dispatch('warning', 'در حال حاضر امکان ثبت درخواست جدید وجود ندارد.');
            return;
        }

        $data = $this->validate();

        AdvisorChangeRequest::create([
            'student_id' => $student->id,
            'old_advisor_id' => $student->advisor_id,
            'subject' => $data['subject'],
            'subject_other' => $data['subject'] === AdvisorChangeRequest::SUBJECT_OTHER ? $data['subject_other'] : null,
            'request_text' => $data['request_text'],
            'status' => AdvisorChangeRequest::STATUS_PENDING,
        ]);

        $this->reset(['subject', 'subject_other', 'request_text']);
        $this->dispatch('success', 'درخواست شما ثبت و به مدیر آموزشی ارجاع داده شد،منتظر تماس کارشناسان ما باشید.');
    }

    public function render()
    {
        $student = Auth::user()?->student()
            ->with(['advisor', 'advisorChangeRequests.oldAdvisor', 'advisorChangeRequests.newAdvisor', 'advisorChangeRequests.reviewer'])
            ->first();

        $requests = $student
            ? $student->advisorChangeRequests()
                ->with(['oldAdvisor', 'newAdvisor', 'reviewer'])
                ->latest()
                ->get()
            : collect();

        $latest = $requests->first();
        $canSubmit = $student?->advisor_id
            && (! $latest || $latest->status === AdvisorChangeRequest::STATUS_CANCELLED_BY_STUDENT);

        return view('livewire.client.profile.advisor-change-request.index', [
            'student' => $student,
            'advisor' => $student?->advisor,
            'requests' => $requests,
            'latest' => $latest,
            'subjects' => AdvisorChangeRequest::subjects(),
            'canSubmit' => $canSubmit,
        ])->layout('layouts.client.app');
    }
}
