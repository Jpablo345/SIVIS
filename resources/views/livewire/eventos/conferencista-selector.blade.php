<div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
    <div>
        <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">Conferencistas</h4>
        <p class="text-xs text-slate-500">Busca por nombre o documento.</p>
    </div>

    <div class="mt-4 flex gap-2">
        <div class="flex-1">
            <x-text-input wire:model.live="speakerSearch" type="search" class="w-full"
                placeholder="Buscar investigador..." />
        </div>
        <button type="button" wire:click="openResearcherModal"
            class="rounded-full bg-[#9c1c1c] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#7a1515] whitespace-nowrap">
            + Nuevo
        </button>
    </div>

    @if ($speakerResults)
        <div class="mt-3 space-y-1 rounded-xl border border-[#f0dede] bg-white p-3">
            @foreach ($speakerResults as $result)
                <div class="flex items-center justify-between rounded-lg px-3 py-2 text-sm">
                    <div>
                        <div class="font-semibold text-[#2b2323]">{{ $result['name'] }}</div>
                        <div class="text-xs text-slate-500">{{ $result['group'] ?? 'Sin grupo' }}</div>
                    </div>
                    <button type="button" wire:click="agregar({{ $result['researcher_id'] }})"
                        class="rounded-full bg-[#9c1c1c]/10 px-3 py-1 text-xs font-semibold text-[#9c1c1c] hover:bg-[#f2d3d3]">
                        + Agregar
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-4 space-y-2">
        @forelse ($selectedSpeakers as $index => $speaker)
            <div class="flex items-center justify-between rounded-xl border border-[#f0dede] bg-white px-4 py-3">
                <div class="min-w-0">
                    <div class="font-semibold text-[#2b2323]">{{ $index + 1 }}. {{ $speaker['name'] }}</div>
                    <div class="text-xs text-slate-500">{{ $speaker['group'] ?? 'Sin grupo' }}</div>
                </div>
                <button type="button" wire:click="quitar({{ $index }})"
                    class="shrink-0 rounded-full border border-[#d77a7a]/40 px-3 py-1 text-xs font-semibold text-[#9c1c1c] hover:bg-[#f9dede]">
                    Quitar
                </button>
            </div>
        @empty
            <div
                class="rounded-xl border border-dashed border-[#f0dede] bg-white/60 px-4 py-6 text-center text-sm text-slate-500">
                No has agregado conferencistas todavía.
            </div>
        @endforelse
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
    MODAL PARA CREAR INVESTIGADOR (copiado de AutoresSelector)
    ═══════════════════════════════════════════════════════════════════ --}}
    @if ($showResearcherModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 overflow-y-auto">
            <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                    <h3 class="text-lg font-semibold text-[#2b2323]">Nuevo Investigador</h3>
                    <button type="button" wire:click="$set('showResearcherModal', false)"
                        class="rounded-full p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    {{-- Datos personales --}}
                    <div class="rounded-lg bg-slate-50 p-4">
                        <h4 class="mb-3 text-sm font-semibold text-[#9c1c1c]">Datos personales</h4>
                        <div class="grid gap-3 md:grid-cols-2">
                            <div>
                                <x-input-label value="Primer nombre *" class="text-xs font-semibold" />
                                <x-text-input wire:model="modal_name_1" type="text" class="mt-1 w-full" />
                                <x-input-error :messages="$errors->get('modal_name_1')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label value="Segundo nombre" class="text-xs font-semibold" />
                                <x-text-input wire:model="modal_name_2" type="text" class="mt-1 w-full" />
                            </div>
                            <div>
                                <x-input-label value="Primer apellido *" class="text-xs font-semibold" />
                                <x-text-input wire:model="modal_last_name_1" type="text" class="mt-1 w-full" />
                                <x-input-error :messages="$errors->get('modal_last_name_1')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label value="Segundo apellido" class="text-xs font-semibold" />
                                <x-text-input wire:model="modal_last_name_2" type="text" class="mt-1 w-full" />
                            </div>
                            <div>
                                <x-input-label value="Documento / Cédula" class="text-xs font-semibold" />
                                <x-text-input wire:model="modal_document" type="text" class="mt-1 w-full" />
                                <x-input-error :messages="$errors->get('modal_document')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    {{-- Grupo de investigación --}}
                    <div class="rounded-lg bg-slate-50 p-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-[#9c1c1c]">Grupo de investigación</h4>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" wire:model.live="modal_create_group"
                                    class="rounded border-red-300" />
                                <span class="text-xs text-slate-600">Crear nuevo grupo</span>
                            </label>
                        </div>

                        @if($modal_create_group)
                            <div class="mt-3 grid gap-3 md:grid-cols-2">
                                <div>
                                    <x-input-label value="Código MinCiencias *" class="text-xs font-semibold" />
                                    <x-text-input wire:model="modal_group_code" type="text" class="mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('modal_group_code')" class="mt-1" />
                                </div>
                                <div>
                                    <x-input-label value="Nombre del grupo *" class="text-xs font-semibold" />
                                    <x-text-input wire:model="modal_group_name" type="text" class="mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('modal_group_name')" class="mt-1" />
                                </div>
                                <div>
                                    <x-input-label value="Clasificación" class="text-xs font-semibold" />
                                    <x-text-input wire:model="modal_group_classification" type="text" class="mt-1 w-full" />
                                </div>
                            </div>
                        @else
                            <div class="mt-3">
                                <x-input-label value="Seleccionar grupo existente *" class="text-xs font-semibold" />
                                <select wire:model="modal_cod_minciencias" class="mt-1 w-full rounded-md border-red-200">
                                    <option value="">Seleccionar grupo...</option>
                                    @foreach($this->groups as $group)
                                        <option value="{{ $group->cod_minciencias }}">{{ $group->group_name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('modal_cod_minciencias')" class="mt-1" />
                            </div>
                        @endif
                    </div>

                    {{-- Institución --}}
                    <div class="rounded-lg bg-slate-50 p-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-[#9c1c1c]">Institución</h4>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" wire:model.live="modal_create_institution"
                                    class="rounded border-red-300" />
                                <span class="text-xs text-slate-600">Crear nueva institución</span>
                            </label>
                        </div>

                        @if($modal_create_institution)
                            <div class="mt-3 grid gap-3 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <x-input-label value="Nombre de la institución *" class="text-xs font-semibold" />
                                    <x-text-input wire:model="modal_institution_name" type="text" class="mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('modal_institution_name')" class="mt-1" />
                                </div>
                                <div>
                                    <x-input-label value="Tipo" class="text-xs font-semibold" />
                                    <select wire:model="modal_institution_type" class="mt-1 w-full rounded-md border-red-200">
                                        <option value="">Seleccionar...</option>
                                        <option value="Universidad">Universidad</option>
                                        <option value="Instituto">Instituto</option>
                                        <option value="Centro de investigación">Centro de investigación</option>
                                        <option value="Empresa">Empresa</option>
                                        <option value="ONG">ONG</option>
                                        <option value="Gobierno">Gobierno</option>
                                    </select>
                                </div>

                                {{-- Campo PAÍS con datalist --}}
                                <div>
                                    <x-input-label value="País" class="text-xs font-semibold" />

                                    @php
                                        $paises = config('paises');
                                        if ($paises)
                                            ksort($paises);
                                    @endphp

                                    <x-text-input wire:model="modal_institution_country" list="lista-paises-conferencista"
                                        type="text" class="mt-1 w-full" placeholder="Escribe para buscar un país..."
                                        autocomplete="off" />

                                    <datalist id="lista-paises-conferencista">
                                        @if($paises)
                                            @foreach($paises as $nombre => $codigo)
                                                <option value="{{ $nombre }}"></option>
                                            @endforeach
                                        @endif
                                    </datalist>
                                </div>

                                <div>
                                    <x-input-label value="Ciudad" class="text-xs font-semibold" />
                                    <x-text-input wire:model="modal_institution_city" type="text" class="mt-1 w-full" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label value="Sitio web" class="text-xs font-semibold" />
                                    <x-text-input wire:model="modal_institution_website" type="url" class="mt-1 w-full" />
                                </div>
                            </div>
                        @else
                            <div class="mt-3">
                                <x-input-label value="Seleccionar institución existente" class="text-xs font-semibold" />
                                <select wire:model="modal_institution_id" class="mt-1 w-full rounded-md border-red-200">
                                    <option value="">Seleccionar institución...</option>
                                    @foreach($this->institutions as $institution)
                                        <option value="{{ $institution->institution_id }}">{{ $institution->institution_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex justify-end gap-2 border-t border-slate-200 pt-4">
                        <button type="button" wire:click="$set('showResearcherModal', false)"
                            class="rounded-full border border-[#9c1c1c]/30 px-4 py-2 text-xs font-semibold text-[#7a1515] hover:bg-[#f2d3d3]">
                            Cancelar
                        </button>
                        <button type="button" wire:click="crearYSeleccionar"
                            class="rounded-full bg-[#9c1c1c] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#7a1515]">
                            Crear y agregar
                        </button>
                    </div>
                </div>
            </div>
    @endif
    </div>