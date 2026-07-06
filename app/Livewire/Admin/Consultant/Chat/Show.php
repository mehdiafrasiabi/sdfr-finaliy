<?php

namespace App\Livewire\Admin\Consultant\Chat;

use App\Livewire\Concerns\ManagesConversationThread;
use App\Models\Conversation;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * thread گفتگوی مشاور با یک دانش‌آموزِ تحت مشاوره‌اش.
 */
class Show extends Component
{
    use WithFileUploads, ManagesConversationThread;

    public string $side = 'advisor';

    public Student $student;
    public ?Conversation $conversation = null;

    public function mount(Student $student): void
    {
        // فقط مشاورِ مسئولِ همین دانش‌آموز اجازه دارد.
        abort_unless((int) $student->advisor_id === (int) auth('admin')->id(), 403);

        $this->student = $student;
        $this->conversation = Conversation::firstOrCreate(
            ['student_id' => $student->id],
            ['advisor_id' => $student->advisor_id],
        );

        // باز کردن thread = خواندن پیام‌های دانش‌آموز.
        $this->tick();
    }

    public function render()
    {
        return view('livewire.admin.consultant.chat.show', [
            'messages' => $this->conversation->messages()->get(),
        ])->layout('layouts.admin.app');
    }
}
