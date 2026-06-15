<?php

namespace App\Livewire\Client\Profile;

use App\Models\AdvisingSession;
use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\ProgramPart;
use App\Models\TrialWeek;
use App\Models\WeeklyProgram;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class SuddenEventModal extends Component
{
    public bool $open = false;

    /** مرحله جریان: 0=هشدار، 1=انتخاب روز، 2=دسته، 3=کتاب/فصل، 4=پارت/ساعت، 5=تایید بار مطالعه، 6=جابجایی پارت‌ها */
    public int $step = 0;

    // ─── داده‌های فرم ───
    public ?int $eventDayIndex = null;       // آفست روز اتفاق از start_date برنامه
    public ?string $category = null;          // exam | homework | class_qa
    public ?int $ccSubjectId = null;
    public ?int $ccChapterId = null;
    public int $partCount = 1;
    public int $hours = 1;
    public int $minutes = 0;

    /** پارت‌های کم‌اهمیت انتخاب‌شده برای جابجایی (حالت «اوکی نیست») */
    public array $lowImportancePartIds = [];

    // ─── داده‌های کمکی (لود از سرور) ───
    public array $availableSubjects = [];
    public array $availableChapters = [];

    protected $student;

    public function mount(): void
    {
        $this->student = Auth::user()?->student;
    }

    // ────────────────────────────────────────────────────────────
    //  باز/بسته کردن
    // ────────────────────────────────────────────────────────────
    #[On('open-sudden-event')]
    public function openModal(): void
    {
        $this->resetState();
        $this->loadStudentSubjects();
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
        $this->resetState();
    }

    protected function resetState(): void
    {
        $this->step = 0;
        $this->eventDayIndex = null;
        $this->category = null;
        $this->ccSubjectId = null;
        $this->ccChapterId = null;
        $this->partCount = 1;
        $this->hours = 1;
        $this->minutes = 0;
        $this->lowImportancePartIds = [];
        $this->availableChapters = [];
    }

    // ────────────────────────────────────────────────────────────
    //  برنامه فعال (هم‌منطق با Dashboard)
    // ────────────────────────────────────────────────────────────
    protected function activeProgram(): ?WeeklyProgram
    {
        $student = $this->student ?? Auth::user()?->student;
        if (!$student) {
            return null;
        }

        if ($student->is_trial) {
            $trial = TrialWeek::where('user_id', Auth::id())->latest()->first();
            if ($trial && $trial->advising_session_id) {
                $prog = WeeklyProgram::where('advising_session_id', $trial->advising_session_id)->latest()->first();
                if ($prog) {
                    return $prog;
                }
            }
            return WeeklyProgram::where('student_id', $student->id)->latest()->first();
        }

        $activeSession = AdvisingSession::where('student_id', $student->id)
            ->where('result_status', 'held')
            ->orderBy('activation_date', 'desc')
            ->first();

        if (!$activeSession) {
            return WeeklyProgram::where('student_id', $student->id)->latest()->first();
        }

        return WeeklyProgram::where('advising_session_id', $activeSession->id)->first();
    }

    protected function programStart(): ?Carbon
    {
        $program = $this->activeProgram();
        return $program ? Carbon::parse($program->start_date)->startOfDay() : null;
    }

    protected function maxOffset(): int
    {
        $program = $this->activeProgram();
        if (!$program) {
            return 7;
        }
        $start = Carbon::parse($program->start_date)->startOfDay();
        $end = Carbon::parse($program->end_date ?? $start->copy()->addDays(7))->startOfDay();
        return max(0, (int) $start->diffInDays($end));
    }

    // ────────────────────────────────────────────────────────────
    //  لیست روزهای قابل‌انتخاب (فقط روزهای آینده، نه گذشته و نه امروز)
    // ────────────────────────────────────────────────────────────
    public function getAvailableDaysProperty(): array
    {
        $start = $this->programStart();
        if (!$start) {
            return [];
        }
        $today = Carbon::today();
        $max = $this->maxOffset();
        $days = [];

        // i>=1 لازم است تا «روز قبل» (هدف) درون بازه باشد
        for ($i = 1; $i <= $max; $i++) {
            $date = $start->copy()->addDays($i);
            if (!$date->gt($today)) {
                continue; // گذشته یا امروز را نمایش نده
            }
            $days[] = [
                'index' => $i,
                'date' => $date->toDateString(),
                'label' => jdate($date)->format('l j F'),
                'short' => jdate($date)->format('j F'),
            ];
        }
        return $days;
    }

    /** پارت‌های روز قبل از اتفاق (روز هدف) */
    public function getTargetDayPartsProperty()
    {
        $program = $this->activeProgram();
        if (!$program || $this->eventDayIndex === null) {
            return collect();
        }
        $targetIndex = $this->eventDayIndex - 1;
        return $program->parts()
            ->where('day_of_week', $targetIndex)
            ->with(['ccSubject', 'ccChapter'])
            ->orderBy('part_order')
            ->get();
    }

    /** بار مطالعه روز هدف: ساعت مشاور و ساعت خود دانش‌آموز */
    public function getTargetLoadProperty(): array
    {
        $program = $this->activeProgram();
        if (!$program || $this->eventDayIndex === null) {
            return ['advisor_minutes' => 0, 'student_minutes' => 0, 'new_minutes' => 0];
        }
        $targetIndex = $this->eventDayIndex - 1;

        $advisorMinutes = (int) $program->parts()
            ->where('day_of_week', $targetIndex)
            ->where('is_student_added', false)
            ->sum('duration_minutes');

        $studentMinutes = (int) $program->parts()
            ->where('day_of_week', $targetIndex)
            ->where('is_student_added', true)
            ->sum('duration_minutes');

        return [
            'advisor_minutes' => $advisorMinutes,
            'student_minutes' => $studentMinutes,
            'new_minutes' => $this->partCount * ($this->hours * 60 + $this->minutes),
        ];
    }

    // ────────────────────────────────────────────────────────────
    //  بارگذاری دروس/فصل بر اساس پایه و رشته
    // ────────────────────────────────────────────────────────────
    protected function loadStudentSubjects(): void
    {
        $this->availableSubjects = [];
        $student = $this->student ?? Auth::user()?->student;
        $personalInfo = $student?->user?->personalInformation;
        if (!$personalInfo) {
            return;
        }

        $grade = $personalInfo->grade;
        $field = $personalInfo->field;
        $ccField = $field ? CcField::where('slug', $field)->where('is_active', true)->first() : null;

        $ccGradeQuery = CcGrade::where('grade_number', $grade)->where('is_active', true);
        if ($ccField) {
            $ccGradeQuery->where('cc_field_id', $ccField->id);
        }
        $ccGrade = $ccGradeQuery->first();

        if ($ccGrade) {
            $ccFieldId = $ccGrade->cc_field_id ?? null;
            $this->availableSubjects = CcSubject::where('cc_grade_id', $ccGrade->id)
                ->when($ccFieldId, fn($q) => $q->where(function ($q2) use ($ccFieldId) {
                    $q2->where('cc_field_id', $ccFieldId)->orWhereNull('cc_field_id');
                }))
                ->orderBy('order')
                ->get(['id', 'name'])->toArray();
        } else {
            $ccGrades = CcGrade::where('grade_number', $grade)->where('is_active', true)->pluck('id');
            $this->availableSubjects = CcSubject::whereIn('cc_grade_id', $ccGrades)
                ->orderBy('order')->get(['id', 'name'])->toArray();
        }
    }

    public function updatedCcSubjectId($value): void
    {
        $this->ccChapterId = null;
        $this->availableChapters = [];
        if ($value) {
            $this->availableChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get(['id', 'name'])->toArray();
        }
    }

    // ────────────────────────────────────────────────────────────
    //  ناوبری مراحل
    // ────────────────────────────────────────────────────────────
    public function goToStep(int $step): void
    {
        $this->step = $step;
    }

    public function selectDay(int $index): void
    {
        $this->eventDayIndex = $index;
        $this->step = 2;
    }

    public function selectCategory(string $category): void
    {
        if (!in_array($category, ['exam', 'homework', 'class_qa'], true)) {
            return;
        }
        $this->category = $category;
    }

    public function nextFromCategory(): void
    {
        if (!$this->category) {
            $this->dispatch('warning', 'لطفاً دسته‌بندی اتفاق را انتخاب کن.');
            return;
        }
        $this->step = 3;
    }

    public function nextFromSubject(): void
    {
        if (!$this->ccSubjectId || !$this->ccChapterId) {
            $this->dispatch('warning', 'لطفاً کتاب و فصل را انتخاب کن.');
            return;
        }
        $this->step = 4;
    }

    public function nextFromParts(): void
    {
        $this->partCount = max(1, (int) $this->partCount);
        $this->hours = max(0, (int) $this->hours);
        $this->minutes = max(0, min(59, (int) $this->minutes));
        if (($this->hours * 60 + $this->minutes) <= 0) {
            $this->dispatch('warning', 'مدت زمان هر پارت باید بیشتر از صفر باشد.');
            return;
        }
        $this->step = 5;
    }

    public function toggleLowImportance(int $partId): void
    {
        if (in_array($partId, $this->lowImportancePartIds, true)) {
            $this->lowImportancePartIds = array_values(array_diff($this->lowImportancePartIds, [$partId]));
        } else {
            $this->lowImportancePartIds[] = $partId;
        }
    }

    // ────────────────────────────────────────────────────────────
    //  اعمال نهایی
    // ────────────────────────────────────────────────────────────

    /** «اوکی هست» → فقط افزودن پارت‌های اتفاق به روز قبل */
    public function confirmOkay(): void
    {
        $this->applyChanges(false);
    }

    /** رفتن به مرحله انتخاب پارت‌های کم‌اهمیت */
    public function startRedistribute(): void
    {
        $this->lowImportancePartIds = [];
        $this->step = 6;
    }

    /** «اوکی نیست» → جابجایی پارت‌های کم‌اهمیت + افزودن پارت اتفاق */
    public function confirmRedistribute(): void
    {
        if (empty($this->lowImportancePartIds)) {
            $this->dispatch('warning', 'حداقل یک پارت کم‌اهمیت برای جابجایی انتخاب کن.');
            return;
        }
        $this->applyChanges(true);
    }

    protected function applyChanges(bool $redistribute): void
    {
        $program = $this->activeProgram();
        if (!$program || $this->eventDayIndex === null || !$this->ccSubjectId || !$this->ccChapterId) {
            $this->dispatch('error', 'اطلاعات ناقص است.');
            return;
        }

        $targetIndex = $this->eventDayIndex - 1;
        $start = Carbon::parse($program->start_date)->startOfDay();
        $subject = CcSubject::find($this->ccSubjectId);
        $personalInfo = ($this->student ?? Auth::user()?->student)?->user?->personalInformation;
        $partMinutes = $this->hours * 60 + $this->minutes;

        DB::transaction(function () use ($program, $targetIndex, $start, $subject, $personalInfo, $partMinutes, $redistribute) {

            // ۱) در حالت «اوکی نیست»: جابجایی پارت‌های کم‌اهمیت روز هدف به روزهای بعد از اتفاق
            if ($redistribute && !empty($this->lowImportancePartIds)) {
                $targetDays = $this->remainingDayIndices();
                if (!empty($targetDays)) {
                    $parts = ProgramPart::where('weekly_program_id', $program->id)
                        ->whereIn('id', $this->lowImportancePartIds)
                        ->where('day_of_week', $targetIndex)
                        ->get();

                    $i = 0;
                    foreach ($parts as $part) {
                        $destIndex = $targetDays[$i % count($targetDays)];
                        $nextOrder = (int) ProgramPart::where('weekly_program_id', $program->id)
                            ->where('day_of_week', $destIndex)->max('part_order');
                        $part->day_of_week = $destIndex;
                        $part->part_order = $nextOrder + 1;
                        $part->part_date = $start->copy()->addDays($destIndex);
                        $part->save();
                        $i++;
                    }
                }
            }

            // ۲) افزودن پارت‌های اتفاق به روز هدف (روز قبل از اتفاق)
            $baseOrder = (int) ProgramPart::where('weekly_program_id', $program->id)
                ->where('day_of_week', $targetIndex)->max('part_order');

            for ($n = 1; $n <= $this->partCount; $n++) {
                ProgramPart::create([
                    'weekly_program_id' => $program->id,
                    'lesson_name' => $subject?->name ?? 'درس',
                    'part_date' => $start->copy()->addDays($targetIndex),
                    'day_of_week' => $targetIndex,
                    'part_order' => $baseOrder + $n,
                    'duration_minutes' => $partMinutes,
                    'part_type' => 'descriptive',
                    'source_type' => $this->category,
                    'is_student_added' => true,
                    'lesson_type' => $subject?->type === 'general' ? 'general' : 'specialized',
                    'grade' => $personalInfo?->grade,
                    'cc_grade_id' => $subject?->cc_grade_id,
                    'cc_field_id' => $subject?->cc_field_id,
                    'cc_subject_id' => $this->ccSubjectId,
                    'cc_chapter_id' => $this->ccChapterId,
                ]);
            }
        });

        $this->dispatch('success', 'اتفاق یهویی با موفقیت در برنامه‌ات اعمال شد.');
        $this->close();
        $this->dispatch('sudden-event-applied');
    }

    /** آفست روزهای بعد از روز اتفاق که روز استراحت نیستند (برای پخش پارت‌ها) */
    protected function remainingDayIndices(): array
    {
        $program = $this->activeProgram();
        if (!$program || $this->eventDayIndex === null) {
            return [];
        }
        $max = $this->maxOffset();
        $result = [];
        for ($j = $this->eventDayIndex; $j <= $max; $j++) {
            $hasParts = $program->parts()->where('day_of_week', $j)->exists();
            if ($hasParts) {
                $result[] = $j; // روز استراحت = بدون پارت، نادیده گرفته می‌شود
            }
        }
        // اگر روزی پیدا نشد، همه روزهای بعد را در نظر بگیر
        if (empty($result)) {
            for ($j = $this->eventDayIndex; $j <= $max; $j++) {
                $result[] = $j;
            }
        }
        return $result;
    }

    public function render()
    {
        return view('livewire.client.profile.sudden-event-modal', [
            'availableDays' => $this->getAvailableDaysProperty(),
            'targetDayParts' => $this->getTargetDayPartsProperty(),
            'targetLoad' => $this->getTargetLoadProperty(),
        ]);
    }
}
