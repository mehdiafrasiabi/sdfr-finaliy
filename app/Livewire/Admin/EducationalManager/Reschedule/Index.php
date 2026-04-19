<?php

namespace App\Livewire\Admin\EducationalManager\Reschedule;

use App\Models\AdminWorkSchedule;
use App\Models\AdvisingSession;
use App\Models\SessionRescheduleRequest;
use App\Models\Student;
use App\Models\StudentSchedulePreference;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $statusFilter = 'open';

    // Modal state برای اسلات‌های خالی
    public ?int $activeRequestId = null;
    /** @var array<int,array<int,int>> [day_of_week][hour] => studentCount */
    public array $weeklyGrid = [];
    public array $selectedEmptySlots = []; // [{day, start, end}]

    public function openGrid(int $requestId): void
    {
        $this->activeRequestId = $requestId;
        $this->selectedEmptySlots = [];
        $this->weeklyGrid = $this->buildWeeklyGrid();
    }

    public function closeGrid(): void
    {
        $this->activeRequestId = null;
        $this->weeklyGrid = [];
        $this->selectedEmptySlots = [];
    }

    /**
     * ساخت گرید هفتگی با تعداد دانش‌آموزان هر اسلات بر اساس ترجیحات تاییدشده.
     * خروجی: [day => [hour => count]]
     */
    protected function buildWeeklyGrid(): array
    {
        $grid = [];
        for ($d = 0; $d <= 6; $d++) {
            for ($h = 8; $h <= 20; $h++) {
                $grid[$d][$h] = 0;
            }
        }

        $prefs = StudentSchedulePreference::where('status', StudentSchedulePreference::STATUS_APPROVED)
            ->with('times')
            ->get();

        foreach ($prefs as $p) {
            foreach ($p->times as $t) {
                $sh = (int) substr($t->start_time, 0, 2);
                $eh = (int) substr($t->end_time, 0, 2);
                $em = (int) substr($t->end_time, 3, 2);
                // hours covered: [sh, eh) OR up to eh if minutes > 0
                $end = ($em > 0) ? $eh : $eh - 1;
                for ($h = $sh; $h <= $end && $h <= 20; $h++) {
                    if ($h < 8) continue;
                    $grid[$t->day_of_week][$h] = ($grid[$t->day_of_week][$h] ?? 0) + 1;
                }
            }
        }

        return $grid;
    }

    public function toggleEmptySlot(int $day, int $hour): void
    {
        $key = "$day-$hour";
        $idx = null;
        foreach ($this->selectedEmptySlots as $i => $s) {
            if ($s['day'] === $day && (int) substr($s['start'], 0, 2) === $hour) {
                $idx = $i;
                break;
            }
        }
        if ($idx !== null) {
            array_splice($this->selectedEmptySlots, $idx, 1);
        } else {
            $start = sprintf('%02d:00', $hour);
            $end   = sprintf('%02d:00', $hour + 1);
            $this->selectedEmptySlots[] = ['day' => $day, 'start' => $start, 'end' => $end];
        }
    }

    public function sendSlotsToConsultant(): void
    {
        if (!$this->activeRequestId || empty($this->selectedEmptySlots)) {
            session()->flash('error', 'حداقل یک اسلات خالی انتخاب کنید.');
            return;
        }
        $req = SessionRescheduleRequest::find($this->activeRequestId);
        if (!$req) return;

        $req->update([
            'manager_available_slots' => array_values($this->selectedEmptySlots),
            'status'                  => SessionRescheduleRequest::STATUS_AWAITING_CONSULTANT_PROP,
        ]);

        session()->flash('message', 'اسلات‌های خالی برای مشاور ارسال شد.');
        $this->closeGrid();
    }

    public function finalizeApprove(int $requestId): void
    {
        $req = SessionRescheduleRequest::with('student')->find($requestId);
        if (!$req) return;
        if ($req->status !== SessionRescheduleRequest::STATUS_AWAITING_MANAGER_FINAL) {
            session()->flash('error', 'این درخواست در وضعیت تایید نهایی نیست.');
            return;
        }

        DB::transaction(function () use ($req) {
            // اگر دائمی: به‌روزرسانی برنامه هفتگی
            if ($req->type === SessionRescheduleRequest::TYPE_PERMANENT) {
                $pref = $req->student->activeSchedulePreference()->with('times')->first();
                if ($pref) {
                    // حذف اسلات مبدا و افزودن اسلات جدید
                    if ($req->original_day !== null && $req->original_time) {
                        $pref->times()
                            ->where('day_of_week', $req->original_day)
                            ->where('start_time', $req->original_time)
                            ->delete();
                    }
                    $pref->times()->create([
                        'day_of_week' => $req->student_selected_day,
                        'start_time'  => $req->student_selected_time,
                        'end_time'    => $this->addHour($req->student_selected_time),
                    ]);
                }
            } else {
                // اگر استثنا: جلسه موجود را به زمان جدید ببر
                if ($req->original_session_id) {
                    AdvisingSession::where('id', $req->original_session_id)->update([
                        'session_time' => $req->student_selected_time,
                        // activation_date تغییر نمی‌دهیم چون ممکن است کاربر فقط ساعت را خواسته باشد؛
                        // در نسخه‌های بعدی بر اساس ترکیب روز/تاریخ می‌توان تاریخ را هم به‌روزرسانی کرد.
                    ]);
                }
            }
            $req->update([
                'status'      => SessionRescheduleRequest::STATUS_APPROVED,
                'approved_at' => now(),
            ]);
        });

        session()->flash('message', 'درخواست جابجایی تایید و اعمال شد.');
    }

    protected function addHour(string $time): string
    {
        $h = (int) substr($time, 0, 2) + 1;
        return sprintf('%02d:%s', $h, substr($time, 3, 2));
    }

    public function reject(int $requestId): void
    {
        $req = SessionRescheduleRequest::find($requestId);
        if ($req) {
            $req->update([
                'status'            => SessionRescheduleRequest::STATUS_REJECTED,
                'rejection_reason'  => 'رد از سوی مدیر آموزشی',
            ]);
            session()->flash('message', 'درخواست رد شد.');
        }
    }

    public function reassignConsultant(int $requestId): void
    {
        $req = SessionRescheduleRequest::find($requestId);
        if ($req) {
            // این صرفاً وضعیت را برمی‌گرداند تا مدیر آموزشی به صورت دستی مشاور جدید را
            // در صفحه‌ی دانش‌آموز تعویض کند و سپس درخواست را بازنشانی کند.
            $req->update(['status' => SessionRescheduleRequest::STATUS_PENDING_MANAGER]);
            session()->flash('message', 'درخواست به حالت بررسی بازگشت تا مشاور تعویض شود.');
        }
    }

    public function render()
    {
        $query = SessionRescheduleRequest::query()
            ->with(['student.user', 'advisor', 'originalSession']);

        if ($this->statusFilter === 'open') {
            $query->whereNotIn('status', [
                SessionRescheduleRequest::STATUS_APPROVED,
                SessionRescheduleRequest::STATUS_REJECTED,
            ]);
        } elseif ($this->statusFilter === 'approved') {
            $query->where('status', SessionRescheduleRequest::STATUS_APPROVED);
        } elseif ($this->statusFilter === 'rejected') {
            $query->where('status', SessionRescheduleRequest::STATUS_REJECTED);
        }

        $requests = $query->latest()->paginate(15);

        return view('livewire.admin.educational-manager.reschedule.index', [
            'requests'     => $requests,
            'days'         => AdminWorkSchedule::DAYS,
            'activeRequest' => $this->activeRequestId
                ? SessionRescheduleRequest::with(['student.user', 'advisor'])->find($this->activeRequestId)
                : null,
        ])->layout('layouts.admin.app');
    }
}

