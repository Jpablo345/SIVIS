<?php

namespace App\Livewire\Publicaciones;

use App\Models\Publication;
use App\Models\PublicationType;
use App\Models\ResearchGroup;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PublicationsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class IndexPublicaciones extends Component
{
    use WithPagination;

    // Filtros existentes
    public string $search = '';
    
    // Nuevos filtros
    public ?string $filterYear = '';
    public ?string $filterType = '';
    public ?string $filterGroup = '';
    public ?string $filterAuthor = '';

    // Estado del formulario
    public bool $showForm = false;
    public ?int $editingId = null;

    protected $listeners = ['publicacionGuardada' => 'handleSaved'];

    // Actualizar página cuando cambia cualquier filtro
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterYear(): void
    {
        $this->resetPage();
    }

    public function updatedFilterType(): void
    {
        $this->resetPage();
    }

    public function updatedFilterGroup(): void
    {
        $this->resetPage();
    }

    public function updatedFilterAuthor(): void
    {
        $this->resetPage();
    }

    // Limpiar todos los filtros
    public function clearFilters(): void
    {
        $this->search = '';
        $this->filterYear = '';
        $this->filterType = '';
        $this->filterGroup = '';
        $this->filterAuthor = '';
        $this->resetPage();
    }

    public function getHasActiveFiltersProperty(): bool
    {
        return !empty($this->search) || 
               !empty($this->filterYear) || 
               !empty($this->filterType) || 
               !empty($this->filterGroup) || 
               !empty($this->filterAuthor);
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
     * Propiedad computada base encargada de procesar todos los filtros
     */
    public function getPublicationsQueryProperty()
    {
        $term = trim($this->search);
        $year = trim($this->filterYear);
        $type = trim($this->filterType);
        $group = trim($this->filterGroup);
        $author = trim($this->filterAuthor);

        return Publication::query()
            ->with([
                'type',
                'researchers.researchGroup.institution',
                'article.journal',
                'book.bookType',
            ])
            // Filtro de búsqueda por texto
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
            // Filtro por año
            ->when($year !== '', function ($query) use ($year) {
                $query->where('publication_year', $year);
            })
            // Filtro por tipo de publicación
            ->when($type !== '', function ($query) use ($type) {
                $query->whereHas('type', function ($q) use ($type) {
                    $q->where('publication_type_id', $type);
                });
            })
            // Filtro por grupo de investigación
            ->when($group !== '', function ($query) use ($group) {
                $query->whereHas('researchers.researchGroup', function ($q) use ($group) {
                    $q->where('research_group_id', $group);
                });
            })
            // Filtro por autor
            ->when($author !== '', function ($query) use ($author) {
                $like = '%' . $author . '%';
                $query->whereHas('researchers', function ($q) use ($like) {
                    $q->where('name_1', 'ilike', $like)
                        ->orWhere('name_2', 'ilike', $like)
                        ->orWhere('last_name_1', 'ilike', $like)
                        ->orWhere('last_name_2', 'ilike', $like);
                });
            })
            ->orderByDesc('publication_id');
    }

    /**
     * Entrega las publicaciones paginadas para el renderizado
     */
    public function getPublicationsProperty()
    {
        return $this->getPublicationsQueryProperty()->paginate(10);
    }

    // Exportación a Excel
    public function exportExcel()
    {
        $publications = $this->getPublicationsQueryProperty()->get();
        return Excel::download(new PublicationsExport($publications), 'publicaciones_filtradas.xlsx');
    }

    // Exportación a PDF
    public function exportPdf()
    {
        $publications = $this->getPublicationsQueryProperty()->get();
        
        $pdf = Pdf::loadView('exports.publications-pdf', compact('publications'))
                  ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'publicaciones_filtradas.pdf');
    }

    // Obtener datos para los selects de filtros
    public function getYearsProperty()
    {
        return Publication::select('publication_year')
            ->distinct()
            ->orderBy('publication_year', 'desc')
            ->pluck('publication_year');
    }

    public function getTypesProperty()
    {
        return PublicationType::orderBy('type_name')->get();
    }

    public function getGroupsProperty()
    {
        return ResearchGroup::orderBy('group_name')->get();
    }

    public function render()
    {
        return view('publicaciones.index')->layout('layouts.app');
    }
}