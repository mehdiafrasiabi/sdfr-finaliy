<?php

namespace App\Livewire\Client\Profile\Consultation;

use App\Models\AdvisingSession;
use App\Models\WeeklyProgram;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class SessionList extends Component
{
    use WithPagination, SEOTools;

    public $showPreSessionModal = false;
    public $selectedSession = null;

    public function mount()
    {
        $this->seo()->setTitle('اتاق مشاوره');
    }

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

    public function render()
    {
        $user    = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        $sessions        = collect();
        $weeklyPrograms  = collect();
        $lockedSessionIds = [];

        if ($student) {
            // ترتیب صعودی برای تشخیص قفل بودن جلسات
            $allSessionsOrdered = AdvisingSession::where('student_id', $student->id)
                ->orderBy('activation_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($allSessionsOrdered as $index => $session) {
                if ($index === 0) continue;
                $prev = $allSessionsOrdered[$index - 1];
                if ($prev->result_status === null) {
                    $lockedSessionIds[] = $session->id;
                }
            }

            // ۱۰ جلسه آخر — جدیدترین اول
            $sessions = AdvisingSession::where('student_id', $student->id)
                ->with(['preSession', 'advisor', 'weeklyProgram'])
                ->orderBy('activation_date', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(10);

            foreach ($sessions as $session) {
                $session->activateIfNeeded();
            }

            $weeklyPrograms = WeeklyProgram::where('student_id', $student->id)
                ->with('parts')
                ->where('is_active', true)
                ->orderBy('start_date', 'desc')
                ->get();
        }

        return view('livewire.client.profile.consultation.session-list', [
            'sessions'         => $sessions,
            'weeklyPrograms'   => $weeklyPrograms,
            'student'          => $student,
            'lockedSessionIds' => $lockedSessionIds,
        ])->layout('layouts.client.app');
    }
}
