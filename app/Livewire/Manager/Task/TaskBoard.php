<?php

namespace App\Livewire\Manager\Task;

use App\Models\Admin;
use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class TaskBoard extends Component
{
    use WithPagination;
    public $tasks;
    public $userId = null; // فیلتر بر اساس کاربر

    public function updatedUserId()
    {
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $query = Task::with(['assignee','creator']);

        if ($this->userId) {
            $query->where('assigned_to', $this->userId);
        }

        $this->tasks = $query->get();
    }

    public function mount()
    {
        $this->loadTasks();
    }
    public function updateStatus($taskId, $status)
    {
        $task = Task::findOrFail($taskId);
        $task->update(['status' => $status]);
        $this->loadTasks();
    }
    public function render()
    {
        $admins = Admin::all();
        $tasks = Task::query()
            ->with(['assignee','creator','category'])
            ->paginate(10);
        return view('livewire.manager.task.task-board',['users' => \App\Models\User::all()])->layout('layouts.manager.app');
    }
}
