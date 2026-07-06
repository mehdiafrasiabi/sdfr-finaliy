<?php

namespace App\Livewire\Admin\EducationalManager\Leave;

use App\Models\AdvisingSession;
use App\Models\AdvisorLeave;
use App\Models\Student;
use App\Services\NotificationService;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * پنل مدیر آموزشی — تایید/ردِ مرخصیِ مشاوران.
 * در صورتِ تایید، برای همه‌ی دانش‌آموزانِ آن روز یک «جلسه‌ی جبرانیِ بدونِ تاریخ» ساخته
 * می‌شود تا مشاور روز و ساعتِ آن را تعیین کند.
 */
class Index extends Component
{
    use WithPagination, SEOTools;

    public array $rejectReason = [];

    public function mount(): void
    {
        $this->seo()->setTitle('مرخصی مشاوران');
    }

    public function approve(int $leaveId): void
    {
        $leave = AdvisorLeave::with('advisor')->find($leaveId);
        if (! $leave || $leave->status !== AdvisorLeave::STATUS_PENDING) {
            $this->dispatch('warning', 'این درخواست دیگر معتبر نیست.');
            return;
        }

        $leaveDate = Carbon::parse($leave->leave_date);
        $dow = ($leaveDate->dayOfWeek + 1) % 7;

        // دانش‌آموزانِ روزِ ثابتِ آن روز
        $weeklyStudentIds = Student::where('advisor_id', $leave->advisor_id)
            ->where('session_day', $dow)
            ->pluck('id');

        // جلساتِ (از جمله جبرانی) که برای آن روز تاریخ‌گذاری شده‌اند
        $sessionsThatDay = AdvisingSession::where('advisor_id', $leave->advisor_id)
            ->whereDate('activation_date', $leave->leave_date)
            ->get();

        $affectedIds = $weeklyStudentIds
            ->merge($sessionsThatDay->pluck('student_id'))
            ->unique()
            ->values();

        DB::transaction(function () use ($leave, $sessionsThatDay, $affectedIds) {
            $leave->update([
                'status'      => AdvisorLeave::STATUS_APPROVED,
                'reviewed_by' => auth('admin')->id(),
                'reviewed_at' => now(),
            ]);

            // جلساتِ آن روز که قبلاً نهایی شده‌اند → غیبتِ مشاور
            foreach ($sessionsThatDay as $s) {
                if ($s->finalized && $s->result_status === null) {
                    $s->update([
                        'result_status' => AdvisingSession::RESULT_ADVISOR_ABSENT,
                        'status'        => AdvisingSession::STATUS_COMPLETED,
                    ]);
                }
            }

            // ساختِ جلسه‌ی جبرانیِ بدونِ تاریخ برای هر دانش‌آموزِ متاثر
            foreach ($affectedIds as $sid) {
                AdvisingSession::create([
                    'student_id'        => $sid,
                    'advisor_id'        => $leave->advisor_id,
                    'title'             => 'جلسه جبرانی',
                    'description'       => 'جلسه جبرانی (مرخصی مشاور)',
                    'activation_date'   => null,
                    'session_time'      => null,
                    'location_type'     => AdvisingSession::LOCATION_ONLINE,
                    'status'            => AdvisingSession::STATUS_INACTIVE,
                    'is_active'         => false,
                    'finalized'         => false,
                    'is_makeup'         => true,
                    'makeup_reason'     => AdvisingSession::MAKEUP_ADVISOR_LEAVE,
                ]);

                NotificationService::sendToStudent(
                    $sid,
                    'تغییر جلسه مشاوره',
                    'به‌دلیلِ مرخصیِ مشاور، جلسه‌ی این هفته‌ی شما به یک «جلسه‌ی جبرانی» موکول شد. مشاور به‌زودی روز و ساعتِ آن را اعلام می‌کند.'
                );
            }
        });

        $this->dispatch('success', 'مرخصی تایید شد و ' . $affectedIds->count() . ' جلسه‌ی جبرانی ساخته شد.');
    }

    public function reject(int $leaveId): void
    {
        $leave = AdvisorLeave::find($leaveId);
        if (! $leave || $leave->status !== AdvisorLeave::STATUS_PENDING) {
            $this->dispatch('warning', 'این درخواست دیگر معتبر نیست.');
            return;
        }

        $leave->update([
            'status'        => AdvisorLeave::STATUS_REJECTED,
            'reject_reason' => $this->rejectReason[$leaveId] ?? null,
            'reviewed_by'   => auth('admin')->id(),
            'reviewed_at'   => now(),
        ]);

        unset($this->rejectReason[$leaveId]);
        $this->dispatch('success', 'درخواستِ مرخصی رد شد.');
    }

    public function render()
    {
        $pending = AdvisorLeave::where('status', AdvisorLeave::STATUS_PENDING)
            ->with('advisor')
            ->latest()
            ->get();

        $history = AdvisorLeave::whereIn('status', [AdvisorLeave::STATUS_APPROVED, AdvisorLeave::STATUS_REJECTED])
            ->with(['advisor', 'reviewer'])
            ->latest('reviewed_at')
            ->paginate(15);

        return view('livewire.admin.educational-manager.leave.index', [
            'pending' => $pending,
            'history' => $history,
        ])->layout('layouts.admin.app');
    }
}
