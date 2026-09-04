<?php

namespace App\Livewire\Admin\EducationalManager\Acquisition;

use App\Models\Admin;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneRegistrationLink;
use App\Models\TrialWeek;
use App\Services\AcquisitionAnalyticsService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Details extends Component
{
    use WithPagination;

    public string $channel;

    public string $segment;

    #[Url]
    public string $search = '';

    #[Url]
    public string $consultant = '';

    #[Url]
    public string $source = '';

    #[Url]
    public string $period = 'all';

    public function mount(string $channel, string $segment = 'all'): void
    {
        abort_unless(in_array($channel, ['phone', 'trial'], true), 404);
        abort_unless(array_key_exists($segment, $this->segmentLabels($channel)), 404);

        $this->channel = $channel;
        $this->segment = $segment;
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'consultant', 'source', 'period'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'consultant', 'source']);
        $this->resetPage();
    }

    public function render(AcquisitionAnalyticsService $analytics)
    {
        $rows = $this->channel === 'phone'
            ? $this->phoneRows($analytics)
            : $this->trialRows($analytics);

        $consultants = Admin::role($this->channel === 'phone' ? 'مشاور جذب تلفنی' : 'site acquisition')
            ->orderBy('name')
            ->get(['id', 'name']);

        $sourceMap = $this->channel === 'trial'
            ? $analytics->registrationSourceMap(collect($rows->items())->pluck('user_id'))
            : collect();

        return view('livewire.admin.educational-manager.acquisition.details', [
            'rows' => $rows,
            'consultants' => $consultants,
            'sourceMap' => $sourceMap,
            'segmentLabel' => $this->segmentLabels($this->channel)[$this->segment],
            'segments' => $this->segmentLabels($this->channel),
        ])->layout('layouts.admin.app');
    }

    protected function phoneRows(AcquisitionAnalyticsService $analytics)
    {
        $query = PhoneLead::query()
            ->with([
                'state:id,name',
                'city:id,name',
                'activeAssignment.consultant:id,name',
                'latestRegistrationLink.consultant:id,name',
                'latestRegistrationLink.user:id,name,mobile',
                'calls.admin:id,name',
            ])
            ->withCount('calls');

        $analytics->applyPeriod($query, $this->period);

        match ($this->segment) {
            'active' => $query->where('status', PhoneLead::STATUS_ACTIVE),
            'no-call' => $query->doesntHave('calls'),
            'temporary' => $query->where('disinterest_status', PhoneCall::DISINTEREST_TEMPORARY),
            'definitive' => $query->where('disinterest_status', PhoneCall::DISINTEREST_DEFINITIVE),
            'registered' => $query->whereIn('id', $analytics->completedPhoneLeadIds()),
            'pending-registration' => $query->whereHas('registrationLinks')->whereNotIn('id', $analytics->completedPhoneLeadIds()),
            default => null,
        };

        $query->when(trim($this->search) !== '', function ($query) {
            $search = '%'.trim($this->search).'%';
            $query->where(fn ($q) => $q->where('full_name', 'like', $search)->orWhere('mobile', 'like', $search));
        })->when($this->consultant !== '', fn ($q) => $q->whereHas('assignments', fn ($assignment) => $assignment->where('admin_id', $this->consultant)));

        return $query->latest()->paginate(25);
    }

    protected function trialRows(AcquisitionAnalyticsService $analytics)
    {
        $query = TrialWeek::query()
            ->with([
                'user:id,name,mobile',
                'acquisitionSupporter:id,name',
                'trialAcquisitionCalls.admin:id,name',
            ])
            ->withCount('trialAcquisitionCalls');

        $analytics->applyPeriod($query, $this->period);

        match ($this->segment) {
            'active' => $query->whereNull('acq_disinterest_status'),
            'unassigned' => $query->whereNull('acquisition_supporter_id'),
            'temporary' => $query->where('acq_disinterest_status', TrialWeek::ACQ_DISINTEREST_TEMPORARY),
            'definitive' => $query->where('acq_disinterest_status', TrialWeek::ACQ_DISINTEREST_DEFINITIVE),
            'registered' => $query->where(fn ($q) => $q->whereNotNull('program_built_at')->orWhere('status', TrialWeek::STATUS_PROGRAM_BUILT)),
            'pending-registration' => $query->whereNull('acq_disinterest_status')->whereNull('program_built_at')->where('status', '!=', TrialWeek::STATUS_PROGRAM_BUILT),
            default => null,
        };

        if ($this->source !== '') {
            $phoneUserIds = PhoneRegistrationLink::query()->whereNotNull('registered_user_id')->pluck('registered_user_id')->unique();
            $this->source === 'phone'
                ? $query->whereIn('user_id', $phoneUserIds)
                : $query->whereNotIn('user_id', $phoneUserIds);
        }

        $query->when(trim($this->search) !== '', function ($query) {
            $search = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($user) => $user->where('name', 'like', $search)->orWhere('mobile', 'like', $search))
                    ->orWhere('father_mobile', 'like', $search)
                    ->orWhere('mother_mobile', 'like', $search);
            });
        })->when($this->consultant !== '', fn ($q) => $q->where('acquisition_supporter_id', $this->consultant));

        return $query->latest()->paginate(25);
    }

    protected function segmentLabels(string $channel): array
    {
        $common = [
            'all' => 'همه پرونده‌ها',
            'active' => 'در جریان',
            'temporary' => 'عدم تمایل موقت',
            'definitive' => 'عدم تمایل قطعی',
            'registered' => 'ثبت‌نام موفق',
            'pending-registration' => 'ثبت‌نام تکمیل‌نشده',
        ];

        return $channel === 'phone'
            ? $common + ['no-call' => 'بدون تماس']
            : $common + ['unassigned' => 'بدون مشاور جذب'];
    }
}
