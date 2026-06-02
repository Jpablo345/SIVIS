<?php

use App\Models\Article;
use App\Models\Journal;
use App\Models\Publication;
use App\Models\PublicationType;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;

    public string $title = '';
    public ?string $publication_year = null;
    public ?string $scope = null;
    public ?string $country_publication = null;
    public ?string $url = null;
    public ?string $journal_issn = null;

    public ?int $articleTypeId = null;

    public function mount(): void
    {
        $this->resolveArticleType();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function edit(int $publicationId): void
    {
        $article = Article::with(['publication', 'journal'])->findOrFail($publicationId);

        $this->editingId = $article->publication_id;
        $this->title = $article->publication?->title ?? '';
        $this->publication_year = $article->publication?->publication_year;
        $this->scope = $article->publication?->scope;
        $this->country_publication = $article->publication?->country_publication;
        $this->url = $article->publication?->url;
        $this->journal_issn = $article->journal_issn;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function save(): void
    {
        if (trim($this->title) === '' || ! $this->journal_issn) {
            session()->flash('status', 'Completa el titulo y la revista.');
            return;
        }

        $publicationData = [
            'title' => trim($this->title),
            'publication_year' => $this->publication_year ? trim($this->publication_year) : null,
            'scope' => $this->scope,
            'country_publication' => $this->country_publication ? trim($this->country_publication) : null,
            'url' => $this->url ? trim($this->url) : null,
            'type_id' => $this->articleTypeId,
        ];

        DB::transaction(function () use ($publicationData) {
            if ($this->editingId) {
                Publication::where('publication_id', $this->editingId)->update($publicationData);
                $publicationId = $this->editingId;
            } else {
                $publication = Publication::create($publicationData);
                $publicationId = $publication->publication_id;
            }

            Article::updateOrCreate(
                ['publication_id' => $publicationId],
                ['journal_issn' => $this->journal_issn]
            );
        });

        session()->flash('status', $this->editingId ? 'Articulo actualizado.' : 'Articulo creado.');
        $this->resetForm();
    }

    public function delete(int $publicationId): void
    {
        Publication::where('publication_id', $publicationId)->delete();
        session()->flash('status', 'Articulo eliminado.');
        $this->resetPage();
    }

    public function getArticlesProperty()
    {
        $term = trim($this->search);

        return Article::query()
            ->with(['publication', 'journal'])
            ->when($term !== '', function ($query) use ($term) {
                $like = '%' . $term . '%';
                $query->whereHas('publication', function ($publication) use ($like) {
                    $publication->where('title', 'ilike', $like)
                        ->orWhere('publication_year', 'ilike', $like)
                        ->orWhere('scope', 'ilike', $like)
                        ->orWhere('country_publication', 'ilike', $like);
                })
                ->orWhereHas('journal', function ($journal) use ($like) {
                    $journal->where('journal_name', 'ilike', $like)
                        ->orWhere('journal_issn', 'ilike', $like);
                });
            })
            ->orderByDesc('publication_id')
            ->paginate(10);
    }

    public function getJournalsProperty()
    {
        return Journal::orderBy('journal_name')->get(['journal_issn', 'journal_name']);
    }

    private function resolveArticleType(): void
    {
        $type = PublicationType::query()
            ->whereRaw('lower(type_name) = ?', ['articulo'])
            ->first(['type_id']);

        $this->articleTypeId = $type?->type_id;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->publication_year = null;
        $this->scope = null;
        $this->country_publication = null;
        $this->url = null;
        $this->journal_issn = null;
        $this->resetValidation();
    }
}; ?>

<div class="space-y-6">
    <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-[#2b2323]">
                    {{ $editingId ? 'Editar articulo' : 'Nuevo articulo' }}
                </h3>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">
                    Datos generales y revista asociada
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if ($editingId)
                    <button type="button" wire:click="cancelEdit"
                        class="rounded-full border border-[#9c1c1c]/30 px-4 py-2 text-xs font-semibold text-[#7a1515] hover:bg-[#f2d3d3]">
                        Cancelar
                    </button>
                @endif
                <button type="button" wire:click="save"
                    class="rounded-full bg-[#9c1c1c] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#7a1515]">
                    {{ $editingId ? 'Guardar cambios' : 'Crear articulo' }}
                </button>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-4 rounded-xl bg-[#f7e2d2] px-4 py-3 text-xs font-semibold text-[#7a1515]">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <x-input-label for="title" :value="'Titulo'" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <x-text-input id="title" wire:model.defer="title" type="text" class="mt-2 block w-full" />
            </div>
            <div>
                <x-input-label for="publication_year" :value="'Ano'" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <x-text-input id="publication_year" wire:model.defer="publication_year" type="text" class="mt-2 block w-full" />
            </div>
            <div>
                <x-input-label for="scope" :value="'Ambito'" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <select id="scope" wire:model.defer="scope"
                    class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                    <option value="">Sin definir</option>
                    <option value="Nacional">Nacional</option>
                    <option value="Internacional">Internacional</option>
                </select>
            </div>
            <div>
                <x-input-label for="country_publication" :value="'Pais'" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <x-text-input id="country_publication" wire:model.defer="country_publication" type="text" class="mt-2 block w-full" />
            </div>
            <div>
                <x-input-label for="url" :value="'URL'" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <x-text-input id="url" wire:model.defer="url" type="url" class="mt-2 block w-full" placeholder="https://" />
            </div>
            <div class="md:col-span-2">
                <x-input-label for="journal_issn" :value="'Revista (ISSN)'" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <select id="journal_issn" wire:model.defer="journal_issn"
                    class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                    <option value="">Selecciona una revista</option>
                    @foreach ($this->journals as $journal)
                        <option value="{{ $journal->journal_issn }}">{{ $journal->journal_name }} ({{ $journal->journal_issn }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-[#2b2323]">Articulos registrados</h3>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">{{ $this->articles->total() }} registros</p>
            </div>
            <div class="w-full sm:w-72">
                <x-text-input wire:model.live="search" type="search" placeholder="Buscar por titulo o revista" class="w-full" />
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-[#f0dede]">
            <table class="min-w-full divide-y divide-[#f0dede] text-sm">
                <thead class="bg-[#fff7f7] text-left text-xs font-semibold uppercase tracking-[0.2em] text-[#7a1515]">
                    <tr>
                        <th class="px-4 py-3">Titulo</th>
                        <th class="px-4 py-3">Ano</th>
                        <th class="px-4 py-3">Revista</th>
                        <th class="px-4 py-3">Ambito</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0dede] bg-white">
                    @forelse ($this->articles as $article)
                        <tr wire:key="article-{{ $article->publication_id }}" class="hover:bg-[#fff7f7]">
                            <td class="px-4 py-3 font-semibold text-[#2b2323]">{{ $article->publication?->title }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $article->publication?->publication_year ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $article->journal?->journal_name ?? $article->journal_issn }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $article->publication?->scope ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="edit({{ $article->publication_id }})"
                                        class="rounded-full border border-[#9c1c1c]/30 px-3 py-1 text-xs font-semibold text-[#7a1515] hover:bg-[#f2d3d3]">
                                        Editar
                                    </button>
                                    <button type="button" wire:click="delete({{ $article->publication_id }})"
                                        onclick="confirm('Seguro que deseas eliminar este articulo?') || event.stopImmediatePropagation()"
                                        class="rounded-full border border-[#d77a7a]/40 px-3 py-1 text-xs font-semibold text-[#9c1c1c] hover:bg-[#f9dede]">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                No hay articulos registrados todavia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $this->articles->links() }}
        </div>
    </div>
</div>
