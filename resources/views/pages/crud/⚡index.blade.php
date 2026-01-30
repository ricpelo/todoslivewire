<?php

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

new class extends Component
{
    public Collection $todos;

    public int $id;
    public string $title;
    public string $description;

    public bool $verForm;

    public function mount()
    {
        $this->todos = Todo::all();
        $this->resetForm();
    }

    public function editar($id)
    {
        /** @var Todo */
        $todo = Todo::find($id);
        // $this->title = $todo->title;
        // $this->description = $todo->description;
        $this->fill($todo->only('id', 'title', 'description'));
        $this->verForm = true;
    }

    public function borrar($id)
    {
        Todo::destroy($id);
    }

    public function resetForm()
    {
        $this->id = 0;
        $this->title = '';
        $this->description = '';
        $this->verForm = false;
    }

    public function update($id)
    {
        /** @var Todo */
        $todo = Todo::find($id);
        $todo->title = $this->title;
        $todo->description = $this->description;
        $todo->save();
        $this->resetForm();
    }
};
?>

<div class="flex gap-8">
    <div class="flex-1 min-w-3xl">
        <ul class="list bg-base-100 rounded-box shadow-md">
            <li class="p-4 pb-2 text-lg font-bold tracking-wide">Lista de tareas por hacer</li>
            @foreach ($todos as $todo)
            <li class="list-row">
                <div class="text-sm uppercase font-semibold">{{ $todo->title }}</div>
                <p class="list-col-grow text-xs">
                    {{ $todo->description }}
                </p>
                <button class="btn btn-ghost btn-info"
                    wire:click="editar({{ $todo->id }})"
                >
                    Editar
                </button>
                <button class="btn btn-ghost btn-error"
                    wire:click="borrar({{ $todo->id }})"
                >
                    Borrar
                </button>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="max-w-2xl @if(!$verForm) invisible @endif">
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
            <legend class="fieldset-legend">Editar</legend>

            <label class="label">Título</label>
            <input type="text" class="input" placeholder="Título" wire:model="title" />

            <label class="label">Descripción</label>
            <textarea class="textarea" wire:model="description">
            </textarea>

            <div class="flex justify-between mt-4">
                <button
                    class="btn btn-success btn-ghost"
                    wire:click="update({{ $id }})"
                >
                    Editar
                </button>
                <button
                    class="btn btn-neutral btn-ghost"
                    wire:click="resetForm"
                >
                    Cancelar
                </button>
            </div>
        </fieldset>
    </div>
</div>
