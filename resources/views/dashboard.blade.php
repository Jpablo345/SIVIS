<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#9c1c1c]">Dashboard</p>
                <h2 class="text-2xl font-semibold text-[#2b2323]">Produccion Cientifica 2024</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <button class="rounded-full border border-[#9c1c1c]/30 px-4 py-2 text-xs font-semibold text-[#7a1515] hover:bg-[#f2d3d3]">
                    + Registrar
                </button>
                <button class="rounded-full bg-[#9c1c1c] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#7a1515]">
                    Exportar
                </button>
            </div>
        </div>
    </x-slot>

    <div class="relative">
        <div class="pointer-events-none absolute -left-16 -top-12 h-40 w-40 rounded-full bg-[#f2d3d3] blur-2xl"></div>
        <div class="pointer-events-none absolute right-10 top-32 h-56 w-56 rounded-full bg-[#f7e2d2] blur-3xl"></div>

        <div class="relative grid gap-6">
            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-white/60 bg-white/80 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Publicaciones totales</p>
                    <div class="mt-3 flex items-end justify-between">
                        <span class="text-3xl font-semibold text-[#2b2323]">812</span>
                        <span class="rounded-full bg-[#f2d3d3] px-3 py-1 text-xs font-semibold text-[#7a1515]">+18% este ano</span>
                    </div>
                </div>
                <div class="rounded-2xl border border-white/60 bg-white/80 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Investigadores activos</p>
                    <div class="mt-3 flex items-end justify-between">
                        <span class="text-3xl font-semibold text-[#2b2323]">48</span>
                        <span class="rounded-full bg-[#f2d3d3] px-3 py-1 text-xs font-semibold text-[#7a1515]">6 grupos</span>
                    </div>
                </div>
                <div class="rounded-2xl border border-white/60 bg-white/80 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Articulos indexados</p>
                    <div class="mt-3 flex items-end justify-between">
                        <span class="text-3xl font-semibold text-[#2b2323]">201</span>
                        <span class="rounded-full bg-[#f2d3d3] px-3 py-1 text-xs font-semibold text-[#7a1515]">+12 Scopus / WOS</span>
                    </div>
                </div>
                <div class="rounded-2xl border border-white/60 bg-white/80 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Eventos registrados</p>
                    <div class="mt-3 flex items-end justify-between">
                        <span class="text-3xl font-semibold text-[#2b2323]">74</span>
                        <span class="rounded-full bg-[#f2d3d3] px-3 py-1 text-xs font-semibold text-[#7a1515]">Nacionales e internac.</span>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-[#2b2323]">Publicaciones recientes</h3>
                        <button class="rounded-full border border-[#9c1c1c]/30 px-3 py-1 text-xs font-semibold text-[#7a1515] hover:bg-[#f2d3d3]">
                            Ver todas
                        </button>
                    </div>
                    <div class="mt-4 space-y-4">
                        <div class="flex items-start gap-3 rounded-xl border border-[#f0dede] bg-[#fff7f7] p-4">
                            <span class="rounded-full bg-[#9c1c1c] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-white">Articulo</span>
                            <div>
                                <p class="text-sm font-semibold text-[#2b2323]">Machine learning applied to genomic classification models</p>
                                <p class="text-xs text-slate-500">Gomez, R. Nature Biotech. 2024</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-xl border border-[#f0dede] bg-[#fff7f7] p-4">
                            <span class="rounded-full bg-[#9c1c1c]/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-white">Libro</span>
                            <div>
                                <p class="text-sm font-semibold text-[#2b2323]">Fundamentos de epidemiologia computacional</p>
                                <p class="text-xs text-slate-500">Torres, M. Ed. Uniandes. 2024</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-xl border border-[#f0dede] bg-[#fff7f7] p-4">
                            <span class="rounded-full bg-[#9c1c1c]/70 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-white">Capitulo</span>
                            <div>
                                <p class="text-sm font-semibold text-[#2b2323]">Redes neuronales en diagnostico temprano</p>
                                <p class="text-xs text-slate-500">Rios, P. Springer. 2024</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-xl border border-[#f0dede] bg-[#fff7f7] p-4">
                            <span class="rounded-full bg-[#9c1c1c]/60 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-white">Evento</span>
                            <div>
                                <p class="text-sm font-semibold text-[#2b2323]">Ponencia CLONEMI 2024 - Cali, Colombia</p>
                                <p class="text-xs text-slate-500">Herrera, C. Octubre 2024</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/60 bg-white/80 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-[#2b2323]">Produccion por grupos</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between rounded-xl border border-[#f0dede] bg-white px-4 py-3">
                            <span class="text-sm font-semibold text-[#2b2323]">GIBio</span>
                            <span class="text-xs font-semibold text-[#7a1515]">32</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-[#f0dede] bg-white px-4 py-3">
                            <span class="text-sm font-semibold text-[#2b2323]">GISalud</span>
                            <span class="text-xs font-semibold text-[#7a1515]">27</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-[#f0dede] bg-white px-4 py-3">
                            <span class="text-sm font-semibold text-[#2b2323]">GIMatem</span>
                            <span class="text-xs font-semibold text-[#7a1515]">19</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-[#f0dede] bg-white px-4 py-3">
                            <span class="text-sm font-semibold text-[#2b2323]">GIHum</span>
                            <span class="text-xs font-semibold text-[#7a1515]">16</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-[#f0dede] bg-white px-4 py-3">
                            <span class="text-sm font-semibold text-[#2b2323]">GIEduc</span>
                            <span class="text-xs font-semibold text-[#7a1515]">12</span>
                        </div>
                    </div>
                    <div class="mt-6 rounded-xl bg-[#f7e2d2] px-4 py-3 text-xs font-semibold text-[#7a1515]">
                        Ultima actualizacion: hace 2 horas
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>