<?php

namespace App\Livewire\Publicaciones;

use App\Models\Publication;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PublicationsExport;
use Barryvdh\DomPDF\Facade\Pdf;

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

    /**
     * Propiedad computada base encargada de procesar el filtro de búsqueda.
     * Compartida tanto por la tabla web como por los motores de exportación.
     */
    public function getPublicationsQueryProperty()
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
            ->orderByDesc('publication_id');
    }

    /**
     * Entrega las publicaciones paginadas para el renderizado de la vista de Livewire
     */
    public function getPublicationsProperty()
    {
        return $this->getPublicationsQueryProperty()->paginate(10);
    }

    // Exportación a Excel usando el paquete Maatwebsite
    public function exportExcel()
    {
        $publications = $this->getPublicationsQueryProperty()->get();
        return Excel::download(new PublicationsExport($publications), 'publicaciones_filtradas.xlsx');
    }

    // Exportación a PDF usando el paquete DomPDF
    public function exportPdf()
    {
        $publications = $this->getPublicationsQueryProperty()->get();
        
        $pdf = Pdf::loadView('exports.publications-pdf', compact('publications'))
                  ->setPaper('a4', 'landscape'); // Formato horizontal para tablas anchas

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'publicaciones_filtradas.pdf');
    }

    public function render()
    {
        return view('publicaciones.index')->layout('layouts.app');
    }
}