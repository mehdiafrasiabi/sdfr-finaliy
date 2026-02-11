<?php


namespace App\Livewire\Client\Profile\Consultation;


use App\Models\AdvisingSession;

use App\Models\AdvisingPreSession;

use App\Models\WeeklyProgram;

use App\Models\Student;

use Livewire\Component;

use Livewire\WithPagination;


class SessionList extends Component

{

    use WithPagination;


    public $showPreSessionModal = false;

    public $selectedSession = null;


    public function openPreSessionModal($sessionId)

    {

        $session = AdvisingSession::with('preSession')->find($sessionId);

        if ($session && $session->canFillPreSession()) {

            $this->selectedSession = $session;

            $this->showPreSessionModal = true;

        } else {

            $this->dispatch('warning', 'امکان ویرایش پیش‌جلسه وجود ندارد. زمان برگزاری جلسه فرا رسیده است.');

        }

    }


    public function closePreSessionModal()

    {

        $this->showPreSessionModal = false;

        $this->selectedSession = null;

    }


    public function confirmStartPreSession()

    {

        if ($this->selectedSession) {

            return redirect()->route('client.profile.consultation.pre-session', [

                'session' => $this->selectedSession->id

            ]);

        }

    }
    public $expandedSessions = [];

    public function toggleDetails($sessionId)
    {
        if (in_array($sessionId, $this->expandedSessions)) {
            $this->expandedSessions = array_diff($this->expandedSessions, [$sessionId]);
        } else {
            $this->expandedSessions[] = $sessionId;
        }
    }

    public function render()

    {

        $user = auth()->user();

        $student = Student::where('user_id', $user->id)->first();


        $sessions = collect();

        $weeklyPrograms = collect();


        if ($student) {

            $sessions = AdvisingSession::where('student_id', $student->id)
                ->with(['preSession', 'advisor', 'weeklyProgram'])
                ->orderBy('activation_date', 'desc')
                ->paginate(10);


            // فعال‌سازی خودکار جلسات

            foreach ($sessions as $session) {

                $session->activateIfNeeded();

            }


            // برنامه‌های هفتگی

            $weeklyPrograms = WeeklyProgram::where('student_id', $student->id)
                ->with('parts')
                ->where('is_active', true)
                ->orderBy('start_date', 'desc')
                ->get();

        }


        return view('livewire.client.profile.consultation.session-list', [

            'sessions' => $sessions,

            'weeklyPrograms' => $weeklyPrograms,

            'student' => $student,

        ])->layout('layouts.client.app');

    }

}
