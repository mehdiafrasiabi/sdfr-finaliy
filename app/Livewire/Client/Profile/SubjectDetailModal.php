<?php

namespace App\Livewire\Client\Profile;

use App\Models\ProgramPart;
use App\Models\StudyPartSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class SubjectDetailModal extends Component
{
    public bool $open = false;
    public ?array $subjectData = null;
    public ?string $subjectName = null;
    public ?int $subjectId = null;

    #[On('open-subject-detail')]
    public function openDetail(int $subjectId, string $startDate, string $endDate, ?int $studentId = null): void
    {
        $this->subjectId = $subjectId;
        $this->loadSubjectData($subjectId, $startDate, $endDate, $studentId);
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
        $this->subjectData = null;
        $this->dispatch('src-modal-closed');   // اگر نیاز به پاک‌سازی جانبی دارید
    }
// در فایل SubjectDetailModal.php

    private function formatMinutes($minutes): string
    {
        if ($minutes < 60) {
            return $minutes . ' دقیقه';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return $hours . ' ساعت';
        }

        return $hours . ' ساعت و ' . $remainingMinutes . ' دقیقه';
    }
    private function loadSubjectData(int $subjectId, string $startDate, string $endDate, ?int $studentId = null): void
    {
        $studentId = $studentId ?: Auth::user()?->student?->id;
        if (! $studentId) {
            $this->subjectData = [];
            $this->subjectName = '-';
            return;
        }

        $start = Carbon::parse($startDate)->startOfDay();
        $end   = Carbon::parse($endDate)->endOfDay();

        // دریافت پارت‌های برنامه‌ای برای این درس در بازه
        $programParts = ProgramPart::query()
            ->where('cc_subject_id', $subjectId)
            ->whereBetween('part_date', [$startDate, $endDate])
            ->whereHas('weeklyProgram', fn($q) => $q->where('student_id', $studentId))
            ->with(['ccSubject', 'ccChapter'])
            ->get();

        if ($programParts->isEmpty()) {
            $this->subjectData = [];
            $this->subjectName = '-';
            return;
        }

        $this->subjectName = $programParts->first()->ccSubject->name ?? '-';
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

        // ساختار بر اساس فصل
        $chapters = [];
        foreach ($programParts as $part) {
            $chapterId = $part->cc_chapter_id ?: 0;
            $name = $part->ccChapter->name ?? 'بدون فصل';
            if (!isset($chapters[$chapterId])) {
                $chapters[$chapterId] = [
                    'chapter_name' => $name,
                    'planned_minutes' => 0,
                    'studied_seconds' => 0,
                    'parts_total' => 0,
                    'parts_studied' => 0,
                    'quality' => ['عالی' => 0, 'با کیفیت' => 0, 'بی‌کیفیت' => 0],
                ];
            }
            $ch = &$chapters[$chapterId];
            $ch['planned_minutes'] += (int) ($part->duration_minutes ?? 0);
            $ch['parts_total']++;
            $sec = $partSeconds[$part->id] ?? 0;
            $ch['studied_seconds'] += $sec;
            if ($sec > 0) $ch['parts_studied']++;
            $ratings = $partRatings[$part->id] ?? [];
            if (!empty($ratings)) {
                $avg = array_sum($ratings) / count($ratings);
                if ($avg >= 8)       $ch['quality']['عالی']++;
                elseif ($avg >= 5)   $ch['quality']['با کیفیت']++;
                else                 $ch['quality']['بی‌کیفیت']++;
            }
            unset($ch);
        }

        $result = [];
        foreach ($chapters as $ch) {
            $studiedMin = (int) floor($ch['studied_seconds'] / 60);
            $percent = $ch['planned_minutes'] > 0
                ? round(min(999, ($studiedMin / $ch['planned_minutes']) * 100), 1)
                : 0;
            $result[] = [
                'chapter_name'   => $ch['chapter_name'],
                'planned_minutes'=> $ch['planned_minutes'],
                'studied_minutes'=> $studiedMin,
                'studied_seconds'=> $ch['studied_seconds'],
                'parts_total'    => $ch['parts_total'],
                'parts_studied'  => $ch['parts_studied'],
                'percent'        => $percent,
                'quality'        => $ch['quality'],
            ];
        }

        usort($result, fn($a, $b) => $b['percent'] <=> $a['percent']);
        $this->subjectData = $result;
    }

    public function render()
    {
        return view('livewire.client.profile.subject-detail-modal', [
            'subjectName' => $this->subjectName,
            'subjectData' => $this->subjectData,
        ]);
    }
}
