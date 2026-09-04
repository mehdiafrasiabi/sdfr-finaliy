<?php

namespace App\Livewire\Client\Profile;

use App\Models\AdvisingSession;
use App\Models\DailyReport;
use App\Models\DailyReportPart;
use App\Models\MakeupSession;
use App\Models\ProgramPart;
use App\Models\SessionFeedback;
use App\Models\SmartReportCard;
use App\Models\StudyPartSession;
use App\Models\WeeklyProgram;
use App\Services\ExamPlanningService;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SmartReportCardShow extends Component
{
    use SEOTools;

    public SmartReportCard $card;
    public ?string $studentGrade = null;

    // Detail modal
    public bool $detailModalOpen = false;
    public ?array $detailSubject = null;

    public function mount(SmartReportCard $smartReportCard): void
    {
        $user = Auth::user();
        $studentId = $user->student->id ?? null;
        $examPlanning = app(ExamPlanningService::class);

        if ($examPlanning->shouldHideTrialExamProgramReportCard($user)) {
            abort(404);
        }

        if (!$studentId || (int) $smartReportCard->student_id !== (int) $studentId || !$smartReportCard->is_active) {
            abort(404);
        }

        $this->card = $smartReportCard;
        $this->studentGrade = (string) ($user->personalInformation->grade ?? '12');

        $this->seo()->setTitle('کارنامه هوشمند ' . $this->card->month_name . ' ' . $this->card->jalali_year);
    }

//    public function openSubjectDetail(int $subjectId): void
//    {
//        $studentId = $this->card->student_id;
//        $start = $this->card->start_date->copy()->startOfDay();
//        $end = $this->card->end_date->copy()->endOfDay();
//
//        $subjectsByGrade = $this->buildSubjectProgress($studentId, $start, $end);
//
//        $found = null;
//        foreach ($subjectsByGrade as $group) {
//            foreach ($group as $subject) {
//                if ((int) $subject['subject_id'] === $subjectId) {
//                    $found = $subject;
//                    break 2;
//                }
//            }
//        }
//
//        if (!$found) {
//            return;
//        }
//
//        $this->detailSubject = $found;
//        $this->detailModalOpen = true;
//    }
    public function requestSubjectDetail(int $subjectId): void
    {
        $this->dispatch('open-subject-detail',
            subjectId: $subjectId,
            startDate: $this->card->start_date->toDateString(),
            endDate:   $this->card->end_date->toDateString()
        );
        $this->skipRender();          // ★ از رندر مجدّد خبری نیست
    }

    public function closeSubjectDetail(): void
    {
        $this->detailModalOpen = false;
        $this->detailSubject = null;
        $this->dispatch('src-modal-closed');
    }

    public function render()
    {
        $studentId = $this->card->student_id;

        $currentStart = $this->card->start_date->copy()->startOfDay();
        $currentEnd = $this->card->end_date->copy()->endOfDay();

        $prevMonth = $this->card->jalali_month - 1;
        $prevYear = $this->card->jalali_year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear -= 1;
        }
        $prevRange = SmartReportCard::jalaliMonthRange($prevYear, $prevMonth);

        $current = $this->buildPeriodStats($studentId, $currentStart, $currentEnd);
        $previous = $this->buildPeriodStats($studentId, $prevRange['start'], $prevRange['end']);

        $currentSessions = $this->buildSessionBreakdown($studentId, $currentStart, $currentEnd);
        $previousSessions = $this->buildSessionBreakdown($studentId, $prevRange['start'], $prevRange['end']);

        $subjectsByGrade = $this->buildSubjectProgress($studentId, $currentStart, $currentEnd);

        $analysis = $this->buildAnalysis($current);

        $availableGrades = $this->getAvailableGrades();

        return view('livewire.client.profile.smart-report-card-show', [
            'current' => $current,
            'previous' => $previous,
            'currentSessions' => $currentSessions,
            'previousSessions' => $previousSessions,
            'subjectsByGrade' => $subjectsByGrade,
            'availableGrades' => $availableGrades,
            'analysis' => $analysis,
            'currentLabel' => $this->card->month_name . ' ' . $this->card->jalali_year,
            'previousLabel' => SmartReportCard::MONTH_NAMES[$prevMonth] . ' ' . $prevYear,
        ])->layout('layouts.client.app');
    }

    // ====================================================================
    // ====================== Aggregation helpers =========================
    // ====================================================================

    private function buildPeriodStats(int $studentId, Carbon $start, Carbon $end): array
    {
        $startDate = $start->toDateString();
        $endDate = $end->toDateString();

        // ---- Held advising sessions ----
        $heldSessions = AdvisingSession::query()
            ->where('student_id', $studentId)
            ->whereBetween('activation_date', [$startDate, $endDate])
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->get();

        // ---- Daily reports (regular + compensatory) ----
        $allReports = DailyReport::query()
            ->where('student_id', $studentId)
            ->whereBetween('report_date', [$startDate, $endDate])
            ->get(['id', 'report_date', 'is_compensatory']);
        $regularReports = $allReports->where('is_compensatory', false);
        $compensatoryReports = $allReports->where('is_compensatory', true);
        $reportsSent = $regularReports->count();

        $reportsMissing = $this->countMissingReports($studentId, $start, $end, $regularReports->pluck('report_date'));

        // ---- Program parts in range ----
        $programParts = ProgramPart::query()
            ->whereBetween('part_date', [$startDate, $endDate])
            ->whereHas('weeklyProgram', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->with(['ccSubject', 'ccChapter'])
            ->get();

        // ---- Study sessions for parts in range ----
        $partIdsInRange = $programParts->pluck('id')->all();
        $studyRows = collect();
        if (!empty($partIdsInRange)) {
            $studyRows = StudyPartSession::query()
                ->where('student_id', $studentId)
                ->whereIn('program_part_id', $partIdsInRange)
                ->whereBetween('started_at', [$start, $end])
                ->with('feedback')
                ->get(['id', 'program_part_id', 'started_at', 'ended_at', 'duration_seconds', 'is_completed']);
        }

        // Aggregate per-part seconds + feedback ratings
        $partSeconds = [];
        $partRatings = [];
        $studySecondsTotal = 0;
        foreach ($studyRows as $row) {
            $sec = (int) ($row->duration_seconds ?? 0);
            if ($sec <= 0 && $row->started_at && $row->ended_at) {
                $sec = $row->started_at->diffInSeconds($row->ended_at);
            }
            if ($sec > 0) {
                $partSeconds[$row->program_part_id] = ($partSeconds[$row->program_part_id] ?? 0) + $sec;
                $studySecondsTotal += $sec;
            }
            if ($row->feedback && $row->feedback->rating !== null) {
                $partRatings[$row->program_part_id][] = (int) $row->feedback->rating;
            }
        }

        $studiedPartIds = array_keys(array_filter($partSeconds, fn($s) => $s > 0));
        $partsStudied = count(array_intersect($partIdsInRange, $studiedPartIds));

        // ---- Tests done ----
        $reportIds = $regularReports->pluck('id')->all();
        $testsDone = !empty($reportIds)
            ? (int) DailyReportPart::whereIn('daily_report_id', $reportIds)->sum('tests_done')
            : 0;

        // ---- Distributions ----
        $partTypeDist = $this->buildPartTypeDistribution($programParts);
        $restDaysInRange = $this->countRestDaysInRange($studentId, $start, $end);
        if ($restDaysInRange > 0) {
            $partTypeDist['استراحت'] = ($partTypeDist['استراحت'] ?? 0) + $restDaysInRange;
        }

        $lessonTypeDist = [];
        foreach ($programParts as $p) {
            $label = match ($p->lesson_type) {
                ProgramPart::LESSON_TYPE_GENERAL => 'عمومی',
                ProgramPart::LESSON_TYPE_SPECIALIZED => 'تخصصی',
                default => 'نامشخص',
            };
            $lessonTypeDist[$label] = ($lessonTypeDist[$label] ?? 0) + 1;
        }

        $gradeDist = [];
        foreach ($programParts as $p) {
            $label = match ((string) $p->grade) {
                '10' => 'دهم',
                '11' => 'یازدهم',
                '12' => 'دوازدهم',
                default => 'نامشخص',
            };
            $gradeDist[$label] = ($gradeDist[$label] ?? 0) + 1;
        }

        // ---- Quality distribution (per studied part with feedback) ----
        $qualityDist = ['عالی' => 0, 'با کیفیت' => 0, 'بی‌کیفیت' => 0];
        foreach ($partRatings as $ratings) {
            if (empty($ratings)) continue;
            $avg = array_sum($ratings) / count($ratings);
            if ($avg >= 8)      $qualityDist['عالی']++;
            elseif ($avg >= 5)  $qualityDist['با کیفیت']++;
            else                $qualityDist['بی‌کیفیت']++;
        }

        // ---- Escaped (per month + per session) ----
        $escapedMonthly = $this->buildEscapedMonthly($programParts, $partSeconds);
        $escapedPerSession = $this->buildEscapedPerSession($studentId, $heldSessions);

        // ---- Cheat parts ----
        $cheatParts = $this->buildCheatParts($studentId, $reportIds, $partSeconds);

        // ---- Extra (makeup) study time ----
        $extraStudySeconds = (int) MakeupSession::query()
            ->where('student_id', $studentId)
            ->where('status', '!=', 'rejected')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('started_at', [$start, $end])
                    ->orWhere(function ($qq) use ($start, $end) {
                        $qq->whereNull('started_at')
                            ->whereBetween('created_at', [$start, $end]);
                    });
            })
            ->sum('duration_seconds');

        return [
            'total_sessions' => $heldSessions->count(),
            'reports_sent' => $reportsSent,
            'reports_missing' => $reportsMissing,
            'compensatory_reports' => $compensatoryReports->count(),
            'study_seconds' => $studySecondsTotal,
            'tests_done' => $testsDone,
            'parts_total' => $programParts->count(),
            'parts_studied' => $partsStudied,
            'part_type_distribution' => $partTypeDist,
            'lesson_type_distribution' => $lessonTypeDist,
            'grade_distribution' => $gradeDist,
            'quality_distribution' => $qualityDist,
            'escaped_monthly' => $escapedMonthly,
            'escaped_per_session' => $escapedPerSession,
            'cheat_parts' => $cheatParts,
            'extra_study_seconds' => $extraStudySeconds,
        ];
    }

    private function countMissingReports(int $studentId, Carbon $start, Carbon $end, $sentDates): int
    {
        $sentSet = collect($sentDates)
            ->map(fn($d) => $d instanceof Carbon ? $d->toDateString() : (string) $d)
            ->unique()
            ->all();

        $today = Carbon::today();
        $effectiveEnd = $end->copy()->startOfDay()->min($today);

        $programs = WeeklyProgram::where('student_id', $studentId)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_date', '<=', $end->toDateString())
                    ->where('end_date', '>=', $start->toDateString());
            })
            ->with('restDays:id,weekly_program_id,day_index')
            ->get();

        if ($programs->isEmpty()) {
            return 0;
        }

        $expected = 0;
        $cursor = $start->copy()->startOfDay();
        while ($cursor->lte($effectiveEnd)) {
            foreach ($programs as $program) {
                $progStart = Carbon::parse($program->start_date)->startOfDay();
                $progEnd = Carbon::parse($program->end_date)->startOfDay();
                if ($cursor->lt($progStart) || $cursor->gt($progEnd)) {
                    continue;
                }
                $dayIndex = $progStart->diffInDays($cursor);
                $isRest = $program->restDays->contains(fn($r) => (int) $r->day_index === (int) $dayIndex);
                if (!$isRest) {
                    $expected++;
                }
                break;
            }
            $cursor->addDay();
        }

        return max(0, $expected - count($sentSet));
    }

    private function buildPartTypeDistribution($programParts): array
    {
        $dist = [];
        foreach ($programParts as $p) {
            $source = $p->source_type ?: 'normal';
            $isSpecialSource = $source !== 'normal' && $source !== ProgramPart::SOURCE_NORMAL;

            if ($isSpecialSource) {
                $label = match ($source) {
                    ProgramPart::SOURCE_CLASS_QA, 'class_qa' => 'پرسش و پاسخ کلاسی',
                    ProgramPart::SOURCE_EXAM, 'exam' => 'امتحانات',
                    ProgramPart::SOURCE_HOMEWORK, 'homework' => 'تکالیف',
                    ProgramPart::SOURCE_DAILY_READING, 'daily_reading' => 'روزخوانی',
                    ProgramPart::SOURCE_PRE_READING, 'pre_reading' => 'پیش‌خوانی',
                    ProgramPart::SOURCE_CLASSIFICATION, 'classification' => 'طبقه‌بندی',
                    ProgramPart::SOURCE_COMPREHENSIVE_EXAM, 'comprehensive_exam' => 'آزمون جامع',
                    default => $this->partTypeLabel($p->part_type),
                };
            } else {
                $label = $this->partTypeLabel($p->part_type);
            }
            $dist[$label] = ($dist[$label] ?? 0) + 1;
        }
        return $dist;
    }

    private function partTypeLabel(?string $partType): string
    {
        return match ($partType) {
            ProgramPart::PART_TYPE_TEST, 'test' => 'تستی',
            ProgramPart::PART_TYPE_DESCRIPTIVE, 'descriptive' => 'تشریحی',
            ProgramPart::PART_TYPE_VIDEO, 'video' => 'ویدئو',
            ProgramPart::PART_TYPE_TOPIC_EXAM, 'topic_exam' => 'آزمون مبحثی',
            ProgramPart::PART_TYPE_COMPREHENSIVE_EXAM, 'comprehensive_exam' => 'آزمون جامع',
            ProgramPart::PART_TYPE_EXAM_ANALYSIS, 'exam_analysis' => 'تحلیل آزمون',
            default => 'نامشخص',
        };
    }

    private function countRestDaysInRange(int $studentId, Carbon $start, Carbon $end): int
    {
        $programs = WeeklyProgram::where('student_id', $studentId)
            ->where('start_date', '<=', $end->toDateString())
            ->where('end_date', '>=', $start->toDateString())
            ->with('restDays')
            ->get();

        $count = 0;
        foreach ($programs as $program) {
            $progStart = Carbon::parse($program->start_date)->startOfDay();
            foreach ($program->restDays as $rest) {
                $restDate = $progStart->copy()->addDays((int) $rest->day_index);
                if ($restDate->gte($start->copy()->startOfDay()) && $restDate->lte($end->copy()->startOfDay())) {
                    $count++;
                }
            }
        }
        return $count;
    }

    /**
     * Subjects with parts in the month where NOT a single part was studied.
     */
    private function buildEscapedMonthly($programParts, array $partSeconds): array
    {
        $bySubject = [];
        foreach ($programParts as $p) {
            $subjectId = $p->cc_subject_id;
            if (!$subjectId) continue;
            if (!isset($bySubject[$subjectId])) {
                $bySubject[$subjectId] = [
                    'subject_id' => $subjectId,
                    'subject_name' => $p->ccSubject->name ?? '-',
                    'total_parts' => 0,
                    'studied_parts' => 0,
                ];
            }
            $bySubject[$subjectId]['total_parts']++;
            if (($partSeconds[$p->id] ?? 0) > 0) {
                $bySubject[$subjectId]['studied_parts']++;
            }
        }

        $result = [];
        foreach ($bySubject as $row) {
            if ($row['total_parts'] > 0 && $row['studied_parts'] === 0) {
                $result[] = [
                    'subject_name' => $row['subject_name'],
                    'total_parts' => $row['total_parts'],
                ];
            }
        }
        return $result;
    }

    /**
     * Per-session escapes: for each held session in range, list subjects with planned parts but zero studied parts.
     */
    private function buildEscapedPerSession(int $studentId, $heldSessions): array
    {
        if ($heldSessions->isEmpty()) return [];

        $result = [];
        $idx = 1;
        foreach ($heldSessions as $session) {
            $weeklyProgram = WeeklyProgram::where('advising_session_id', $session->id)
                ->where('student_id', $studentId)
                ->with(['parts.ccSubject'])
                ->first();
            if (!$weeklyProgram || $weeklyProgram->parts->isEmpty()) {
                $idx++;
                continue;
            }

            $subjectsEscaped = [];
            $bySubject = $weeklyProgram->parts->groupBy('cc_subject_id');
            foreach ($bySubject as $subjectId => $parts) {
                if (!$subjectId) continue;
                $partIds = $parts->pluck('id')->all();
                $hasStudy = StudyPartSession::where('student_id', $studentId)
                    ->whereIn('program_part_id', $partIds)
                    ->where(function ($q) {
                        $q->where('duration_seconds', '>', 0)
                            ->orWhere('is_completed', true);
                    })
                    ->exists();
                if (!$hasStudy) {
                    $name = $parts->first()->ccSubject->name ?? null;
                    if ($name) {
                        $subjectsEscaped[] = ['name' => $name, 'parts_count' => count($partIds)];
                    }
                }
            }

            if (!empty($subjectsEscaped)) {
                $result[] = [
                    'session_label' => 'جلسه ' . $idx,
                    'session_date' => jdate($session->activation_date)->format('Y/m/d'),
                    'subjects' => $subjectsEscaped,
                ];
            }
            $idx++;
        }
        return $result;
    }

    /**
     * Cheat parts: daily_report_parts where is_read=true AND no study_part_session
     * with positive duration exists for that program_part within the date range.
     */
    private function buildCheatParts(int $studentId, array $reportIds, array $partSeconds): array
    {
        if (empty($reportIds)) return [];

        $readParts = DailyReportPart::query()
            ->whereIn('daily_report_id', $reportIds)
            ->where('is_read', true)
            ->with(['programPart.ccSubject', 'programPart.ccChapter', 'dailyReport:id,report_date'])
            ->get();

        $result = [];
        foreach ($readParts as $rp) {
            $programPart = $rp->programPart;
            if (!$programPart) continue;
            $studied = ($partSeconds[$programPart->id] ?? 0) > 0;
            if (!$studied) {
                $result[] = [
                    'lesson_name' => $programPart->lesson_name ?? '-',
                    'subject_name' => $programPart->ccSubject->name ?? '-',
                    'chapter_name' => $programPart->ccChapter->name ?? null,
                    'report_date' => $rp->dailyReport
                        ? jdate($rp->dailyReport->report_date)->format('Y/m/d')
                        : '-',
                    'planned_minutes' => (int) ($programPart->duration_minutes ?? 0),
                ];
            }
        }
        return $result;
    }

    private function buildSubjectProgress(int $studentId, Carbon $start, Carbon $end): array
    {
        $startDate = $start->toDateString();
        $endDate = $end->toDateString();

        $programParts = ProgramPart::query()
            ->whereBetween('part_date', [$startDate, $endDate])
            ->whereHas('weeklyProgram', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->with(['ccSubject', 'ccChapter'])
            ->get();

        if ($programParts->isEmpty()) {
            return ['10' => [], '11' => [], '12' => []];
        }

        $partIds = $programParts->pluck('id')->all();
        $studyRows = StudyPartSession::query()
            ->where('student_id', $studentId)
            ->whereIn('program_part_id', $partIds)
            ->whereBetween('started_at', [$start, $end])
            ->with('feedback')
            ->get();

        $partSeconds = [];
        $partRatings = [];
        foreach ($studyRows as $row) {
            $sec = (int) ($row->duration_seconds ?? 0);
            if ($sec <= 0 && $row->started_at && $row->ended_at) {
                $sec = $row->started_at->diffInSeconds($row->ended_at);
            }
            if ($sec > 0) {
                $partSeconds[$row->program_part_id] = ($partSeconds[$row->program_part_id] ?? 0) + $sec;
            }
            if ($row->feedback && $row->feedback->rating !== null) {
                $partRatings[$row->program_part_id][] = (int) $row->feedback->rating;
            }
        }

        // Group by grade -> subject -> chapter
        $bySubject = [];
        foreach ($programParts as $p) {
            if (!$p->cc_subject_id) continue;
            $grade = (string) ($p->grade ?: 'unknown');
            $subjectId = $p->cc_subject_id;
            $chapterId = $p->cc_chapter_id ?: 0;

            if (!isset($bySubject[$grade][$subjectId])) {
                $bySubject[$grade][$subjectId] = [
                    'subject_id' => $subjectId,
                    'subject_name' => $p->ccSubject->name ?? '-',
                    'grade' => $grade,
                    'planned_minutes' => 0,
                    'studied_seconds' => 0,
                    'parts_total' => 0,
                    'parts_studied' => 0,
                    'quality' => ['عالی' => 0, 'با کیفیت' => 0, 'بی‌کیفیت' => 0],
                    'chapters' => [],
                ];
            }

            $subj = &$bySubject[$grade][$subjectId];
            $subj['parts_total']++;
            $subj['planned_minutes'] += (int) ($p->duration_minutes ?? 0);
            $partSec = $partSeconds[$p->id] ?? 0;
            $subj['studied_seconds'] += $partSec;
            if ($partSec > 0) {
                $subj['parts_studied']++;
            }

            // Quality
            $ratings = $partRatings[$p->id] ?? [];
            if (!empty($ratings)) {
                $avg = array_sum($ratings) / count($ratings);
                if ($avg >= 8)      $subj['quality']['عالی']++;
                elseif ($avg >= 5)  $subj['quality']['با کیفیت']++;
                else                $subj['quality']['بی‌کیفیت']++;
            }

            // Chapter
            if (!isset($subj['chapters'][$chapterId])) {
                $subj['chapters'][$chapterId] = [
                    'chapter_id' => $chapterId,
                    'chapter_name' => $p->ccChapter->name ?? 'بدون فصل',
                    'planned_minutes' => 0,
                    'studied_seconds' => 0,
                    'parts_total' => 0,
                    'parts_studied' => 0,
                ];
            }
            $ch = &$subj['chapters'][$chapterId];
            $ch['planned_minutes'] += (int) ($p->duration_minutes ?? 0);
            $ch['studied_seconds'] += $partSec;
            $ch['parts_total']++;
            if ($partSec > 0) $ch['parts_studied']++;
            unset($ch);
            unset($subj);
        }

        // Format result
        $output = ['10' => [], '11' => [], '12' => []];
        foreach ($bySubject as $grade => $subjects) {
            if (!in_array($grade, ['10', '11', '12'])) continue;
            $rows = [];
            foreach ($subjects as $subj) {
                $studiedMinutes = (int) floor($subj['studied_seconds'] / 60);
                $percent = $subj['planned_minutes'] > 0
                    ? round(min(999, ($studiedMinutes / $subj['planned_minutes']) * 100), 1)
                    : 0;

                $chapters = [];
                foreach ($subj['chapters'] as $ch) {
                    $chStudied = (int) floor($ch['studied_seconds'] / 60);
                    $chapters[] = [
                        'chapter_name' => $ch['chapter_name'],
                        'planned_minutes' => $ch['planned_minutes'],
                        'studied_minutes' => $chStudied,
                        'studied_seconds' => $ch['studied_seconds'],
                        'parts_total' => $ch['parts_total'],
                        'parts_studied' => $ch['parts_studied'],
                        'percent' => $ch['planned_minutes'] > 0
                            ? round(min(999, ($chStudied / $ch['planned_minutes']) * 100), 1)
                            : 0,
                    ];
                }
                usort($chapters, fn($a, $b) => $b['percent'] <=> $a['percent']);

                $rows[] = [
                    'subject_id' => $subj['subject_id'],
                    'subject_name' => $subj['subject_name'],
                    'grade' => $subj['grade'],
                    'planned_minutes' => $subj['planned_minutes'],
                    'studied_minutes' => $studiedMinutes,
                    'studied_seconds' => $subj['studied_seconds'],
                    'parts_total' => $subj['parts_total'],
                    'parts_studied' => $subj['parts_studied'],
                    'percent' => $percent,
                    'quality' => $subj['quality'],
                    'chapters' => $chapters,
                ];
            }
            usort($rows, fn($a, $b) => $b['percent'] <=> $a['percent']);
            $output[$grade] = $rows;
        }
        return $output;
    }

    private function getAvailableGrades(): array
    {
        $sg = (int) $this->studentGrade;
        if ($sg <= 10) return ['10'];
        if ($sg === 11) return ['10', '11'];
        return ['10', '11', '12'];
    }

    private function buildAnalysis(array $current): array
    {
        // Score 0..100 from completion of plan + report consistency + quality
        $partsTotal = $current['parts_total'];
        $completionScore = $partsTotal > 0
            ? min(100, ($current['parts_studied'] / $partsTotal) * 100)
            : 0;

        $expectedReports = $current['reports_sent'] + $current['reports_missing'];
        $reportsScore = $expectedReports > 0
            ? min(100, ($current['reports_sent'] / $expectedReports) * 100)
            : 0;

        $qualityTotal = array_sum($current['quality_distribution']);
        $qualityScore = $qualityTotal > 0
            ? min(100, (
                ($current['quality_distribution']['عالی'] * 100
                    + $current['quality_distribution']['با کیفیت'] * 65
                    + $current['quality_distribution']['بی‌کیفیت'] * 25) / $qualityTotal
            ))
            : 0;

        $cheatPenalty = min(25, count($current['cheat_parts']) * 3);
        $score = round(($completionScore * 0.45) + ($reportsScore * 0.25) + ($qualityScore * 0.30) - $cheatPenalty);
        $score = max(0, min(100, $score));

        if ($score >= 85) {
            $tier = 'عالی';
            $color = 'emerald';
            $message = 'فوق‌العاده‌ای! این روند طلایی رو نگه دار. تمرکز، نظم گزارش‌دهی و کیفیت مطالعه‌ات حرف نداره. این پایداری در ادامه مسیر کنکور تفاوت‌ساز خواهد بود.';
        } elseif ($score >= 70) {
            $tier = 'خوب';
            $color = 'blue';
            $message = 'عملکرد قابل قبولی داشتی. فقط کمی روی نقاط ضعف کیفیت مطالعه و کاهش پارت‌های فراری کار کن تا به سطح عالی برسی.';
        } elseif ($score >= 50) {
            $tier = 'متوسط';
            $color = 'amber';
            $message = 'پتانسیلش رو داری ولی ناپیوستگی در مطالعه و گزارش‌دهی نتیجه رو پایین آورده. این ماه روی منظم کردن خودت تمرکز کن — حتی برنامه‌های کوچک ولی پیوسته.';
        } elseif ($score >= 30) {
            $tier = 'ضعیف';
            $color = 'orange';
            $message = 'وضعیتت هشدارآمیزه. بخش زیادی از برنامه رو انجام ندادی یا کیفیت پایینی داشتی. الان وقتشه با مشاورت جدی صحبت کنی و یک ری‌استارت اساسی بزنی.';
        } else {
            $tier = 'بحرانی';
            $color = 'red';
            $message = 'شرایط بحرانیه. اگر همین مسیر ادامه پیدا کنه، رسیدن به هدفت سخت میشه. حتما با مشاور تماس بگیر و دلایل اصلی رو شناسایی کن — این ماه باید نقطه عطف باشه.';
        }

        return [
            'score' => $score,
            'tier' => $tier,
            'color' => $color,
            'message' => $message,
            'breakdown' => [
                'completion' => round($completionScore),
                'reports' => round($reportsScore),
                'quality' => round($qualityScore),
                'cheat_penalty' => $cheatPenalty,
            ],
        ];
    }

    /**
     * Per-session breakdown: planned vs done vs extra (makeup) seconds (precise).
     */
    private function buildSessionBreakdown(int $studentId, Carbon $start, Carbon $end): array
    {
        $startDate = $start->toDateString();
        $endDate = $end->toDateString();

        $sessions = AdvisingSession::query()
            ->where('student_id', $studentId)
            ->whereBetween('activation_date', [$startDate, $endDate])
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->orderBy('activation_date')
            ->get();

        if ($sessions->isEmpty()) return [];

        $result = [];
        $index = 1;

        foreach ($sessions as $session) {
            $weeklyProgram = WeeklyProgram::query()
                ->where('advising_session_id', $session->id)
                ->where('student_id', $studentId)
                ->with('parts:id,weekly_program_id,duration_minutes')
                ->first();

            $plannedMinutes = 0;
            $doneSeconds = 0;
            $weekStart = null;
            $weekEnd = null;

            if ($weeklyProgram) {
                $plannedMinutes = (int) $weeklyProgram->parts->sum('duration_minutes');
                $partIds = $weeklyProgram->parts->pluck('id')->all();

                if (!empty($partIds)) {
                    $studyRows = StudyPartSession::query()
                        ->where('student_id', $studentId)
                        ->whereIn('program_part_id', $partIds)
                        ->get(['program_part_id', 'started_at', 'ended_at', 'duration_seconds']);

                    foreach ($studyRows as $row) {
                        $sec = (int) ($row->duration_seconds ?? 0);
                        if ($sec <= 0 && $row->started_at && $row->ended_at) {
                            $sec = $row->started_at->diffInSeconds($row->ended_at);
                        }
                        if ($sec > 0) $doneSeconds += $sec;
                    }
                }

                if ($weeklyProgram->start_date && $weeklyProgram->end_date) {
                    $weekStart = Carbon::parse($weeklyProgram->start_date)->startOfDay();
                    $weekEnd = Carbon::parse($weeklyProgram->end_date)->endOfDay();
                }
            }

            if (!$weekStart) {
                $weekStart = Carbon::parse($session->activation_date)->startOfDay();
                $weekEnd = $weekStart->copy()->addDays(7)->endOfDay();
            }

            $extraSeconds = (int) MakeupSession::query()
                ->where('student_id', $studentId)
                ->where('status', '!=', 'rejected')
                ->where(function ($q) use ($weekStart, $weekEnd) {
                    $q->whereBetween('started_at', [$weekStart, $weekEnd])
                        ->orWhere(function ($qq) use ($weekStart, $weekEnd) {
                            $qq->whereNull('started_at')
                                ->whereBetween('created_at', [$weekStart, $weekEnd]);
                        });
                })
                ->sum('duration_seconds');

            $result[] = [
                'label' => 'جلسه ' . $index . ' — ' . jdate($session->activation_date)->format('Y/m/d'),
                'short_label' => 'جلسه ' . $index,
                'planned_seconds' => $plannedMinutes * 60,
                'done_seconds' => $doneSeconds,
                'extra_seconds' => $extraSeconds,
                // For chart (precise hours with decimals)
                'planned_hours' => round(($plannedMinutes * 60) / 3600, 3),
                'done_hours' => round($doneSeconds / 3600, 3),
                'extra_hours' => round($extraSeconds / 3600, 3),
            ];
            $index++;
        }
        return $result;
    }
}
