<?php

namespace App\Livewire;

use App\Events\TodoCreated;
use App\Models\Todo;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class TodoList extends Component
{
    public $content;
    public $todos = [];

    public function mount()
    {
        $this->todos = Todo::orderBy('id', 'desc')->get(); // Load existing todos
        Log::info('Mount todos:', $this->todos->toArray()); // Debug
    }

    public function createTodo()
    {
        $this->validate([
            'content' => 'required|string|max:255',
        ]);

        $todo = Todo::create(['content' => $this->content]);

        // Dispatch the event with the new Todo
        TodoCreated::dispatch($todo);

        $this->content = ''; // Clear the input field
    }

    #[On('echo:todos-a,TodoCreated')]
    public function todoAdded($todo)
    {
        $todo = Todo::where('id', $todo['todo']['id'])->first();

        $this->todos->prepend($todo);
    }

    public function render()
    {
        Log::info('Rendering todos:', $this->todos->toArray());
        return view('livewire.todo-list')->layout('layouts.app');
    }
}
