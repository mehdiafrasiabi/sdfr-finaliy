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
    public $step = 0;

    // ─── بدون strict type تا coercion ایمن کار کنه ───
    public $eventDayIndex = null;
    public $category = null;

    // درسی که در حال حاضر (قبل از افزودن به فهرست نهایی) در حال انتخاب فصل‌هایش هستیم
    public $pendingSubjectId = null;
    public array $pendingChapterIds = [];

    /**
     * دروس و فصل‌های نهایی‌شده برای این اتفاق یهویی. ساختار هر آیتم:
     * [
     *   'subject_id' => int, 'subject_name' => string,
     *   'chapters' => [
     *      ['chapter_id' => int, 'chapter_name' => string, 'part_count' => int, 'hours' => int, 'minutes' => int],
     *      ...
     *   ],
     * ]
     * این ساختار اجازه می‌دهد چند درس، و برای هر درس چند فصل، هر کدام با تعداد پارت
     * و مدت زمان مستقل انتخاب شوند (مثلاً «شیمی۱: فصل۲, فصل۳» + «هندسه: فصل۱»).
     */
    public array $selections = [];

    public array $lowImportancePartIds = [];
    public array $availableSubjects = [];
    public array $availableChapters = [];

    /**
     * پیام خطا/هشدارِ مرحلهٔ جاری. برخلافِ toastِ سراسری (که ممکن است دیده نشود یا
     * با تأخیر ظاهر شود)، این پیام همیشه *داخل خودِ مودال* نمایش داده می‌شود؛ طبق
     * درخواستِ صریح که خطاها نباید مودال را ببندند یا کاربر را بیرون از مودال دنبالِ
     * پیام بگردانند.
     */
    public $stepError = null;

    public const MIN_PART_MINUTES = 30;
    public const MINUTE_STEP = 5;
    public const MAX_HOURS = 12;

    protected $student;

    public function mount(): void
    {
        $this->student = Auth::user()?->student;
    }

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
        $this->pendingSubjectId = null;
        $this->pendingChapterIds = [];
        $this->selections = [];
        $this->lowImportancePartIds = [];
        $this->availableChapters = [];
        $this->stepError = null;
    }

    /** پاک‌کردنِ پیامِ خطای مرحلهٔ جاری (برای دکمهٔ ✕ روی بنر خطا). */
    public function clearStepError(): void
    {
        $this->stepError = null;
    }

    /**
     * اجرای امنِ یک عملیات: اگر خطای غیرمنتظره‌ای رخ دهد (مثلاً ایندکس نامعتبر در آرایه)،
     * به‌جای اینکه Livewire یک استثنای مدیریت‌نشده پرتاب کند و کل مودال به‌هم بریزد،
     * پیام را داخل همین مودال (stepError) نشان می‌دهیم — دقیقاً طبق آنچه خواسته شده بود.
     */
    protected function safeRun(callable $action): void
    {
        try {
            $action();
        } catch (\Throwable $e) {
            report($e);
            $this->stepError = 'مشکلی پیش آمد؛ لطفاً دوباره تلاش کن.';
        }
    }

    protected function activeProgram(): ?WeeklyProgram
    {
        $student = $this->student ?? Auth::user()?->student;
        if (!$student) return null;

        if ($student->is_trial) {
            $trial = TrialWeek::where('user_id', Auth::id())->latest()->first();
            if ($trial && $trial->advising_session_id) {
                $prog = WeeklyProgram::where('advising_session_id', $trial->advising_session_id)->latest()->first();
                if ($prog) return $prog;
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

    /**
     * وضعیت دسترسی دانش‌آموز به ثبت اتفاق یهویی:
     *  - none     : هیچ برنامه‌ای ندارد
     *  - expired  : برنامه دارد ولی از تاریخ پایان آن گذشته است
     *  - ok       : برنامهٔ فعال و در بازهٔ معتبر دارد
     */
    public function getAccessStateProperty(): string
    {
        $program = $this->activeProgram();
        if (!$program) {
            return 'none';
        }

        $start = Carbon::parse($program->start_date)->startOfDay();
        $end = Carbon::parse($program->end_date ?? $start->copy()->addDays(7))->endOfDay();

        if (Carbon::now()->gt($end)) {
            return 'expired';
        }

        return 'ok';
    }

    protected function maxOffset(): int
    {
        $program = $this->activeProgram();
        if (!$program) return 7;
        $start = Carbon::parse($program->start_date)->startOfDay();
        $end = Carbon::parse($program->end_date ?? $start->copy()->addDays(7))->startOfDay();
        return max(0, (int) $start->diffInDays($end));
    }

    public function getAvailableDaysProperty(): array
    {
        $start = $this->programStart();
        if (!$start) return [];
        $today = Carbon::today();
        $max = $this->maxOffset();
        $days = [];
        // امروز و روزهای آیندهٔ این هفته قابل انتخاب‌اند (روزهای گذشته غیرفعال).
        for ($i = 0; $i <= $max; $i++) {
            $date = $start->copy()->addDays($i);
            if ($date->lt($today)) continue;
            $days[] = [
                'index' => $i,
                'date' => $date->toDateString(),
                'label' => jdate($date)->format('l j F'),
                'short' => jdate($date)->format('j F'),
                'is_today' => $date->equalTo($today),
            ];
        }
        return $days;
    }

    /**
     * شاخصِ روزی که برنامه‌اش اصلاح می‌شود.
     * به‌صورت پیش‌فرض «روز قبل از اتفاق» است (برای آماده‌سازی قبل از اتفاق)؛
     * اما اگر روزِ قبل در گذشته باشد (مثلاً اتفاق امروز است)، خودِ روزِ اتفاق هدف می‌شود.
     */
    protected function targetDayIndex(): ?int
    {
        if ($this->eventDayIndex === null) return null;
        $start = $this->programStart();
        if (!$start) return null;

        $dayBefore = ((int) $this->eventDayIndex) - 1;
        if ($dayBefore < 0) {
            return (int) $this->eventDayIndex;
        }
        $dayBeforeDate = $start->copy()->addDays($dayBefore);
        if ($dayBeforeDate->lt(Carbon::today())) {
            return (int) $this->eventDayIndex; // روز قبل گذشته است؛ همان روزِ اتفاق را اصلاح کن
        }
        return $dayBefore;
    }

    /** برچسب تاریخ شمسیِ روزی که برنامه‌اش اصلاح می‌شود (مثلاً «شنبه ۲۵ خرداد»). */
    public function getTargetDayLabelProperty(): string
    {
        $start = $this->programStart();
        $ti = $this->targetDayIndex();
        if (!$start || $ti === null) return '';
        return jdate($start->copy()->addDays($ti))->format('l j F');
    }

    public function getTargetDayPartsProperty()
    {
        $program = $this->activeProgram();
        $targetIndex = $this->targetDayIndex();
        if (!$program || $targetIndex === null) return collect();
        return $program->parts()
            ->where('day_of_week', $targetIndex)
            ->with(['ccSubject', 'ccChapter'])
            ->orderBy('part_order')
            ->get();
    }

    /** مجموع تعداد پارت‌های جدیدی که در همهٔ دروس/فصل‌های انتخابی ثبت خواهند شد. */
    public function getNewPartsCountProperty(): int
    {
        $count = 0;
        foreach ($this->selections as $sel) {
            foreach (($sel['chapters'] ?? []) as $ch) {
                $count += max(1, (int) ($ch['part_count'] ?? 1));
            }
        }
        return $count;
    }

    public function getTargetLoadProperty(): array
    {
        $program = $this->activeProgram();
        $targetIndex = $this->targetDayIndex();
        if (!$program || $targetIndex === null) {
            return ['advisor_minutes' => 0, 'student_minutes' => 0, 'new_minutes' => 0];
        }

        $advisorMinutes = (int) $program->parts()
            ->where('day_of_week', $targetIndex)
            ->where('is_student_added', false)
            ->sum('duration_minutes');

        $studentMinutes = (int) $program->parts()
            ->where('day_of_week', $targetIndex)
            ->where('is_student_added', true)
            ->sum('duration_minutes');

        $newMinutes = 0;
        foreach ($this->selections as $sel) {
            foreach (($sel['chapters'] ?? []) as $ch) {
                $partMin = ((int) ($ch['hours'] ?? 0)) * 60 + ((int) ($ch['minutes'] ?? 0));
                $newMinutes += max(1, (int) ($ch['part_count'] ?? 1)) * $partMin;
            }
        }

        return [
            'advisor_minutes' => $advisorMinutes,
            'student_minutes' => $studentMinutes,
            'new_minutes' => $newMinutes,
        ];
    }

    protected function loadStudentSubjects(): void
    {
        $this->availableSubjects = [];
        $student = $this->student ?? Auth::user()?->student;
        $personalInfo = $student?->user?->personalInformation;
        if (!$personalInfo) return;

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

    public function updatedPendingSubjectId($value): void
    {
        $this->stepError = null;
        $this->pendingChapterIds = [];
        $this->availableChapters = [];
        if ($value) {
            $this->availableChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get(['id', 'name'])->toArray();
        }
    }

    /**
     * تیک‌زدن/برداشتنِ یک فصل از فهرستِ «در حال انتخاب» (pendingChapterIds).
     * به‌جای یک dropdown چندانتخابی، این متد با دکمه‌های چیپِ همیشه‌نمایان کار می‌کند —
     * دقیقاً همان الگویی که برای انتخاب روز و پارت‌های کم‌اهمیت در همین مودال استفاده شده.
     */
    public function toggleChapterSelection($chapterId): void
    {
        $this->stepError = null;
        $chapterId = (int) $chapterId;
        if (in_array($chapterId, $this->pendingChapterIds, true)) {
            $this->pendingChapterIds = array_values(array_diff($this->pendingChapterIds, [$chapterId]));
        } else {
            $this->pendingChapterIds[] = $chapterId;
        }
    }

    public function goToStep($step): void
    {
        $this->stepError = null;
        $this->step = (int) $step;
    }

    public function selectDay($index): void
    {
        $this->stepError = null;
        $this->eventDayIndex = (int) $index;
        $this->step = 2;
    }

    public function selectCategory(string $category): void
    {
        if (!in_array($category, ['exam', 'homework', 'class_qa'], true)) return;
        $this->category = $category;
    }

    public function nextFromCategory(): void
    {
        if (!$this->category) {
            $this->stepError = 'لطفاً دسته‌بندی اتفاق را انتخاب کن.';
            return;
        }
        $this->stepError = null;
        $this->step = 3;
    }

    /**
     * افزودن درسِ در حال انتخاب (با فصل‌های چندگانه‌اش) به فهرست نهایی $selections.
     * اگر درس از قبل در فهرست باشد، فصل‌های جدید (بدون تکرار) به همان درس اضافه می‌شوند.
     */
    public function addLessonSelection(): void
    {
        if (!$this->pendingSubjectId || empty($this->pendingChapterIds)) {
            $this->stepError = 'لطفاً یک درس و حداقل یک فصل از آن را انتخاب کن.';
            return;
        }
        $this->stepError = null;

        $this->safeRun(function () {
            $subject = collect($this->availableSubjects)->first(fn($s) => (int) $s['id'] === (int) $this->pendingSubjectId);
            $chaptersPool = collect($this->availableChapters);

            $newChapters = [];
            foreach ($this->pendingChapterIds as $chapterId) {
                $chapter = $chaptersPool->first(fn($c) => (int) $c['id'] === (int) $chapterId);
                if (!$chapter) continue;
                $newChapters[] = [
                    'chapter_id' => (int) $chapterId,
                    'chapter_name' => $chapter['name'] ?? 'فصل',
                    'part_count' => 1,
                    'hours' => 0,
                    'minutes' => self::MINUTE_STEP * 6, // ۳۰ دقیقهٔ پیش‌فرض
                ];
            }

            if (empty($newChapters)) {
                return;
            }

            $existingIndex = null;
            foreach ($this->selections as $i => $sel) {
                if ((int) $sel['subject_id'] === (int) $this->pendingSubjectId) {
                    $existingIndex = $i;
                    break;
                }
            }

            if ($existingIndex !== null) {
                $existingChapterIds = array_column($this->selections[$existingIndex]['chapters'], 'chapter_id');
                foreach ($newChapters as $ch) {
                    if (!in_array($ch['chapter_id'], $existingChapterIds, true)) {
                        $this->selections[$existingIndex]['chapters'][] = $ch;
                    }
                }
            } else {
                $this->selections[] = [
                    'subject_id' => (int) $this->pendingSubjectId,
                    'subject_name' => $subject['name'] ?? 'درس',
                    'chapters' => $newChapters,
                ];
            }

            $this->pendingSubjectId = null;
            $this->pendingChapterIds = [];
            $this->availableChapters = [];
        });
    }

    public function removeLessonSelection($index): void
    {
        $index = (int) $index;
        if (!isset($this->selections[$index])) return;
        unset($this->selections[$index]);
        $this->selections = array_values($this->selections);
    }

    public function removeChapterSelection($lessonIndex, $chapterIndex): void
    {
        $lessonIndex = (int) $lessonIndex;
        $chapterIndex = (int) $chapterIndex;
        if (!isset($this->selections[$lessonIndex]['chapters'][$chapterIndex])) return;

        unset($this->selections[$lessonIndex]['chapters'][$chapterIndex]);
        $this->selections[$lessonIndex]['chapters'] = array_values($this->selections[$lessonIndex]['chapters']);

        if (empty($this->selections[$lessonIndex]['chapters'])) {
            unset($this->selections[$lessonIndex]);
        }
        $this->selections = array_values($this->selections);
    }

    public function nextFromLessons(): void
    {
        $hasChapter = false;
        foreach ($this->selections as $sel) {
            if (!empty($sel['chapters'])) {
                $hasChapter = true;
                break;
            }
        }
        if (!$hasChapter) {
            $this->stepError = 'لطفاً حداقل یک درس و یک فصل انتخاب کن.';
            return;
        }
        $this->stepError = null;
        $this->step = 4;
    }

    public function nextFromParts(): void
    {
        $this->safeRun(function () {
            foreach ($this->selections as $si => $sel) {
                foreach (($sel['chapters'] ?? []) as $ci => $ch) {
                    $partCount = max(1, min(20, (int) ($ch['part_count'] ?? 1)));
                    $hours = max(0, min(self::MAX_HOURS, (int) ($ch['hours'] ?? 0)));
                    $rawMinutes = max(0, min(59, (int) ($ch['minutes'] ?? 0)));
                    $minutes = (int) (round($rawMinutes / self::MINUTE_STEP) * self::MINUTE_STEP);
                    if ($minutes >= 60) $minutes = 55;

                    $this->selections[$si]['chapters'][$ci]['part_count'] = $partCount;
                    $this->selections[$si]['chapters'][$ci]['hours'] = $hours;
                    $this->selections[$si]['chapters'][$ci]['minutes'] = $minutes;

                    $total = $hours * 60 + $minutes;
                    if ($total < self::MIN_PART_MINUTES) {
                        $this->stepError = 'مدت هر پارت برای «' . ($sel['subject_name'] ?? 'درس') . ' – ' . ($ch['chapter_name'] ?? 'فصل') . '» باید حداقل ' . self::MIN_PART_MINUTES . ' دقیقه باشد.';
                        return;
                    }
                }
            }
            $this->stepError = null;
            $this->step = 5;
        });
    }

    public function toggleLowImportance($partId): void
    {
        $partId = (int) $partId;
        if (in_array($partId, $this->lowImportancePartIds, true)) {
            $this->lowImportancePartIds = array_values(array_diff($this->lowImportancePartIds, [$partId]));
        } else {
            $this->lowImportancePartIds[] = $partId;
        }
    }

    public function confirmOkay(): void
    {
        $this->applyChanges(false);
    }

    public function startRedistribute(): void
    {
        $this->stepError = null;
        $this->lowImportancePartIds = [];
        $this->step = 6;
    }

    public function confirmRedistribute(): void
    {
        if (empty($this->lowImportancePartIds)) {
            $this->stepError = 'حداقل یک پارت کم‌اهمیت برای جابجایی انتخاب کن.';
            return;
        }
        $this->stepError = null;
        $this->applyChanges(true);
    }

    protected function applyChanges(bool $redistribute): void
    {
        $program = $this->activeProgram();
        $hasChapter = false;
        foreach ($this->selections as $sel) {
            if (!empty($sel['chapters'])) { $hasChapter = true; break; }
        }

        if (!$program || $this->eventDayIndex === null || !$hasChapter) {
            $this->stepError = 'اطلاعات ناقص است.';
            return;
        }

        $targetIndex = $this->targetDayIndex();
        $start = Carbon::parse($program->start_date)->startOfDay();
        $personalInfo = ($this->student ?? Auth::user()?->student)?->user?->personalInformation;

        $this->safeRun(function () use ($program, $targetIndex, $start, $personalInfo, $redistribute) {
            DB::transaction(function () use ($program, $targetIndex, $start, $personalInfo, $redistribute) {

                // ─── جابجاییِ پارت‌های کم‌اهمیت به روزهای دیگر («خیر، سنگین است») ───
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

                // ─── افزودن پارت‌های جدید برای همهٔ دروس/فصل‌های انتخاب‌شده ───
                $order = (int) ProgramPart::where('weekly_program_id', $program->id)
                    ->where('day_of_week', $targetIndex)->max('part_order');

                foreach ($this->selections as $sel) {
                    $subject = CcSubject::find($sel['subject_id']);
                    foreach (($sel['chapters'] ?? []) as $ch) {
                        $partMinutes = ((int) ($ch['hours'] ?? 0)) * 60 + ((int) ($ch['minutes'] ?? 0));
                        $partCount = max(1, (int) ($ch['part_count'] ?? 1));

                        for ($n = 1; $n <= $partCount; $n++) {
                            $order++;
                            ProgramPart::create([
                                'weekly_program_id' => $program->id,
                                'lesson_name' => $subject?->name ?? ($sel['subject_name'] ?? 'درس'),
                                'part_date' => $start->copy()->addDays($targetIndex),
                                'day_of_week' => $targetIndex,
                                'part_order' => $order,
                                'duration_minutes' => $partMinutes,
                                'part_type' => 'descriptive',
                                'source_type' => $this->category,
                                'is_student_added' => true,
                                'lesson_type' => $subject?->type === 'general' ? 'general' : 'specialized',
                                'grade' => $personalInfo?->grade,
                                'cc_grade_id' => $subject?->cc_grade_id,
                                'cc_field_id' => $subject?->cc_field_id,
                                'cc_subject_id' => $sel['subject_id'],
                                'cc_chapter_id' => $ch['chapter_id'],
                            ]);
                        }
                    }
                }
            });

            $this->dispatch('success', 'اتفاق یهویی با موفقیت در برنامه‌ات اعمال شد.');
            $this->close();
            $this->dispatch('sudden-event-applied');
        });
    }

    /**
     * روزهای «قابل‌استفاده» برای پخش پارت‌های کم‌اهمیت، به‌جز روزِ هدف.
     *
     * باگ قبلی: این متد فقط روزهای *بعد* از روزِ هدف را برمی‌گرداند. وقتی روزِ هدف
     * آخرین روزِ بازهٔ برنامه بود (مثلاً اتفاق برای آخرین روز هفته ثبت می‌شد)، هیچ
     * روزِ بعدی‌ای وجود نداشت، در نتیجه applyChanges() هیچ پارتی را جابجا نمی‌کرد —
     * دقیقاً همان چیزی که کاربر گزارش داده بود («بعضی اوقات جابجا نمی‌شود»).
     * برای رفع این مشکل، روزهای *قبل* از روزِ هدف (به شرطی که در گذشته نباشند) هم
     * به‌عنوان مقصد در نظر گرفته می‌شوند، تا همیشه—تا وقتی حداقل یک روزِ دیگر در
     * بازهٔ برنامه باقی مانده باشد—جابجایی واقعاً انجام شود.
     */
    protected function remainingDayIndices(): array
    {
        $program = $this->activeProgram();
        $start = $this->programStart();
        $targetIndex = $this->targetDayIndex();
        if (!$program || !$start || $targetIndex === null) return [];

        $max = $this->maxOffset();
        $today = Carbon::today();

        $candidates = [];
        for ($j = 0; $j <= $max; $j++) {
            if ($j === $targetIndex) continue;
            $date = $start->copy()->addDays($j);
            if ($date->lt($today)) continue; // روزهای گذشته قابل‌استفاده نیستند
            $candidates[] = $j;
        }

        if (empty($candidates)) return [];

        // ترجیح با روزهایی که از قبل پارت دارند (پخشِ متوازن‌تر)؛ در غیر این صورت همهٔ روزهای باقی‌مانده.
        $withParts = array_values(array_filter($candidates, function ($j) use ($program) {
            return $program->parts()->where('day_of_week', $j)->exists();
        }));

        return !empty($withParts) ? $withParts : $candidates;
    }

    public function render()
    {
        return view('livewire.client.profile.sudden-event-modal', [
            'availableDays' => $this->getAvailableDaysProperty(),
            'targetDayParts' => $this->getTargetDayPartsProperty(),
            'targetLoad' => $this->getTargetLoadProperty(),
            'targetDayLabel' => $this->getTargetDayLabelProperty(),
            'accessState' => $this->getAccessStateProperty(),
            'newPartsCount' => $this->getNewPartsCountProperty(),
        ]);
    }
}
