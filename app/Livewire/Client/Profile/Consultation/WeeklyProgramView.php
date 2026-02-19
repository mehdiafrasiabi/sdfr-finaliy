<?php


namespace App\Livewire\Client\Profile\Consultation;


use App\Models\WeeklyProgram;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\WeeklyProgramRestDay;

class WeeklyProgramView extends Component

{
    use SEOTools;

    public $programId;


    public function mount(WeeklyProgram $program)

    {

        $this->programId = $program->id;
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('برنامه درسی');
    }

    public function render()

    {

        $program = WeeklyProgram::with(['parts.lesson', 'parts.ccSubject', 'parts.ccChapter', 'parts.ccTopic', 'student.user.personalInformation', 'advisor'])
            ->find($this->programId);


        if (!$program) {

            return redirect()->route('client.profile.consultation.sessions')
                ->with('error', 'برنامه یافت نشد.');

        }


        // محاسبه روزهای هفته با نام روز صحیح فارسی (8 روز)

        $weekDays = [];
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        // بارگذاری روزهای استراحت
        $restDays = $program->restDays()->pluck('day_index')->toArray();
        for ($i = 0; $i < 8; $i++) {
            $date = Carbon::parse($program->start_date)->addDays($i);
            $dayParts = $program->parts()->where('day_of_week', $i)->orderBy('part_order')->get();
            // محاسبه روز هفته واقعی از تاریخ
            $jalaliDate = jdate($date);
            $dayOfWeek = $jalaliDate->getDayOfWeek(); // 0 = شنبه، 6 = جمعه
            $dayName = $jalaliDayNames[$dayOfWeek];
            // Check if this day is a rest day
            $isRestDay = in_array($i, $restDays);
            $weekDays[] = [
                'index' => $i,
                'name' => $dayName,
                'date' => $date,
                'jalali_date' => $jalaliDate->format('Y/m/d'),
                'jalali_short' => $jalaliDate->format('d F'),
                'parts' => $dayParts,
                'total_hours' => round($dayParts->sum('duration_minutes') / 60, 1),
                'total_tests' => $dayParts->sum('test_count') ?? 0,
                'is_rest_day' => $isRestDay,
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
        // آمار نوع منبع برای نمودار
        $sourceTypeColors = [
            'normal' => '#3B82F6',
            'class_qa' => '#06B6D4',
            'exam' => '#F59E0B',
            'homework' => '#EF4444',
            'daily_reading' => '#10B981',
            'pre_reading' => '#14B8A6',
            'classification' => '#64748B',
            'comprehensive_exam' => '#6B7280',
        ];
        $sourceTypeLabels = [
            'normal' => 'عادی',
            'class_qa' => 'پرسش و پاسخ کلاسی',
            'exam' => 'امتحانات',
            'homework' => 'تکالیف',
            'daily_reading' => 'روزخوانی',
            'pre_reading' => 'پیش‌خوانی',
            'classification' => 'طبقه‌بندی',
            'comprehensive_exam' => 'آزمون جامع',
        ];
        $allParts = $program->parts;
        $sourceTypeCounts = $allParts->groupBy('source_type')->map->count();
        $totalSourceParts = $allParts->count();
        $sourceTypeStats = [];
        foreach ($sourceTypeCounts as $type => $count) {
            $sourceTypeStats[] = [
                'type' => $type,
                'count' => $count,
                'percent' => $totalSourceParts > 0 ? round(($count / $totalSourceParts) * 100, 1) : 0,
                'color' => $sourceTypeColors[$type] ?? '#3B82F6',
                'label' => $sourceTypeLabels[$type] ?? 'عادی',
            ];
        }
        // نام مشاور و پشتیبان از دانش‌آموز
        $student = $program->student;
        $advisorName = $student?->advisor?->name ?? '-';

        $supporterName = $student?->supporter?->name ?? '-';
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
