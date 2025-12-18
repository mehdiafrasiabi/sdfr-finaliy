<?php

namespace App\Livewire\Admin\Student;

use App\Models\MeetLinkGoogle;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AdvisorSessionHeld;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class MeetGoogle extends Component
{
    use WithPagination,SEOTools;
    public $title;
    public $link;
    public $body;
    public $barnameh;
    public $studentName;
    public $studentId;
    public function mount(Student $student)
    {
        $this->studentId = $student->student->id;
        $this->studentName =$student->personalInformation->name ?? $student->name;
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('لینک گوگل میت'.$this->studentName);
    }
    public function submit()
    {
        $this->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:100',
            'link' => 'required|string',
        ], [
            '*.required' => ' الزامی است.',
            '*.string' => ' تایپ اشتباه است.',
            '*.max' => 'تر حداکثر کارکتر:100',
        ]);


            MeetLinkGoogle::create([
                'admin_id' => auth()->id(),
                'student_id' => $this->studentId,
                'title' => $this->title,
                'body' => $this->body,
                'link' => $this->link,
                'is_active' => true,
            ]);


        $this->dispatch('success', 'لینک با موفقیت ارسال شد.');
        $this->reset(['title', 'body', 'link',]);
    }
    public function delete($meet_id)
    {
        MeetLinkGoogle::query()->where('id', $meet_id)->delete();
        $this->dispatch('success', 'با موفقیت حذف شد');
    }
    public function toggleActive($meet_id)
    {
        $meet = MeetLinkGoogle::findOrFail($meet_id);
        $meet->is_active = ! $meet->is_active;
        $meet->save();
        $this->dispatch('success', 'وضعیت با موفقیت به‌روزرسانی شد.');

        if ($meet->is_active) {
            $student = Student::with('user.personalInformation')->find($this->studentId);

            if ($student && $student->user) {
                $mobile = $student->user->personalInformation->mobile ?? $student->user->mobile;
                $name = $student->user->personalInformation->name ?? $student->user->name ?? $this->studentName;

                if ($mobile && $name) {
                    $date = jalali(now())->format('%Y/%m/%d');
                    $student->user->notify(new AdvisorSessionHeld($mobile, $name, $date));
                }
            }
        }
    }

    public function render()
    {
        $meets = MeetLinkGoogle::query()
            ->where('student_id', $this->studentId)
            ->latest()
            ->paginate(10);

        return view('livewire.admin.student.meet-google',['meets'=>$meets])->layout('layouts.admin.app');
    }
}
