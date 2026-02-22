<?php


namespace App\Livewire\Client\Profile\Consultation;

use App\Models\AdvisingSession;
use App\Models\AdvisingPreSession;
use App\Models\WeeklyProgram;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

use Livewire\WithPagination;


class SessionList extends Component

{

    use WithPagination,SEOTools;
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

    public function mount()
    {
        $this->seo()->setTitle('اتاق مشاوره');
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
        $lockedSessionIds = [];
        if ($student) {
            // Get ALL sessions in chronological order (asc) to determine locking
            $allSessionsOrdered = AdvisingSession::where('student_id', $student->id)
                ->orderBy('activation_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            // Determine which sessions are locked:
            // - Session 0 (earliest): always unlocked
            // - Session N: locked if session N-1 has no result_status
            foreach ($allSessionsOrdered as $index => $session) {
                if ($index === 0) {
                    continue; // first session always unlocked
                }
                $prevSession = $allSessionsOrdered[$index - 1];
                if ($prevSession->result_status === null) {
                    $lockedSessionIds[] = $session->id;
                }
            }

            // Paginate for display (desc order for display)
            $sessions = AdvisingSession::where('student_id', $student->id)
                ->with(['preSession', 'advisor', 'weeklyProgram'])
                ->orderBy('activation_date', 'desc')->latest()
                ->paginate(10);
            // Auto-activate sessions
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
            'lockedSessionIds' => $lockedSessionIds,
        ])->layout('layouts.client.app');
    }
}
