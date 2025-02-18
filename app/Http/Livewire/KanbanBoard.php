<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Task;

class KanbanBoard extends Component
{
    public $tasks;

    public function mount()
    {
        $this->tasks = Task::all();
    }

    public function updateTaskOrder($taskOrder)
    {
        foreach ($taskOrder as $task) {
            Task::where('id', $task['id'])->update(['status' => $task['status']]);
        }

        $this->tasks = Task::all(); // Refresh tasks
    }

    public function render()
    {
        return view('livewire.kanban-board');
    }
}