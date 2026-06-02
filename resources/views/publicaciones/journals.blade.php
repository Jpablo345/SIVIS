<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#9c1c1c]">Publicaciones</p>
            <h2 class="text-2xl font-semibold text-[#2b2323]">Revistas</h2>
        </div>
    </div>

    <div class="mt-6 space-y-6">
        {{-- Formulario --}}
        <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-[#2b2323]">
                        {{ $editingId ? 'Editar revista' : 'Nueva revista' }}
                    </h3>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">
                        Registra revistas por ISSN y nombre
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
                        {{ $editingId ? 'Guardar cambios' : 'Crear revista' }}
                    </button>
                </div>
            </div>

            @if (session('status'))
                <div class="mt-4 rounded-xl bg-[#f7e2d2] px-4 py-3 text-xs font-semibold text-[#7a1515]">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="journal_issn" :value="'ISSN'" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="journal_issn" wire:model="journal_issn" type="text"
                        class="mt-2 block w-full {{ $editingId ? 'bg-slate-100 cursor-not-allowed' : '' }}"
                        :disabled="(bool) $editingId" />
                    <x-input-error :messages="$errors->get('journal_issn')" class="mt-2" />
                    @if ($editingId)
                        <p class="mt-1 text-xs text-slate-400">El ISSN no se puede modificar.</p>
                    @endif
                </div>
                <div>
                    <x-input-label for="journal_name" :value="'Nombre de la revista'" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="journal_name" wire:model="journal_name" type="text" class="mt-2 block w-full" />
                    <x-input-error :messages="$errors->get('journal_name')" class="mt-2" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="category" :value="'Categoria (Q1, Q2, etc.)'" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="category" wire:model="category" type="text" class="mt-2 block w-full" />
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </div>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-[#2b2323]">Revistas registradas</h3>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">{{ $this->journals->total() }} registros</p>
                </div>
                <div class="w-full sm:w-72">
                    <x-text-input wire:model.live="search" type="search" placeholder="Buscar por nombre o ISSN" class="w-full" />
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-[#f0dede]">
                <table class="min-w-full divide-y divide-[#f0dede] text-sm">
                    <thead class="bg-[#fff7f7] text-left text-xs font-semibold uppercase tracking-[0.2em] text-[#7a1515]">
                        <tr>
                            <th class="px-4 py-3">ISSN</th>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Categoria</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0dede] bg-white">
                        @forelse ($this->journals as $journal)
                            <tr wire:key="journal-{{ $journal->journal_issn }}" class="hover:bg-[#fff7f7]">
                                <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $journal->journal_issn }}</td>
                                <td class="px-4 py-3 font-semibold text-[#2b2323]">{{ $journal->journal_name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $journal->category ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" wire:click="edit('{{ $journal->journal_issn }}')"
                                            class="rounded-full border border-[#9c1c1c]/30 px-3 py-1 text-xs font-semibold text-[#7a1515] hover:bg-[#f2d3d3]">
                                            Editar
                                        </button>
                                        <button type="button" wire:click="delete('{{ $journal->journal_issn }}')"
                                            onclick="confirm('Seguro que deseas eliminar esta revista?') || event.stopImmediatePropagation()"
                                            class="rounded-full border border-[#d77a7a]/40 px-3 py-1 text-xs font-semibold text-[#9c1c1c] hover:bg-[#f9dede]">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">
                                    No hay revistas registradas todavia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $this->journals->links() }}
            </div>
        </div>
    </div>
</div>