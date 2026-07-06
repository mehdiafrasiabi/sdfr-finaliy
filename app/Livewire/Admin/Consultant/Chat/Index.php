<?php

namespace App\Livewire\Admin\Consultant\Chat;

use App\Models\Student;
use Livewire\Component;

/**
 * لیست دانش‌آموزانِ تحت مشاوره‌ی این مشاور برای گفتگوی مستقیم.
 */
class Index extends Component
{
    public string $search = '';

    public function render()
    {
        $advisorId = auth('admin')->id();

        $students = Student::query()
            ->where('advisor_id', $advisorId)
            ->with(['user', 'conversation.messages'])
            ->when($this->search !== '', function ($q) {
                $q->whereHas('user', function ($u) {
                    $u->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%");
                });
            })
            ->get()
            ->sortByDesc(fn ($s) => optional($s->conversation)->last_message_at?->timestamp ?? 0)
            ->values();

        return view('livewire.admin.consultant.chat.index', [
            'students' => $students,
        ])->layout('layouts.admin.app');
    }
}
