<?php

namespace App\Livewire\Client\Profile;

use App\Models\PreAdvisingSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MultiStepModal extends Component
{
    public $show = false;
    public $step = 1;

    public $homeworks = [];
    public $exams = [];
    public $free_times = [];

    public function mount()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->step = 1;
        $this->homeworks = [['lesson' => '', 'parts' => '', 'date' => '']];
        $this->exams = [['lesson' => '', 'date' => '']];
        $this->free_times = [['day' => '', 'time' => '']];
    }

    public function addHomework() { $this->homeworks[] = ['lesson' => '', 'parts' => '', 'date' => '']; }
    public function addExam() { $this->exams[] = ['lesson' => '', 'date' => '']; }
    public function addFreeTime() { $this->free_times[] = ['day' => '', 'time' => '']; }
    public function updatedShow($value)
    {
        if(!$value) $this->resetForm();
    }

    public function close()
    {
        $this->dispatch('closePreAdvisingModal');
        $this->resetForm();
    }




    public function nextStep()
    {
        $this->validateCurrentStep();
        $this->step++;
    }

    public function prevStep() { $this->step--; }

    private function validateCurrentStep()
    {
        if($this->step === 1){
            $this->validate(['homeworks.*.lesson' => 'required','homeworks.*.parts'=>'required|integer','homeworks.*.date'=>'required|date']);
        } elseif($this->step === 2){
            $this->validate(['exams.*.lesson'=>'required','exams.*.date'=>'required|date']);
        } elseif($this->step === 3){
            $this->validate(['free_times.*.day'=>'required','free_times.*.time'=>'required']);
        }
    }

    public function save()
    {
        try {
            $this->validate([
                'homeworks.*.lesson' => 'required',
                'homeworks.*.parts' => 'required',
                'homeworks.*.date' => 'required|date',
                'exams.*.lesson' => 'required',
                'exams.*.date' => 'required|date',
                'free_times.*.day' => 'required',
                'free_times.*.time' => 'required',
            ], [
                'required' => 'پر کردن این فیلد الزامی است.',
                'date' => 'فرمت تاریخ معتبر نیست.',
                'integer' => 'لطفاً عدد معتبر وارد کنید.',
            ]);

            $user = Auth::user();
            if (! $user) {
                $this->dispatch('warning', message: 'کاربر وارد نشده است.');
                return;
            }

            $student = $user->student;
            if (! $student) {
                $this->dispatch('warning', message: 'پروفایل دانش‌آموز برای این حساب وجود ندارد.');
                return;
            }

            // بررسی تکراری بودن فرم
            if (PreAdvisingSession::where('student_id', $student->id)->exists()) {
                $this->dispatch('warning', message: 'شما قبلاً فرم پیش‌جلسه را ثبت کرده‌اید.');
                $this->close();
                return;
            }

            // ذخیره اطلاعات
            PreAdvisingSession::create([
                'student_id' => $student->id,
                'homeworks' => $this->homeworks,
                'exams' => $this->exams,
                'free_times' => $this->free_times,
            ]);

            $this->dispatch('success', message: 'فرم شما با موفقیت ثبت شد ✅');
            $this->emitUp('handlePreAdvisingSaved');
            $this->close();

        } catch (\Exception $e) {
            $this->dispatch('warning', message: 'خطایی هنگام ثبت پیش آمد: ' . $e->getMessage());
        }
    }



    public function render()
    {
        return view('livewire.client.profile.multi-step-modal');
    }
}
