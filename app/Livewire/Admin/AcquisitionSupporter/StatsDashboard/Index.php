<?php

namespace App\Livewire\Admin\AcquisitionSupporter\StatsDashboard;

use App\Models\AcquisitionContact;
use App\Models\TrialWeek;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * C-5 — داشبورد پشتیبان جذب: فقط آمار تجمیعی (بدون لیست).
 */
class Index extends Component
{
    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $totalStudents = TrialWeek::where('acquisition_supporter_id', $adminId)->count();

        $cutoff = Carbon::now()->subDays(2);

        $awaitingPrimary = TrialWeek::where('acquisition_supporter_id', $adminId)
            ->whereDoesntHave('acquisitionContacts', function ($q) {
                $q->where('type', AcquisitionContact::TYPE_INITIAL)
                    ->where('answered', true);
            })
            ->count();

        $awaitingSecondary = TrialWeek::where('acquisition_supporter_id', $adminId)
            ->whereHas('acquisitionContacts', function ($q) use ($cutoff) {
                $q->where('type', AcquisitionContact::TYPE_INITIAL)
                    ->where('answered', true)
                    ->where('contacted_at', '<=', $cutoff);
            })
            ->whereDoesntHave('acquisitionContacts', function ($q) {
                $q->where('type', AcquisitionContact::TYPE_SECONDARY)
                    ->where('answered', true);
            })
            ->count();

        $callsToday = AcquisitionContact::where('admin_id', $adminId)
            ->whereDate('contacted_at', Carbon::today())
            ->count();

        $callsAnsweredTotal = AcquisitionContact::where('admin_id', $adminId)
            ->where('answered', true)
            ->count();

        $callsUnansweredTotal = AcquisitionContact::where('admin_id', $adminId)
            ->where('answered', false)
            ->count();

        $callsTotal = $callsAnsweredTotal + $callsUnansweredTotal;
        $answeredRate = $callsTotal > 0
            ? (int) round(($callsAnsweredTotal / $callsTotal) * 100)
            : 0;

        $avgPrediction = AcquisitionContact::where('admin_id', $adminId)
            ->where('type', AcquisitionContact::TYPE_SECONDARY)
            ->where('answered', true)
            ->whereNotNull('prediction_percentage')
            ->avg('prediction_percentage');

        return view('livewire.admin.acquisition-supporter.stats-dashboard.index', [
            'totalStudents'         => $totalStudents,
            'awaitingPrimary'       => $awaitingPrimary,
            'awaitingSecondary'     => $awaitingSecondary,
            'callsToday'            => $callsToday,
            'callsAnsweredTotal'    => $callsAnsweredTotal,
            'callsUnansweredTotal'  => $callsUnansweredTotal,
            'answeredRate'          => $answeredRate,
            'avgPrediction'         => $avgPrediction ? (int) round($avgPrediction) : 0,
        ])->layout('layouts.admin.app');
    }
}
