<?php

namespace App\Livewire\Admin\Student\StudySession;

use App\Models\StudySession;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\User;

class Show extends Component
{
    use SEOTools;
    public $studentId;
    public $studentName;
    public $studyTime = [];
    public $studySessions = [];

    public function mount(User $student)
    {
        // چک کردن اینکه کاربر حتما student داشته باشد
        if (!$student->student) {
            abort(404, 'Student not found'); // یا میتونی پیام خطا نمایش بدی
        }

        $this->studentId = $student->student->id;
        $this->studentName = $student->personalInformation->name ?? $student->name;

        $this->calculateStudyTime();

        $this->studySessions = $student->student->studySessions()
            ->orderBy('started_at','desc')
            ->get();

        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('ساعت مطالعه '.$this->studentName);
    }
    protected function calculateStudyTime()
    {
        $now = Carbon::now();

        $totalSeconds = StudySession::where('student_id', $this->studentId)->sum('duration_seconds');
        $todaySeconds = StudySession::where('student_id', $this->studentId)
            ->whereDate('started_at', $now->toDateString())
            ->sum('duration_seconds');
        $weekSeconds = StudySession::where('student_id', $this->studentId)
            ->whereBetween('started_at', [$now->startOfWeek(), $now->endOfWeek()])
            ->sum('duration_seconds');
        $monthSeconds = StudySession::where('student_id', $this->studentId)
            ->whereBetween('started_at', [$now->startOfMonth(), $now->endOfMonth()])
            ->sum('duration_seconds');

        $formatTime = fn($seconds) => sprintf(
            '%02d:%02d:%02d',
            floor($seconds / 3600),
            floor(($seconds % 3600) / 60),
            $seconds % 60
        );

        $this->studyTime = [
            'total' => $formatTime($totalSeconds),
            'today' => $formatTime($todaySeconds),
            'week' => $formatTime($weekSeconds),
            'month' => $formatTime($monthSeconds),
        ];
    }

    public function render()
    {
        return view('livewire.admin.student.study-session.show')->layout('layouts.admin.app');
    }
}
