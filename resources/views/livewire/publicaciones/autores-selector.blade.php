<div>
    {{-- CONTENEDOR DE AUTORES --}}
    <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">Autores</h4>
                <p class="text-xs text-slate-500">Busca por nombre o documento.</p>
            </div>
            <button type="button" x-on:click="$dispatch('open-modal', 'crear-investigador-modal')"
                class="rounded-full border border-[#9c1c1c]/30 px-4 py-2 text-xs font-semibold text-[#7a1515] hover:bg-[#f2d3d3]">
                + Crear investigador
            </button>
        </div>

        <div class="mt-4">
            <x-text-input wire:model.live="authorSearch" type="search" class="w-full"
                placeholder="Buscar investigador" />
        </div>

        @if (!empty($authorResults))
            <div class="mt-3 space-y-2 rounded-xl border border-[#f0dede] bg-white p-3">
                @foreach ($authorResults as $result)
                    <div class="flex items-center justify-between rounded-lg px-3 py-2 text-sm">
                        <div>
                            <div class="font-semibold text-[#2b2323]">{{ $result['name'] }}</div>
                            <div class="text-xs text-slate-500">{{ $result['group'] ?? 'Sin grupo' }}</div>
                        </div>
                        <button type="button" wire:click="agregarAutor('{{ $result['researcher_id'] }}')"
                            class="rounded-full bg-[#9c1c1c]/10 px-3 py-1 text-xs font-semibold text-[#9c1c1c] hover:bg-[#f2d3d3]">
                            + Agregar
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4 space-y-2">
            @forelse ($selectedAuthors as $index => $author)
                <div
                    class="flex items-center justify-between rounded-xl border border-[#f0dede] bg-white px-4 py-3 text-sm">
                    <div>
                        <div class="font-semibold text-[#2b2323]">{{ $index + 1 }}. {{ $author['name'] }}</div>
                        <div class="text-xs text-slate-500">{{ $author['group'] ?? 'Sin grupo' }}</div>
                    </div>
                    <button type="button" wire:click="eliminarAutor({{ $index }})"
                        class="rounded-full border border-[#d77a7a]/40 px-3 py-1 text-xs font-semibold text-[#9c1c1c] hover:bg-[#f9dede]">
                        Quitar
                    </button>
                </div>
            @empty
                <div
                    class="rounded-xl border border-dashed border-[#f0dede] bg-white/60 px-4 py-6 text-center text-sm text-slate-500">
                    No has agregado autores todavía.
                </div>
            @endforelse
        </div>
    </div>

    {{-- MODAL CREAR INVESTIGADOR --}}
    <x-modal name="crear-investigador-modal" maxWidth="2xl">
        <div class="bg-white p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-[#2b2323]">Crear investigador</h3>
                <button type="button" x-on:click="$dispatch('close-modal', 'crear-investigador-modal')"
                    class="text-sm text-[#9c1c1c]">Cerrar</button>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="modal_document" :value="'Documento (opcional)'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="modal_document" wire:model="modal_document" type="text" class="mt-2 block w-full"
                        placeholder="Cédula, pasaporte..." />
                    <x-input-error :messages="$errors->get('modal_document')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="modal_name_1" :value="'Primer nombre'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="modal_name_1" wire:model="modal_name_1" type="text" class="mt-2 block w-full" />
                    <x-input-error :messages="$errors->get('modal_name_1')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="modal_last_name_1" :value="'Primer apellido'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="modal_last_name_1" wire:model="modal_last_name_1" type="text"
                        class="mt-2 block w-full" />
                    <x-input-error :messages="$errors->get('modal_last_name_1')" class="mt-2" />
                </div>
                <div class="flex items-center gap-2 pt-6">
                    <input id="modal_create_group_selector" type="checkbox" wire:model.live="modal_create_group"
                        class="rounded border-red-200 text-red-600 focus:ring-red-500" />
                    <label for="modal_create_group_selector" class="text-sm text-slate-600">Crear grupo nuevo</label>
                </div>
            </div>

            @if (!$modal_create_group)
                <div class="mt-4">
                    <x-input-label for="modal_cod_minciencias" :value="'Grupo existente'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <select id="modal_cod_minciencias" wire:model="modal_cod_minciencias"
                        class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                        <option value="">Selecciona un grupo</option>
                        @foreach ($this->groups as $group)
                            <option value="{{ $group->cod_minciencias }}">{{ $group->group_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('modal_cod_minciencias')" class="mt-2" />
                </div>
            @endif

            @if ($modal_create_group)
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <x-input-label for="modal_group_code" :value="'Código Minciencias'"
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                        <x-text-input id="modal_group_code" wire:model="modal_group_code" type="text"
                            class="mt-2 block w-full" />
                        <x-input-error :messages="$errors->get('modal_group_code')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="modal_group_classification" :value="'Clasificación'"
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                        <x-text-input id="modal_group_classification" wire:model="modal_group_classification" type="text"
                            class="mt-2 block w-full" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="modal_group_name" :value="'Nombre del grupo'"
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                        <x-text-input id="modal_group_name" wire:model="modal_group_name" type="text"
                            class="mt-2 block w-full" />
                        <x-input-error :messages="$errors->get('modal_group_name')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <input id="modal_create_institution_selector" type="checkbox" wire:model.live="modal_create_institution"
                        class="rounded border-red-200 text-red-600 focus:ring-red-500" />
                    <label for="modal_create_institution_selector" class="text-sm text-slate-600">Crear institución
                        nueva</label>
                </div>

                @if (!$modal_create_institution)
                    <div class="mt-4">
                        <x-input-label for="modal_institution_id" :value="'Institución existente'"
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                        <select id="modal_institution_id" wire:model.defer="modal_institution_id"
                            class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                            <option value="">Sin asignar</option>
                            @foreach ($this->institutions as $institution)
                                <option value="{{ $institution->institution_id }}">{{ $institution->institution_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if ($modal_create_institution)
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <x-input-label for="modal_institution_name" :value="'Nombre de institución'"
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                            <x-text-input id="modal_institution_name" wire:model.defer="modal_institution_name" type="text"
                                class="mt-2 block w-full" />
                            <x-input-error :messages="$errors->get('modal_institution_name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="modal_institution_type" :value="'Tipo'"
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                            <x-text-input id="modal_institution_type" wire:model.defer="modal_institution_type" type="text"
                                class="mt-2 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="modal_institution_country" :value="'País'"
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                            <x-text-input id="modal_institution_country" wire:model.defer="modal_institution_country"
                                type="text" class="mt-2 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="modal_institution_city" :value="'Ciudad'"
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                            <x-text-input id="modal_institution_city" wire:model.defer="modal_institution_city" type="text"
                                class="mt-2 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="modal_institution_website" :value="'Sitio web'"
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                            <x-text-input id="modal_institution_website" wire:model.defer="modal_institution_website" type="url"
                                class="mt-2 block w-full" placeholder="https://" />
                        </div>
                    </div>
                @endif
            @endif

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" x-on:click="$dispatch('close-modal', 'crear-investigador-modal')"
                    class="rounded-full border border-[#9c1c1c]/30 px-4 py-2 text-xs font-semibold text-[#7a1515] hover:bg-[#f2d3d3]">
                    Cancelar
                </button>
                <button type="button" wire:click.prevent="crearYSeleccionar"
                    class="rounded-full bg-[#9c1c1c] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#7a1515]">
                    Guardar investigador
                </button>
            </div>
        </div>
    </x-modal>
</div>