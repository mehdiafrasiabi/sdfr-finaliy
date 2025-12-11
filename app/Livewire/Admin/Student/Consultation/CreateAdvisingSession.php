<?php


namespace App\Livewire\Admin\Student\Consultation;


use App\Models\Student;

use App\Models\AdvisingSession;

use App\Models\AdvisingPreSession;

use Illuminate\Support\Facades\Validator;

use Livewire\Component;

use Livewire\WithPagination;


class CreateAdvisingSession extends Component

{

    use WithPagination;


    public $studentId = null;

    public $title;

    public $description;

    public $activation_date;

    public $session_time;

    public $location_type = 'online';

    public $skyroom_link;


    // ویرایش وضعیت جلسه

    public $editingSessionId = null;

    public $result_status = null;


    protected function messages()

    {

        return [

            'title.required' => 'وارد کردن عنوان جلسه الزامی است.',

            'title.max' => 'عنوان جلسه نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'description.string' => 'فرمت توضیحات صحیح نیست.',

            'activation_date.required' => 'تاریخ برگزاری جلسه الزامی است.',

            'activation_date.date' => 'فرمت تاریخ صحیح نیست.',

            'session_time.required' => 'ساعت برگزاری جلسه الزامی است.',

            'location_type.required' => 'محل برگزاری الزامی است.',

            'location_type.in' => 'محل برگزاری معتبر نیست.',

            'skyroom_link.required_if' => 'لینک جلسه آنلاین الزامی است.',

            'skyroom_link.url' => 'فرمت لینک صحیح نیست.',

        ];

    }


    public function mount(Student $student)

    {

        $this->studentId = $student->id;

    }


    public function createSession($formData)

    {

        $rules = [

            'title' => 'required|string|max:255',

            'description' => 'nullable|string',

            'activation_date' => 'required|date',

            'session_time' => 'required',

            'location_type' => 'required|in:in_person,online',

        ];


        // اگر آنلاین انتخاب شده، لینک الزامی است

        if ($this->location_type === 'online') {

            $rules['skyroom_link'] = 'required|url';

        }


        $validator = Validator::make($formData, $rules, $this->messages());

        $validator->validate();


        // ساخت جلسه اصلی

        $session = AdvisingSession::create([

            'student_id' => $this->studentId,

            'advisor_id' => auth()->id(),

            'title' => $this->title,

            'description' => $this->description,

            'activation_date' => $this->activation_date,

            'session_time' => $this->session_time,

            'location_type' => $this->location_type,

            'skyroom_link' => $this->location_type === 'online' ? $this->skyroom_link : null,

            'status' => 'inactive',

            'is_active' => false,

        ]);


        // ساخت پیش‌جلسه به‌صورت خودکار

        AdvisingPreSession::create([

            'advising_session_id' => $session->id,

            'student_id' => $this->studentId,

            'title' => $this->title,

            'status' => 'pending',

        ]);


        // ریست فرم

        $this->reset(['title', 'description', 'activation_date', 'session_time', 'skyroom_link']);

        $this->location_type = 'online';


        $this->dispatch('success', 'جلسه مشاوره و پیش‌جلسه با موفقیت ایجاد شد.');

    }


    // به‌روزرسانی وضعیت نتیجه جلسه

    public function updateResultStatus($sessionId, $status)

    {

        $session = AdvisingSession::find($sessionId);

        if ($session) {

            $session->update([

                'result_status' => $status,

                'status' => 'completed',

            ]);

            $this->dispatch('success', 'وضعیت جلسه با موفقیت ثبت شد.');

        }

    }


    // حذف جلسه

    public function deleteSession($id)

    {

        $session = AdvisingSession::find($id);

        if ($session) {

            AdvisingPreSession::where('advising_session_id', $session->id)->delete();

            $session->delete();

            $this->dispatch('success', 'جلسه مشاوره حذف شد.');

        } else {

            $this->dispatch('warning', 'جلسه مورد نظر یافت نشد.');

        }

    }


    public function render()

    {

        $student = Student::with(['user.personalInformation'])->find($this->studentId);

        $sessions = AdvisingSession::where('student_id', $this->studentId)
            ->with('preSession')
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        // فعال‌سازی خودکار جلسات

        foreach ($sessions as $session) {

            $session->activateIfNeeded();

        }


        return view('livewire.admin.student.consultation.create-advising-session', [

            'student' => $student,

            'sessions' => $sessions,

        ])->layout('layouts.admin.app');

    }

}

