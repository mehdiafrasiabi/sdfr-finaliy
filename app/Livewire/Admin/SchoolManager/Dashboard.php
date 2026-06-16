<?php

namespace App\Livewire\Admin\SchoolManager;

use App\Models\AdvisingSession;
use App\Models\EmergencyCall;
use App\Models\SchoolParentContact;
use App\Models\SchoolStudentGrade;
use App\Models\SmartReportCard;
use App\Models\Student;
use App\Models\StudyPartSession;
use App\Support\ClassificationProgress;
use App\Traits\UploadFile;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Morilog\Jalali\Jalalian;

/**
 * داشبورد جامع مدیر مدرسه:
 *  ۱) تعداد کل جلسات (برگزارشده/نشده) + اسامی در مودال (نشده‌ها با علت)
 *  ۲) میزان تسلط هر دانش‌آموز در هر درس بر اساس طبقه‌بندی (A خیلی‌خوب … D ضعیف)
 *  ۳) نفرات برتر کارنامهٔ ماهانه (معدل کل) + برترین‌های ساعت مطالعه
 *  ۴) رشد/پسرفت نسبت به ماه قبل + اسامی در مودال
 *  ۵) تعداد کل دانش‌آموزان
 *  ۶) تماس‌های این ماه (گرفته/نگرفته) — هدف حداقل ۴ تماس در ماه
 *  ۷) تماس‌های اورژانسیِ ثبت‌شده توسط مشاور برای پیگیری
 */
class Dashboard extends Component
{
    use WithFileUploads, UploadFile;

    public const CALL_TARGET = 4;
    public const TOP_LIMIT = 10;

    public const MASTERY_LABELS = [
        4 => 'خیلی خوب', // A
        3 => 'خوب',      // B
        2 => 'متوسط',    // C
        1 => 'ضعیف',     // D
    ];

    public int $jalaliYear;
    public int $jalaliMonth;

    public ?string $modal = null;        // 'held' | 'notheld' | 'grew' | 'regressed' | 'calls' | 'students'
    public ?int $masteryStudentId = null;

    public $schoolImage = null;          // فایل آپلودیِ عکس مدرسه

    public function mount(): void
    {
        $this->schoolId();
        $now = Jalalian::now();
        $this->jalaliYear  = (int) $now->getYear();
        $this->jalaliMonth = (int) $now->getMonth();
    }

    private function schoolId(): ?int
    {
        $admin = auth('admin')->user();
        abort_unless($admin?->school_id || $admin?->hasRole('super admin'), 403);
        return $admin?->school_id;
    }

    public function openModal(string $name): void { $this->modal = $name; }
    public function closeModal(): void { $this->modal = null; }
    public function showMastery(int $studentId): void { $this->masteryStudentId = $studentId; }
    public function closeMastery(): void { $this->masteryStudentId = null; }

    /**
     * آپلود عکس مدرسه توسط مدیر مدرسه: تبدیل به webp و ذخیره در public_html/schools/{id}.
     * در داشبورد دانش‌آموزانِ مدرسه نمایش داده می‌شود.
     */
    public function saveSchoolImage(): void
    {
        $school = auth('admin')->user()?->managedSchool;
        abort_unless($school, 403);

        $this->validate(
            ['schoolImage' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240'],
            [
                'schoolImage.required' => 'لطفاً یک تصویر انتخاب کنید.',
                'schoolImage.image'    => 'فایل باید یک تصویر باشد.',
                'schoolImage.mimes'    => 'فرمت‌های مجاز: JPG, PNG, WEBP',
                'schoolImage.max'      => 'حجم مجاز تصویر تا ۱۰ مگابایت است.',
            ]
        );

        $filename = $this->uploadImageInWebpFormatSdfrSchool($this->schoolImage, $school->id, null, null, 'schools');
        $school->update(['image' => $filename]);

        $this->reset('schoolImage');
        $this->dispatch('success', 'عکس مدرسه ثبت شد و در داشبورد دانش‌آموزان نمایش داده می‌شود.');
    }

    public function resolveEmergency(int $id): void
    {
        $schoolStudentIds = Student::where('school_id', $this->schoolId())->pluck('id');
        $call = EmergencyCall::whereIn('student_id', $schoolStudentIds)->findOrFail($id);
        $call->update([
            'status'      => EmergencyCall::STATUS_RESOLVED,
            'resolved_by' => auth('admin')->id(),
            'resolved_at' => now(),
        ]);
        $this->dispatch('success', 'تماس اورژانسی به‌عنوان «پیگیری‌شده» علامت خورد.');
    }

    /** معدل کل هر دانش‌آموز در یک ماه (میانگین معدل دروس، از ۲۰). */
    private function monthlyAverages(Collection $studentIds, string $monthKey): Collection
    {
        return SchoolStudentGrade::whereIn('student_id', $studentIds)
            ->where('jalali_month', $monthKey)
            ->get()
            ->groupBy('student_id')
            ->map(function ($rows) {
                $avgs = $rows->map(fn($r) => $r->subject_average)->filter(fn($v) => $v !== null);
                return $avgs->isNotEmpty() ? round($avgs->avg(), 2) : null;
            })
            ->filter(fn($v) => $v !== null);
    }

    private function prevMonthKey(): string
    {
        $y = $this->jalaliYear;
        $m = $this->jalaliMonth - 1;
        if ($m < 1) { $m = 12; $y--; }
        return sprintf('%04d-%02d', $y, $m);
    }

    public function render()
    {
        $schoolId = $this->schoolId();
        $admin = auth('admin')->user();

        $students = Student::with('user')->where('school_id', $schoolId)->get();
        $studentIds = $students->pluck('id');
        $studentNameById = $students->mapWithKeys(fn($s) => [$s->id => $s->user?->name ?? '—']);

        $monthKey = sprintf('%04d-%02d', $this->jalaliYear, $this->jalaliMonth);
        $range = SmartReportCard::jalaliMonthRange($this->jalaliYear, $this->jalaliMonth);

        // ۱) جلسات این ماه
        $sessions = AdvisingSession::with('student.user')
            ->whereIn('student_id', $studentIds)
            ->whereBetween('activation_date', [$range['start']->toDateString(), $range['end']->toDateString()])
            ->get();

        $heldSessions = $sessions->where('result_status', AdvisingSession::RESULT_HELD)->values();
        $notHeldSessions = $sessions->whereIn('result_status', [
            AdvisingSession::RESULT_ADVISOR_ABSENT,
            AdvisingSession::RESULT_STUDENT_ABSENT,
        ])->map(fn($s) => [
            'name'   => $s->student?->user?->name ?? '—',
            'reason' => $s->result_status === AdvisingSession::RESULT_ADVISOR_ABSENT ? 'غیبت مشاور' : 'غیبت دانش‌آموز',
            'date'   => $s->activation_date ? jdate($s->activation_date)->format('Y/m/d') : '—',
        ])->values();

        // ۴) رشد/پسرفت نسبت به ماه قبل (بر اساس معدل کل ماهانه)
        $curAvg  = $this->monthlyAverages($studentIds, $monthKey);
        $prevAvg = $this->monthlyAverages($studentIds, $this->prevMonthKey());

        $grew = collect();
        $regressed = collect();
        foreach ($curAvg as $sid => $avg) {
            if (!$prevAvg->has($sid)) { continue; }
            $delta = round($avg - $prevAvg[$sid], 2);
            $entry = ['name' => $studentNameById[$sid] ?? '—', 'prev' => $prevAvg[$sid], 'cur' => $avg, 'delta' => $delta];
            if ($delta > 0) { $grew->push($entry); }
            elseif ($delta < 0) { $regressed->push($entry); }
        }
        $grew = $grew->sortByDesc('delta')->values();
        $regressed = $regressed->sortBy('delta')->values();

        // ۳) نفرات برتر معدل ماهانه
        $topGrades = $curAvg->map(fn($avg, $sid) => ['name' => $studentNameById[$sid] ?? '—', 'avg' => $avg])
            ->sortByDesc('avg')->take(self::TOP_LIMIT)->values();

        // ۳) نفرات برتر ساعت مطالعه (این ماه)
        $studySeconds = StudyPartSession::whereIn('student_id', $studentIds)
            ->whereBetween('started_at', [$range['start'], $range['end']])
            ->selectRaw('student_id, SUM(duration_seconds) as secs')
            ->groupBy('student_id')
            ->pluck('secs', 'student_id');

        $topStudy = $studySeconds->map(fn($secs, $sid) => [
            'name'  => $studentNameById[$sid] ?? '—',
            'hours' => round(((int) $secs) / 3600, 1),
        ])->sortByDesc('hours')->take(self::TOP_LIMIT)->values();

        // ۶) تماس‌های این ماه (هدف حداقل ۴)
        $contacts = SchoolParentContact::whereIn('student_id', $studentIds)
            ->whereBetween('contacted_at', [$range['start'], $range['end']])
            ->get(['student_id']);
        $contactCountByStudent = $contacts->groupBy('student_id')->map->count();

        $callsNotMet = $students->map(function ($s) use ($contactCountByStudent) {
            $c = (int) ($contactCountByStudent[$s->id] ?? 0);
            return ['name' => $s->user?->name ?? '—', 'count' => $c];
        })->filter(fn($r) => $r['count'] < self::CALL_TARGET)->sortBy('count')->values();

        $callsMetCount = $students->count() - $callsNotMet->count();

        // ۲) میزان تسلط بر اساس طبقه‌بندی (آخرین رتبهٔ هر درس)
        $userIds = $students->pluck('user_id')->filter()->values()->all();
        $userToStudent = $students->filter(fn($s) => $s->user_id)->mapWithKeys(fn($s) => [$s->user_id => $s->id]);
        $ratings = ClassificationProgress::subjectRatings($userIds);
        $masteryByStudent = [];
        foreach ($ratings['data'] as $uid => $subs) {
            $sid = $userToStudent[$uid] ?? null;
            if (!$sid) { continue; }
            foreach ($subs as $subjectId => $series) {
                $latest = ClassificationProgress::latestPrevious($series)['latest'];
                if ($latest === null) { continue; }
                $rounded = (int) round($latest);
                $masteryByStudent[$sid][] = [
                    'subject' => $ratings['subjects'][$subjectId] ?? '—',
                    'rating'  => $rounded,
                    'label'   => self::MASTERY_LABELS[$rounded] ?? '—',
                ];
            }
        }
        $masteryStudents = $students->filter(fn($s) => isset($masteryByStudent[$s->id]))
            ->map(fn($s) => ['id' => $s->id, 'name' => $s->user?->name ?? '—'])
            ->sortBy('name')->values();

        // ۷) تماس‌های اورژانسی برای پیگیری
        $emergencyCalls = EmergencyCall::with(['student.user', 'admin'])
            ->whereIn('student_id', $studentIds)
            ->orderByRaw("status = 'pending' DESC")
            ->latest('called_at')
            ->limit(50)
            ->get();

        return view('livewire.admin.school-manager.dashboard', [
            'school'           => $admin?->managedSchool,
            'studentCount'     => $students->count(),
            'studentNames'     => $students->map(fn($s) => $s->user?->name ?? '—')->sort()->values(),
            'monthNames'       => SmartReportCard::MONTH_NAMES,
            'yearOptions'      => range($this->jalaliYear, $this->jalaliYear - 2),
            'heldCount'        => $heldSessions->count(),
            'heldNames'        => $heldSessions->map(fn($s) => $s->student?->user?->name ?? '—')->values(),
            'notHeldCount'     => $notHeldSessions->count(),
            'notHeldSessions'  => $notHeldSessions,
            'totalSessions'    => $sessions->count(),
            'grew'             => $grew,
            'regressed'        => $regressed,
            'topGrades'        => $topGrades,
            'topStudy'         => $topStudy,
            'callTarget'       => self::CALL_TARGET,
            'callsMetCount'    => $callsMetCount,
            'callsNotMet'      => $callsNotMet,
            'masteryStudents'  => $masteryStudents,
            'masteryByStudent' => $masteryByStudent,
            'emergencyCalls'   => $emergencyCalls,
            'emergencyPending' => $emergencyCalls->where('status', EmergencyCall::STATUS_PENDING)->count(),
        ])->layout('layouts.admin.app');
    }
}
