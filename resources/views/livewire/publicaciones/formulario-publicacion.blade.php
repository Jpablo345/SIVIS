<form wire:submit.prevent="guardar" class="space-y-6">
    <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-[#2b2323]">Formulario de publicacion</h3>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">
                    Campos comunes y especificos por tipo
                </p>
            </div>
            <button type="submit"
                class="rounded-full bg-[#9c1c1c] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#7a1515]">
                Guardar publicacion
            </button>
        </div>

        @if (session('status'))
            <div class="mt-4 rounded-xl bg-[#f7e2d2] px-4 py-3 text-xs font-semibold text-[#7a1515]">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <x-input-label for="title" :value="'Titulo'"
                    class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <x-text-input id="title" wire:model.defer="title" type="text" class="mt-2 block w-full" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="publication_year" :value="'Ano'"
                    class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <x-text-input id="publication_year" wire:model.defer="publication_year" type="text"
                    class="mt-2 block w-full" />
                <x-input-error :messages="$errors->get('publication_year')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="scope" :value="'Ambito'"
                    class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <select id="scope" wire:model.defer="scope"
                    class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                    <option value="">Sin definir</option>
                    <option value="Nacional">Nacional</option>
                    <option value="Internacional">Internacional</option>
                </select>
                <x-input-error :messages="$errors->get('scope')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="country_publication" :value="'Pais'"
                    class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />

                @php
                    //Llama al archivo config/paises.php
                    $paises = config('paises');
                    if ($paises)
                        ksort($paises); // Ordena alfabéticamente
                @endphp

                <x-text-input id="country_publication" list="lista-paises" wire:model="country_publication" type="text"
                    class="mt-2 block w-full" placeholder="Escribe para buscar un país..." autocomplete="off" />

                <datalist id="lista-paises">
                    @if($paises)
                        @foreach($paises as $nombre => $codigo)
                            <option value="{{ $nombre }}"></option>
                        @endforeach
                    @endif
                </datalist>

                <x-input-error :messages="$errors->get('country_publication')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="type_id" :value="'Tipo'"
                    class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <select id="type_id" wire:model.live="type_id"
                    class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                    <option value="">Selecciona un tipo</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('type_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="url" :value="'URL'"
                    class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <x-text-input id="url" wire:model.defer="url" type="url" class="mt-2 block w-full"
                    placeholder="https://" />
                <x-input-error :messages="$errors->get('url')" class="mt-2" />
            </div>
        </div>
    </div>

    @if ($this->isArticleType())
        <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">Datos de articulo</h4>
            </div>

            <div class="mt-4">
                <x-input-label for="journal_search" :value="'Revista (ISSN o nombre)'"
                    class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                <x-text-input id="journal_search" wire:model.live="journalSearch" type="text" class="mt-2 block w-full" />
                <x-input-error :messages="$errors->get('journal_issn')" class="mt-2" />

                @if ($journalResults)
                    <div class="mt-3 space-y-2 rounded-xl border border-[#f0dede] bg-white p-3">
                        @foreach ($journalResults as $journal)
                            <button type="button" wire:click="seleccionarRevista('{{ $journal['journal_issn'] }}')"
                                class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-[#fff7f7]">
                                <span>{{ $journal['journal_name'] }}</span>
                                <span class="text-xs text-slate-400">{{ $journal['journal_issn'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($this->isBookType())
        <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">Datos de libro</h4>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="book_isbn" :value="'ISBN'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="book_isbn" wire:model.defer="book_isbn" type="text" class="mt-2 block w-full" />
                    <x-input-error :messages="$errors->get('book_isbn')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="book_type_id" :value="'Tipo de libro'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <select id="book_type_id" wire:model.defer="book_type_id"
                        class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                        <option value="">Selecciona un tipo</option>
                        @foreach ($bookTypes as $bookType)
                            <option value="{{ $bookType->book_type_id }}">{{ $bookType->type_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('book_type_id')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="means_of_dissemination" :value="'Medio de difusion'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <select id="means_of_dissemination" wire:model="means_of_dissemination"
                        class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                        <option value="">Selecciona un medio</option>
                        <option value="Electronico">Electronico</option>
                        <option value="Papel">Papel</option>
                    </select>
                    <x-input-error :messages="$errors->get('means_of_dissemination')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="editorial" :value="'Editorial'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="editorial" wire:model.defer="editorial" type="text" class="mt-2 block w-full" />
                    <x-input-error :messages="$errors->get('editorial')" class="mt-2" />
                </div>
            </div>
        </div>
    @endif

    <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-[#9c1c1c]">Autores</h4>
                <p class="text-xs text-slate-500">Busca por nombre o documento.</p>
            </div>
            <button type="button" x-on:click="$dispatch('open-modal', 'crear-investigador')"
                class="rounded-full border border-[#9c1c1c]/30 px-4 py-2 text-xs font-semibold text-[#7a1515] hover:bg-[#f2d3d3]">
                + Crear investigador
            </button>
        </div>

        <div class="mt-4">
            <x-text-input wire:model.live="authorSearch" type="search" class="w-full"
                placeholder="Buscar investigador" />
        </div>

        @if ($authorResults)
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
                    No has agregado autores todavia.
                </div>
            @endforelse
        </div>
    </div>

    <x-modal name="crear-investigador" maxWidth="2xl">
        <div class="bg-white p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-[#2b2323]">Crear investigador</h3>
                <button type="button" x-on:click="$dispatch('close-modal', 'crear-investigador')"
                    class="text-sm text-[#9c1c1c]">Cerrar</button>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="modal_document" :value="'Documento (opcional)'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="modal_document" wire:model="modal_document" type="text" class="mt-2 block w-full"
                        placeholder="Cedula, pasaporte..." />
                    <x-input-error :messages="$errors->get('modal_document')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="modal_name_1" :value="'Primer nombre'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="modal_name_1" wire:model="modal_name_1" type="text" class="mt-2 block w-full" />
                    <x-input-error :messages="$errors->get('modal_name_1')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="modal_name_2" :value="'Segundo nombre'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="modal_name_2" wire:model="modal_name_2" type="text" class="mt-2 block w-full" />
                    <x-input-error :messages="$errors->get('modal_name_2')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="modal_last_name_1" :value="'Primer apellido'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="modal_last_name_1" wire:model="modal_last_name_1" type="text"
                        class="mt-2 block w-full" />
                    <x-input-error :messages="$errors->get('modal_last_name_1')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="modal_last_name_2" :value="'Segundo apellido'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <x-text-input id="modal_last_name_2" wire:model="modal_last_name_2" type="text"
                        class="mt-2 block w-full" />
                    <x-input-error :messages="$errors->get('modal_last_name_2')" class="mt-2" />
                </div>

                <div class="flex items-center gap-2 pt-6">
                    <input id="modal_create_group" type="checkbox" wire:model.live="modal_create_group"
                        class="rounded border-red-200 text-red-600 focus:ring-red-500" />
                    <label for="modal_create_group" class="text-sm text-slate-600">Crear grupo nuevo</label>
                </div>
            </div>

            @if (!$modal_create_group)
                <div class="mt-4">
                    <x-input-label for="modal_cod_minciencias" :value="'Grupo existente'"
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                    <select id="modal_cod_minciencias" wire:model="modal_cod_minciencias"
                        class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                        <option value="">Selecciona un grupo</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->cod_minciencias }}">{{ $group->group_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('modal_cod_minciencias')" class="mt-2" />
                </div>
            @endif

            @if ($modal_create_group)
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <x-input-label for="modal_group_code" :value="'Codigo Minciencias'"
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                        <x-text-input id="modal_group_code" wire:model="modal_group_code" type="text"
                            class="mt-2 block w-full" />
                        <x-input-error :messages="$errors->get('modal_group_code')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="group_classification" :value="'Clasificacion'"
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                        <select id="group_classification" wire:model="group_classification"
                            class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                            <option value="">Sin clasificacion</option>
                            <option value="Categoría A1">Categoría A1</option>
                            <option value="Categoría A">Categoría A</option>
                            <option value="Categoría B">Categoría B</option>
                            <option value="Categoría C">Categoría C</option>
                            <option value="Categoría D">Categoría D</option>
                            <option value="Institucional">Institucional</option>
                        </select>
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
                    <input id="modal_create_institution" type="checkbox" wire:model.live="modal_create_institution"
                        class="rounded border-red-200 text-red-600 focus:ring-red-500" />
                    <label for="modal_create_institution" class="text-sm text-slate-600">Crear institución
                        nueva</label>
                </div>
                @if (!$modal_create_institution)
                    <div class="mt-4">
                        <x-input-label for="modal_institution_id" :value="'Institucion existente'"
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />
                        <select id="modal_institution_id" wire:model.defer="modal_institution_id"
                            class="mt-2 block w-full rounded-md border-red-200 bg-white text-zinc-900 shadow-sm transition-colors focus:border-red-600 focus:ring-red-600">
                            <option value="">Sin asignar</option>
                            @foreach ($institutions as $institution)
                                <option value="{{ $institution->institution_id }}">{{ $institution->institution_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if ($modal_create_institution)
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <x-input-label for="modal_institution_name" :value="'Nombre de institucion'"
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
                            <x-input-label for="country_publication" :value="'Pais'"
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600" />

                            @php
                                //Llama al archivo config/paises.php
                                $paises = config('paises');
                                if ($paises)
                                    ksort($paises); // Ordena alfabéticamente
                            @endphp

                            <x-text-input id="country_publication" list="lista-paises" wire:model="country_publication"
                                type="text" class="mt-2 block w-full" placeholder="Escribe para buscar un país..."
                                autocomplete="off" />

                            <datalist id="lista-paises">
                                @if($paises)
                                    @foreach($paises as $nombre => $codigo)
                                        <option value="{{ $nombre }}"></option>
                                    @endforeach
                                @endif
                            </datalist>

                            <x-input-error :messages="$errors->get('country_publication')" class="mt-2" />
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
                <button type="button" x-on:click="$dispatch('close-modal', 'crear-investigador')"
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
</form>