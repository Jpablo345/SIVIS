<div>
   <div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#9c1c1c]">Publicaciones</p>
            <h2 class="text-2xl font-semibold text-[#2b2323]">Registro general</h2>
        </div>
        
        <div class="flex items-center gap-2">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button"
                    class="inline-flex items-center gap-2 rounded-full bg-[#2b2323] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#1f1a1a]">
                    <span>Exportar</span>
                    <svg class="h-3 w-3 transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" 
                    x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-44 origin-top-right rounded-xl border border-gray-100 bg-white p-1 shadow-lg z-50">
                    <button wire:click="exportExcel" @click="open = false" class="flex w-full items-center gap-2 px-3 py-2 text-left text-xs font-semibold text-gray-700 hover:bg-[#fff7f7] hover:text-[#9c1c1c] rounded-lg">
                        <span>📊</span> Exportar a Excel
                    </button>
                    <button wire:click="exportPdf" @click="open = false" class="flex w-full items-center gap-2 px-3 py-2 text-left text-xs font-semibold text-gray-700 hover:bg-[#fff7f7] hover:text-[#9c1c1c] rounded-lg">
                        <span>📕</span> Exportar a PDF
                    </button>
                </div>
            </div>

            <button wire:click="abrirFormulario"
                class="rounded-full bg-[#9c1c1c] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#7a1515]">
                + Registrar publicacion
            </button>
        </div>
    </div>

    <div class="mt-6 space-y-6">
        @if ($showForm)
            <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-[#2b2323]">
                        {{ $editingId ? 'Editar publicacion' : 'Nueva publicacion' }}
                    </h3>
                    <button type="button" wire:click="cerrarFormulario" class="text-sm text-[#9c1c1c]">
                        Cerrar
                    </button>
                </div>
                <div class="mt-6">
                    <livewire:publicaciones.formulario-publicacion :publication-id="$editingId" :key="'publication-form-' . ($editingId ?? 'nuevo')" />
                </div>
            </div>
        @endif

        <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-[#2b2323]">Publicaciones registradas</h3>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">
                        {{ $this->publications->total() }} registros</p>
                </div>
                <div class="w-full sm:w-72">
                    <x-text-input wire:model.live="search" type="search" placeholder="Buscar por titulo o autor"
                        class="w-full" />
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-[#f0dede]">
                <table class="min-w-full divide-y divide-[#f0dede] text-sm">
                    <thead
                        class="bg-[#fff7f7] text-left text-xs font-semibold uppercase tracking-[0.2em] text-[#7a1515]">
                        <tr>
                            <th class="px-4 py-3">Titulo</th>
                            <th class="px-4 py-3">Ano</th>
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3">Ambito</th>
                            <th class="px-4 py-3">Autores</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0dede] bg-white">
                        @forelse ($this->publications as $publication)
                                                <tr wire:key="publication-{{ $publication->publication_id }}" class="hover:bg-[#fff7f7]">
                                                    <td class="px-4 py-3 font-semibold text-[#2b2323]">{{ $publication->title }}</td>
                                                    <td class="px-4 py-3 text-slate-600">{{ $publication->publication_year ?? '—' }}</td>
                                                    <td class="px-4 py-3 text-slate-600">{{ $publication->type?->type_name ?? '—' }}</td>
                                                    <td class="px-4 py-3 text-slate-600">{{ $publication->scope ?? '—' }}</td>
                                                    <td class="px-4 py-3 text-slate-600">
                                                        @php
                                                            $authors = $publication->researchers->sortBy(function ($researcher) {
                                                                return $researcher->pivot->author_order ?? 999;
                                                            });
                                                        @endphp
                                                        {{ $authors->map(fn($researcher) => trim(implode(' ', array_filter([
                                $researcher->name_1,
                                $researcher->name_2,
                                $researcher->last_name_1,
                                $researcher->last_name_2,
                            ]))))->filter()->implode(', ') ?: '—' }}
                                                    </td>
                                                    <td class="px-4 py-3 text-right">
                                                        <div class="flex justify-end gap-2">
                                                            <button type="button" wire:click="editar({{ $publication->publication_id }})"
                                                                class="rounded-full border border-[#9c1c1c]/30 px-3 py-1 text-xs font-semibold text-[#7a1515] hover:bg-[#f2d3d3]">
                                                                Editar
                                                            </button>
                                                            <button type="button" wire:click="eliminar({{ $publication->publication_id }})"
                                                                onclick="confirm('Seguro que deseas eliminar esta publicacion?') || event.stopImmediatePropagation()"
                                                                class="rounded-full border border-[#d77a7a]/40 px-3 py-1 text-xs font-semibold text-[#9c1c1c] hover:bg-[#f9dede]">
                                                                Eliminar
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">
                                    No hay publicaciones registradas todavia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $this->publications->links() }}
            </div>
        </div>
    </div>
</div>