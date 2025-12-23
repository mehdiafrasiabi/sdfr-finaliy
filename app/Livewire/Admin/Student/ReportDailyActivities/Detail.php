<?php


namespace App\Livewire\Admin\Student\ReportDailyActivities;


use App\Exports\DailyReportExport;

use App\Models\AdvisingSession;

use App\Models\DailyReport;

use App\Models\User;

use App\Models\WeeklyProgram;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\Validator;

use Livewire\Component;

use Livewire\WithPagination;

use Maatwebsite\Excel\Facades\Excel;


class Detail extends Component

{

    use WithPagination, SEOTools;


    public $studentName;

    public $studentId;

    public $userId;


    // Session Selection

    public ?int $selectedSessionId = null;

    public array $sessions = [];


    // Stats

    public array $stats = [];


    // Comment Modal

    public bool $commentModalOpen = false;

    public ?int $commentReportId = null;

    public string $advisorCommentInput = '';

    public bool $advisorCommentReadonly = false;

    public string $commentStudentName = '';

    public ?string $commentStudentReply = null;


    // Detail Modal

    public bool $detailModalOpen = false;

    public ?int $selectedReportId = null;

    public array $reportPartsDetails = [];

    public array $selectedReportData = [];


    protected $paginationTheme = 'bootstrap';


    public function mount(User $student)

    {

        $this->userId = $student->id;

        $this->studentId = $student->student->id;

        $this->studentName = $student->personalInformation->name ?? $student->name;

        $this->seo()->setTitle('گزارش‌های ' . $this->studentName);

        $this->loadSessions();

        $this->markStudentRepliesAsSeen();

    }


    protected function loadSessions()

    {

        $this->sessions = AdvisingSession::where('student_id', $this->studentId)
            ->where('result_status', 'held')
            ->orderBy('activation_date', 'desc')
            ->get()
            ->map(function ($session) {

                return [

                    'id' => $session->id,

                    'label' => jdate($session->activation_date)->format('Y/m/d') . ' - ' . ($session->title ?? 'جلسه مشاوره'),

                ];

            })
            ->toArray();


        // Select first session by default

        if (!empty($this->sessions) && !$this->selectedSessionId) {

            $this->selectedSessionId = $this->sessions[0]['id'];

        }

    }


    public function updatedSelectedSessionId()

    {

        $this->resetPage();

        $this->loadStats();

    }


    protected function loadStats()

    {

        if (!$this->selectedSessionId) {

            $this->stats = [];

            return;

        }


        $weeklyProgram = WeeklyProgram::where('advising_session_id', $this->selectedSessionId)->first();

        if (!$weeklyProgram) {

            $this->stats = [];

            return;

        }


        $reports = DailyReport::where('student_id', $this->studentId)
            ->where('weekly_program_id', $weeklyProgram->id)
            ->with('reportParts.programPart')
            ->get();


        $totalReports = $reports->count();

        $approvedReports = $reports->where('status', 'approved')->count();

        $pendingReports = $reports->where('status', 'pending')->count();

        $rejectedReports = $reports->where('status', 'rejected')->count();

        $compensatoryReports = $reports->where('is_compensatory', true)->count();


        $totalParts = 0;

        $readParts = 0;

        $totalTests = 0;

        $doneTests = 0;

        $totalPhoneHours = 0;


        foreach ($reports as $report) {

            $totalPhoneHours += $report->phone_hours;

            foreach ($report->reportParts as $rp) {

                $totalParts++;

                if ($rp->is_read) $readParts++;

                $totalTests += $rp->programPart?->test_count ?? 0;

                $doneTests += $rp->tests_done;

            }

        }


        $this->stats = [

            'total_reports' => $totalReports,

            'approved_reports' => $approvedReports,

            'pending_reports' => $pendingReports,

            'rejected_reports' => $rejectedReports,

            'compensatory_reports' => $compensatoryReports,

            'total_parts' => $totalParts,

            'read_parts' => $readParts,

            'unread_parts' => $totalParts - $readParts,

            'total_tests' => $totalTests,

            'done_tests' => $doneTests,

            'undone_tests' => $totalTests - $doneTests,

            'total_phone_hours' => $totalPhoneHours,

            'read_percentage' => $totalParts > 0 ? round(($readParts / $totalParts) * 100) : 0,

            'test_percentage' => $totalTests > 0 ? round(($doneTests / $totalTests) * 100) : 0,

        ];

    }


    public function changeStatus($reportId, $value)

    {

        $validator = Validator::make(['status' => $value, 'id' => $reportId], [

            'id' => 'required|exists:daily_reports,id',

            'status' => 'required|in:pending,approved,rejected'

        ], [

            '*.required' => 'فیلد الزامی است.',

            'status.in' => 'وضعیت نامعتبر است.',

            'id.exists' => 'گزارش یافت نشد.'

        ]);


        $validator->validate();


        DailyReport::where('id', $reportId)->update(['status' => $value]);

        $this->resetValidation();

        $this->dispatch('success', 'وضعیت با موفقیت تغییر کرد.');

        $this->loadStats();

    }


    public function delete($reportId)

    {

        DailyReport::where('id', $reportId)->delete();

        $this->dispatch('success', 'گزارش با موفقیت حذف شد.');

        $this->loadStats();

    }


    public function openCommentModal(int $reportId)

    {

        $report = DailyReport::with('student.user')
            ->where('id', $reportId)
            ->firstOrFail();


        $this->commentReportId = $reportId;

        $this->advisorCommentInput = $report->advisor_comment ?? '';

        $this->advisorCommentReadonly = !empty($report->advisor_comment);

        $this->commentStudentName = $report->student->user->name ?? '';

        $this->commentStudentReply = $report->student_reply;

        $this->commentModalOpen = true;

    }


    public function closeCommentModal()

    {

        $this->commentModalOpen = false;

        $this->advisorCommentInput = '';

        $this->commentReportId = null;

        $this->advisorCommentReadonly = false;

        $this->commentStudentReply = null;

        $this->commentStudentName = '';

        $this->resetErrorBag('advisorCommentInput');

    }


    public function saveAdvisorComment()

    {

        if (!$this->commentReportId) return;


        $report = DailyReport::where('id', $this->commentReportId)->firstOrFail();


        if (!empty($report->advisor_comment)) {

            $this->dispatch('warning', 'برای این گزارش قبلاً نظری ثبت شده است.');

            $this->closeCommentModal();

            return;

        }


        $validated = $this->validate([

            'advisorCommentInput' => 'required|string|max:1000',

        ], [

            'advisorCommentInput.required' => 'متن نظر را وارد کنید.',

            'advisorCommentInput.max' => 'طول نظر نمی‌تواند بیشتر از ۱۰۰۰ کاراکتر باشد.',

        ]);


        $report->update([

            'advisor_comment' => $validated['advisorCommentInput'],

            'advisor_commented_at' => now(),

        ]);


        $this->dispatch('success', 'نظر شما ثبت شد.');

        $this->closeCommentModal();

    }


    public function openDetailModal(int $reportId)

    {

        $report = DailyReport::with([

            'student.user',

            'weeklyProgram',

            'reportParts.programPart.ccSubject',

            'reportParts.programPart.ccTopic',

        ])->where('id', $reportId)->firstOrFail();


        $this->selectedReportId = $reportId;


        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        $this->selectedReportData = [

            'student_name' => $report->student->user->name ?? 'نامشخص',

            'report_date' => jdate($report->report_date)->format('Y/m/d'),

            'day_name' => $dayNames[$report->day_of_week] ?? '-',

            'phone_hours' => $report->phone_hours,

            'description' => $report->description,

            'rating' => $report->rating,

            'rating_label' => DailyReport::RATINGS[$report->rating] ?? 'نامشخص',

            'is_compensatory' => $report->is_compensatory,

            'status' => $report->status,

        ];


        $dayOfWeek = $report->day_of_week;

        $programParts = $report->weeklyProgram
            ->parts()
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('part_order')
            ->get();


        $reportPartsMap = $report->reportParts->keyBy('program_part_id');


        $this->reportPartsDetails = [];

        $totalTests = 0;

        $doneTests = 0;

        $totalParts = 0;

        $readParts = 0;


        foreach ($programParts as $programPart) {

            $reportPart = $reportPartsMap->get($programPart->id);

            $isRead = $reportPart?->is_read ?? false;

            $testsDone = $reportPart?->tests_done ?? 0;

            $testCount = $programPart->test_count ?? 0;


            $totalParts++;

            if ($isRead) $readParts++;

            $totalTests += $testCount;

            $doneTests += $testsDone;


            $this->reportPartsDetails[] = [

                'id' => $programPart->id,

                'lesson_name' => $programPart->lesson_name,

                'subject_name' => $programPart->ccSubject->name ?? null,

                'topic_name' => $programPart->ccTopic->name ?? null,

                'duration_minutes' => $programPart->duration_minutes,

                'is_read' => $isRead,

                'tests_done' => $testsDone,

                'test_count' => $testCount,

                'is_compensatory' => $reportPart?->is_compensatory ?? false,

            ];

        }


        $this->selectedReportData['total_parts'] = $totalParts;

        $this->selectedReportData['read_parts'] = $readParts;

        $this->selectedReportData['unread_parts'] = $totalParts - $readParts;

        $this->selectedReportData['total_tests'] = $totalTests;

        $this->selectedReportData['done_tests'] = $doneTests;

        $this->selectedReportData['undone_tests'] = $totalTests - $doneTests;


        $this->detailModalOpen = true;

    }


    public function closeDetailModal()

    {

        $this->detailModalOpen = false;

        $this->selectedReportId = null;

        $this->reportPartsDetails = [];

        $this->selectedReportData = [];

    }


    public function exportExcel()

    {

        if (!$this->selectedSessionId) {

            $this->dispatch('warning', 'لطفاً یک جلسه مشاوره را انتخاب کنید.');

            return;

        }


        $weeklyProgram = WeeklyProgram::where('advising_session_id', $this->selectedSessionId)->first();

        if (!$weeklyProgram) {

            $this->dispatch('warning', 'برنامه‌ای برای این جلسه یافت نشد.');

            return;

        }


        $fileName = 'daily_reports_' . str_replace(' ', '_', $this->studentName) . '_' . now()->format('Ymd_His') . '.xlsx';


        return Excel::download(

            new DailyReportExport($this->studentId, $weeklyProgram->id),

            $fileName

        );

    }


    protected function markStudentRepliesAsSeen(): void

    {

        DailyReport::query()
            ->where('student_id', $this->studentId)
            ->whereNotNull('student_reply')
            ->whereNull('student_reply_seen_at')
            ->update(['student_reply_seen_at' => now()]);

    }


    public function getStatusColor($status): string

    {

        return match ($status) {

            'pending' => 'primary',

            'approved' => 'success',

            'rejected' => 'danger',

            default => 'secondary',

        };

    }


    public function render()

    {

        $reports = collect([]);


        if ($this->selectedSessionId) {

            $weeklyProgram = WeeklyProgram::where('advising_session_id', $this->selectedSessionId)->first();


            if ($weeklyProgram) {

                $reports = DailyReport::with([

                    'student.user',

                    'reportParts.programPart',

                ])
                    ->where('student_id', $this->studentId)
                    ->where('weekly_program_id', $weeklyProgram->id)
                    ->latest()
                    ->paginate(10);


                $this->loadStats();

            }

        }


        return view('livewire.admin.student.report-daily-activities.detail', [

            'reports' => $reports,

        ])->layout('layouts.admin.app');

    }

}
