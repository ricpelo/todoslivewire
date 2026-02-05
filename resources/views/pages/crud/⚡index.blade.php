<?php

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Title('Tareas por hacer')] class extends Component
{
    public int $id;

    #[Validate('required|string|max:255')]
    public string $title;

    #[Validate('required|string|max:255')]
    public string $description;

    public bool $verForm;

    public bool $esCrear;

    #[Computed]
    public function todos()
    {
        return Todo::all();
    }

    public function mount()
    {
        Auth::loginUsingId(1);
        $this->resetForm();
    }

    public function crear()
    {
        $this->resetForm();
        $this->verForm = true;
        $this->esCrear = true;
    }

    public function editar($id)
    {
        Gate::authorize('admin');

        /** @var Todo */
        $todo = Todo::find($id);
        $this->fill($todo->only('id', 'title', 'description'));
        $this->verForm = true;
        $this->esCrear = false;
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
        $this->resetValidation();
        $this->verForm = false;
        $this->esCrear = false;
    }

    public function storeOrUpdate()
    {
        if ($this->esCrear) {
            $this->store();
        } else {
            $this->update();
        }
        $this->resetForm();
    }

    public function store()
    {
        $this->validate();
        Todo::create($this->only('title', 'description'));
    }

    public function update()
    {
        Gate::authorize('admin');

        $this->validate();

        /** @var Todo */
        $todo = Todo::find($this->id);
        $todo->update($this->only('title', 'description'));
    }
};
?>

<div class="flex gap-8">
    <div class="flex-1 min-w-3xl">
        <ul class="list bg-base-100 rounded-box shadow-md">
            <li class="p-4 pb-2 text-lg font-bold tracking-wide">Lista de tareas por hacer</li>
            @foreach ($this->todos as $todo)
            <li class="list-row">
                <div class="text-sm uppercase font-semibold">{{ $todo->title }}</div>
                <p class="list-col-grow text-xs">
                    {{ $todo->description }}
                </p>
                @can('admin')
                    <button class="btn btn-ghost btn-info"
                        wire:click="editar({{ $todo->id }})"
                    >
                        Editar
                    </button>
                @endcan
                <button class="btn btn-ghost btn-error"
                    wire:click="borrar({{ $todo->id }})"
                >
                    Borrar
                </button>
            </li>
            @endforeach
        </ul>
        <button class="btn btn-sm btn-primary mt-4" wire:click="crear">Agregar</button>
    </div>
    <div class="max-w-2xl @if(!$verForm) invisible @endif">
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
            <legend class="fieldset-legend">{{ $esCrear ? 'Crear' : 'Editar' }}</legend>

            <label class="label">Título</label>
            <input
                type="text"
                class="input @error('title') input-error @enderror"
                placeholder="Título"
                wire:model.live.blur="title"
            />
            @error('title')
                <span class="text-error">{{ $message }}</span>
            @enderror

            <label class="label">Descripción</label>
            <textarea
                class="textarea @error('description') textarea-error @enderror"
                wire:model="description"
            >
            </textarea>
            @error('description')
                <span class="text-error">{{ $message }}</span>
            @enderror

            <div class="flex justify-between mt-4">
                <button
                    class="btn btn-success btn-ghost"
                    wire:click="storeOrUpdate"
                >
                    {{ $esCrear ? 'Crear' : 'Editar' }}
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
