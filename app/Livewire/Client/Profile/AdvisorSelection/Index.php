<?php

namespace App\Livewire\Client\Profile\AdvisorSelection;

use App\Models\Admin;
use App\Models\AdvisorSelection;
use App\Models\GeneralSetting;
use App\Models\Student;
use App\Services\NotificationService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/**
 * انتخاب مشاور تحصیلی توسط دانش‌آموزِ خریدکرده.
 * دانش‌آموز روز هفته و ساعت را به‌عنوان فیلتر انتخاب می‌کند و فقط مشاورانی که در آن
 * روز/ساعت کاری دارند و ظرفیت دارند نمایش داده می‌شوند. سپس یا به‌صورت دستی مشاور را
 * برمی‌گزیند یا «انتخاب تصادفی» می‌زند تا سیستم کم‌بارترین مشاورِ آزاد را انتخاب کند.
 * انتخاب تا تاییدِ مدیر آموزشی «در انتظار/رزرو» می‌ماند.
 */
class Index extends Component
{
    use SEOTools;

    public const SLOT_START_HOUR = 8;   // اولین ساعتِ مجاز برای شروع جلسه
    public const SLOT_END_HOUR   = 20;  // آخرین ساعتِ مجاز برای شروع جلسه

    /** فیلترِ روزِ هفته (۰=شنبه .. ۶=جمعه) */
    public $filterDay = null;

    /** فیلترِ ساعت (۸..۲۰) */
    public $filterHour = null;

    // مودالِ جزئیاتِ مشاور
    public bool $showModal = false;
    public ?int $modalAdvisorId = null;

    public function mount(): void
    {
        $this->seo()->setTitle('انتخاب مشاور');
    }

    protected function student(): ?Student
    {
        return Auth::user()?->student;
    }

    /**
     * مقدارِ ظرفیتِ سراسریِ پیش‌فرض (یک‌بار محاسبه می‌شود).
     */
    protected function defaultCapacity(): int
    {
        return (int) (GeneralSetting::query()->value('advisor_default_capacity') ?? 50);
    }

    /**
     * آیا فیلترِ روز/ساعت به‌طور کامل انتخاب شده است؟
     */
    protected function hasSlotFilter(): bool
    {
        return $this->filterDay !== null && $this->filterDay !== ''
            && $this->filterHour !== null && $this->filterHour !== '';
    }

    /**
     * فهرستِ مشاورانِ واجدِ شرایط: نقشِ «مشاور تحصیلی»، ظرفیت‌دار، و (در صورت فیلتر)
     * دارای ساعتِ کاری در روز/ساعتِ انتخاب‌شده.
     */
    protected function eligibleAdvisors()
    {
        $default = $this->defaultCapacity();

        $advisors = Admin::role('مشاور تحصیلی')
            ->with('workSchedules')
            ->withCount('advisedStudents')
            ->orderBy('name')
            ->get()
            // حذفِ مشاورانِ تکمیل‌ظرفیت
            ->filter(function (Admin $a) use ($default) {
                $cap = $a->student_capacity ?? $default;
                return $a->advised_students_count < $cap;
            });

        if ($this->hasSlotFilter()) {
            $day  = (int) $this->filterDay;
            $time = sprintf('%02d:00', (int) $this->filterHour);
            $advisors = $advisors->filter(fn (Admin $a) => $a->isWorkingAt($day, $time));
        }

        return $advisors->values();
    }

    public function openModal(int $advisorId): void
    {
        $this->modalAdvisorId = $advisorId;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalAdvisorId = null;
    }

    /**
     * اعتبارسنجیِ فیلترِ روز/ساعت پیش از هر انتخاب.
     */
    protected function validateSlot(): bool
    {
        if (! $this->hasSlotFilter()) {
            $this->dispatch('warning', 'ابتدا روز هفته و ساعت را انتخاب کنید.');
            return false;
        }

        $day  = (int) $this->filterDay;
        $hour = (int) $this->filterHour;

        if ($day < 0 || $day > 6 || $hour < self::SLOT_START_HOUR || $hour > self::SLOT_END_HOUR) {
            $this->dispatch('warning', 'روز یا ساعتِ انتخابی معتبر نیست.');
            return false;
        }

        return true;
    }

    /**
     * ثبتِ انتخابِ معلق (رزرو) و جایگزینیِ انتخابِ قبلیِ معلق.
     */
    protected function storeSelection(Admin $advisor, string $mode): void
    {
        $student = $this->student();

        DB::transaction(function () use ($student, $advisor, $mode) {
            // آزادسازیِ رزروِ قبلی
            $student->advisorSelections()
                ->where('status', AdvisorSelection::STATUS_PENDING)
                ->delete();

            AdvisorSelection::create([
                'student_id'     => $student->id,
                'advisor_id'     => $advisor->id,
                'weekly_day'     => (int) $this->filterDay,
                'preferred_hour' => sprintf('%02d:00', (int) $this->filterHour),
                'mode'           => $mode,
                'status'         => AdvisorSelection::STATUS_PENDING,
            ]);
        });
    }

    public function selectAdvisor(int $advisorId): void
    {
        $student = $this->student();
        if (! $student || $student->advisor_id !== null) {
            $this->dispatch('error', 'امکان انتخاب مشاور وجود ندارد.');
            return;
        }

        if (! $this->validateSlot()) {
            return;
        }

        $advisor = $this->eligibleAdvisors()->firstWhere('id', $advisorId);
        if (! $advisor) {
            $this->dispatch('error', 'این مشاور در روز/ساعتِ انتخابی در دسترس نیست یا ظرفیتش تکمیل است.');
            return;
        }

        $this->storeSelection($advisor, AdvisorSelection::MODE_MANUAL);
        $this->closeModal();
        $this->dispatch('success', 'انتخابِ شما ثبت شد و برای تاییدِ مدیر آموزشی ارسال گردید.');
    }

    public function selectRandom(): void
    {
        $student = $this->student();
        if (! $student || $student->advisor_id !== null) {
            $this->dispatch('error', 'امکان انتخاب مشاور وجود ندارد.');
            return;
        }

        if (! $this->validateSlot()) {
            return;
        }

        $candidates = $this->eligibleAdvisors();
        if ($candidates->isEmpty()) {
            $this->dispatch('warning', 'برای این روز و ساعت، مشاورِ آزادی موجود نیست. روز یا ساعتِ دیگری را امتحان کنید.');
            return;
        }

        // کم‌بارترین مشاور(ها)؛ در صورت تساوی، یکی به‌صورت تصادفی
        $min  = $candidates->min('advised_students_count');
        $pool = $candidates->where('advised_students_count', $min)->values();
        $chosen = $pool->random();

        $this->storeSelection($chosen, AdvisorSelection::MODE_RANDOM);
        $this->dispatch('success', 'سیستم مشاوری برای شما انتخاب کرد و برای تاییدِ مدیر آموزشی ارسال شد.');
    }

    /**
     * لغوِ انتخابِ معلق تا دانش‌آموز بتواند دوباره انتخاب کند.
     */
    public function cancelSelection(): void
    {
        $student = $this->student();
        if (! $student) {
            return;
        }

        $student->advisorSelections()
            ->where('status', AdvisorSelection::STATUS_PENDING)
            ->delete();

        $this->dispatch('success', 'انتخابِ قبلی لغو شد. می‌توانید مشاورِ دیگری انتخاب کنید.');
    }

    public function render()
    {
        $student = $this->student();

        $approvedAdvisor = null;
        if ($student && $student->advisor_id) {
            $approvedAdvisor = Admin::with('workSchedules')->find($student->advisor_id);
        }

        $pending = $student
            ? $student->pendingAdvisorSelection()->with('advisor')->first()
            : null;

        $advisors = ($student && $student->advisor_id === null)
            ? $this->eligibleAdvisors()
            : collect();

        $modalAdvisor = $this->modalAdvisorId
            ? Admin::with('workSchedules')->withCount('advisedStudents')->find($this->modalAdvisorId)
            : null;

        $hours = range(self::SLOT_START_HOUR, self::SLOT_END_HOUR);

        return view('livewire.client.profile.advisor-selection.index', [
            'student'         => $student,
            'days'            => \App\Models\AdminWorkSchedule::DAYS,
            'hours'           => $hours,
            'advisors'        => $advisors,
            'pending'         => $pending,
            'approvedAdvisor' => $approvedAdvisor,
            'modalAdvisor'    => $modalAdvisor,
            'defaultCapacity' => $this->defaultCapacity(),
        ])->layout('layouts.client.app');
    }
}
