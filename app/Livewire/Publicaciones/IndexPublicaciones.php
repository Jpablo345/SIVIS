<?php

namespace App\Livewire\Publicaciones;

use App\Models\Publication;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPublicaciones extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showForm = false;
    public ?int $editingId = null;

    protected $listeners = ['publicacionGuardada' => 'handleSaved'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function abrirFormulario(): void
    {
        $this->editingId = null;
        $this->showForm = true;
    }

    public function editar(int $publicationId): void
    {
        $this->editingId = $publicationId;
        $this->showForm = true;
    }

    public function cerrarFormulario(): void
    {
        $this->showForm = false;
        $this->editingId = null;
    }

    public function eliminar(int $publicationId): void
    {
        Publication::where('publication_id', $publicationId)->delete();
        $this->resetPage();
    }

    public function handleSaved(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->resetPage();
    }

    public function getPublicationsProperty()
    {
        $term = trim($this->search);

        return Publication::query()
            ->with(['type', 'researchers'])
            ->when($term !== '', function ($query) use ($term) {
                $like = '%' . $term . '%';
                $query->where(function ($inner) use ($like) {
                    $inner->where('title', 'ilike', $like)
                        ->orWhere('publication_year', 'ilike', $like)
                        ->orWhere('scope', 'ilike', $like)
                        ->orWhereHas('type', function ($type) use ($like) {
                            $type->where('type_name', 'ilike', $like);
                        })
                        ->orWhereHas('researchers', function ($researcher) use ($like) {
                            $researcher->where('name_1', 'ilike', $like)
                                ->orWhere('last_name_1', 'ilike', $like);
                        });
                });
            })
            ->orderByDesc('publication_id')
            ->paginate(10);
    }

    public function render()
    {
        return view('publicaciones.index')->layout('layouts.app');
    }
}
