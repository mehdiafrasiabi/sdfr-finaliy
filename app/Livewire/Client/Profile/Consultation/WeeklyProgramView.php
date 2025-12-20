<?php


namespace App\Livewire\Client\Profile\Consultation;


use App\Models\WeeklyProgram;

use App\Models\Student;

use Livewire\Component;

use Carbon\Carbon;


class WeeklyProgramView extends Component

{

    public $programId;


    public function mount(WeeklyProgram $program)

    {

        $this->programId = $program->id;

    }


    public function render()

    {

        $program = WeeklyProgram::with(['parts.lesson', 'student.user.personalInformation', 'advisor'])
            ->find($this->programId);


        if (!$program) {

            return redirect()->route('client.profile.consultation.sessions')
                ->with('error', 'برنامه یافت نشد.');

        }


        // محاسبه روزهای هفته با نام روز صحیح فارسی


        $weekDays = [];


        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];


        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::parse($program->start_date)->addDays($i);
            $dayParts = $program->parts()->where('day_of_week', $i)->orderBy('part_order')->get();
            // محاسبه روز هفته واقعی از تاریخ
            $jalaliDate = jdate($date);
            $dayOfWeek = $jalaliDate->getDayOfWeek(); // 0 = شنبه، 6 = جمعه
            $dayName = $jalaliDayNames[$dayOfWeek];
            $weekDays[] = [
                'index' => $i,
                'name' => $dayName,
                'date' => $date,
                'jalali_date' => $jalaliDate->format('Y/m/d'),

                'parts' => $dayParts,

                'total_hours' => round($dayParts->sum('duration_minutes') / 60, 1),

                'total_tests' => $dayParts->sum('test_count') ?? 0,

            ];

        }


        // آمار برنامه

        $stats = [

            'totalHours' => $program->total_hours,

            'totalTests' => $program->total_tests,

            'totalParts' => $program->total_parts,

            'totalPlans' => $program->total_plans,

            'testParts' => $program->test_parts_count,

            'descriptiveParts' => $program->descriptive_parts_count,

            'videoParts' => $program->video_parts_count,

            'generalParts' => $program->general_parts_count,

            'specializedParts' => $program->specialized_parts_count,

            'grade10Parts' => $program->grade_10_parts_count,

            'grade11Parts' => $program->grade_11_parts_count,

            'grade12Parts' => $program->grade_12_parts_count,

        ];

        // آمار نمودارها
        $chartStats = [
            'partType' => $program->getPartTypeStats(),
            'lessonType' => $program->getLessonTypeStats(),
            'grade' => $program->getGradeStats(),
        ];

        // نام مشاور و پشتیبان از دانش‌آموز
        $student = $program->student;
        $advisorName = $student?->advisor?->name ?? $program->advisor_name ?? '-';
        $supporterName = $student?->supporter?->name ?? $program->supporter_name ?? '-';
        return view('livewire.client.profile.consultation.weekly-program-view', [
            'program' => $program,
            'weekDays' => $weekDays,
            'stats' => $stats,
            'chartStats' => $chartStats,
            'advisorName' => $advisorName,
            'supporterName' => $supporterName,
        ])->layout('layouts.client.app');

    }

}
