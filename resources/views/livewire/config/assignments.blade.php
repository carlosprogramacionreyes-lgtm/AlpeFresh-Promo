<section class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200">
                Configuración · Asignaciones
            </span>
            <h1 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">
                Controla qué promotores pueden visitar cada tienda
            </h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                Administra las relaciones entre promotores y tiendas. Las asignaciones determinan la visibilidad de tiendas en la app del promotor y bloquean evaluaciones no autorizadas.
            </p>
        </div>
        <button
            type="button"
            wire:click="openModal"
            class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300 dark:bg-emerald-500/80 dark:hover:bg-emerald-400"
        >
            <i data-lucide="plus" class="h-4 w-4"></i>
            Asignar
        </button>
    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                    Asignaciones activas
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Cada promotor solo verá las tiendas listadas aquí cuando registre una nueva visita.
                </p>
            </div>
            <div class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-100">
                {{ $assignments->sum(fn ($group) => count($group['assignments'])) }} tiendas asignadas
            </div>
        </div>

        <div class="mt-6 space-y-4">
            @forelse ($assignments as $group)
                <article class="rounded-2xl border border-slate-200/70 bg-white/80 p-5 shadow-sm transition hover:border-emerald-200/60 hover:shadow-md dark:border-slate-700 dark:bg-slate-900/70 dark:hover:border-emerald-500/30">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                {{ $group['name'] }}
                            </h3>
                            <p class="text-xs uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                {{ '@' . $group['username'] }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                wire:click="editPromoterAssignments({{ $group['id'] }})"
                                class="inline-flex items-center gap-1 rounded-xl border border-emerald-200/70 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-50 dark:border-emerald-500/30 dark:text-emerald-200 dark:hover:border-emerald-500/50 dark:hover:bg-emerald-500/10"
                            >
                                <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                Editar
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($group['assignments'] as $assignment)
                            <div
                                wire:key="assignment-{{ $assignment['id'] }}"
                                class="flex items-start justify-between rounded-2xl border border-slate-200/60 bg-white/70 px-4 py-3 text-sm text-slate-600 shadow-sm dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-300"
                            >
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-slate-100">
                                        {{ $assignment['store_name'] ?? 'Tienda eliminada' }}
                                    </p>
                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-400 dark:text-slate-500">
                                        @if ($assignment['chain_name'])
                                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                <i data-lucide="layers" class="h-3 w-3"></i>
                                                {{ $assignment['chain_name'] }}
                                            </span>
                                        @endif
                                        @if ($assignment['zone_name'])
                                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                <i data-lucide="map-pin" class="h-3 w-3"></i>
                                                {{ $assignment['zone_name'] }}
                                            </span>
                                        @endif
                                        @if ($assignment['assigned_at'])
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-medium uppercase tracking-wide text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-200">
                                                <i data-lucide="calendar" class="h-3 w-3"></i>
                                                {{ $assignment['assigned_at'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    x-data
                                    x-on:click="if (confirm('¿Eliminar esta asignación?')) { $wire.removeAssignment({{ $assignment['id'] }}); }"
                                    class="rounded-lg p-2 text-slate-400 transition hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-200 dark:text-slate-500 dark:hover:text-red-300 dark:focus:ring-red-500/40"
                                    title="Eliminar asignación"
                                >
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300/80 bg-slate-50/70 p-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-400">
                    <i data-lucide="target" class="mx-auto h-10 w-10 text-slate-300 dark:text-slate-600"></i>
                    <p class="mt-3 font-medium text-slate-600 dark:text-slate-300">No hay asignaciones registradas.</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                        Usa el botón <strong>Asignar</strong> para enlazar promotores con tiendas específicas.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    @if ($showModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4 py-10 backdrop-blur-sm dark:bg-slate-950/60"
            x-data
            x-on:keydown.escape.window="$wire.closeModal()"
        >
            <div class="relative w-full max-w-3xl rounded-3xl border border-slate-200/70 bg-white/95 shadow-xl dark:border-slate-700 dark:bg-slate-900/95">
                <div class="flex items-center justify-between border-b border-slate-200/70 px-6 py-4 dark:border-slate-700">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">
                            Asignaciones de tiendas
                        </p>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                            Paso {{ $modalStep }} de 2
                        </h2>
                    </div>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="rounded-full p-2 text-slate-400 transition hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-200 dark:text-slate-500 dark:hover:text-slate-300 dark:focus:ring-slate-700/60"
                    >
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveAssignments" class="px-6 py-6">
                    <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-slate-600">
                        <div @class([
                            'flex h-8 w-8 items-center justify-center rounded-full border',
                            $modalStep >= 1 ? 'border-emerald-400 bg-emerald-500 text-white' : 'border-slate-200 bg-slate-100 text-slate-500',
                        ])>1</div>
                        <span class="{{ $modalStep === 1 ? 'text-emerald-500' : '' }}">Promotor</span>
                        <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                        <div @class([
                            'flex h-8 w-8 items-center justify-center rounded-full border',
                            $modalStep === 2 ? 'border-emerald-400 bg-emerald-500 text-white' : 'border-slate-200 bg-slate-100 text-slate-500',
                        ])>2</div>
                        <span class="{{ $modalStep === 2 ? 'text-emerald-500' : '' }}">Tiendas</span>
                    </div>

                    <div class="mt-6 space-y-6">
                        @if ($modalStep === 1)
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300">
                                        Buscar promotor
                                    </label>
                                    <input
                                        type="search"
                                        wire:model.live.debounce.400ms="promoterSearch"
                                        class="mt-1 w-full rounded-2xl border border-slate-200/70 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                                        placeholder="Nombre o usuario"
                                    >
                                </div>

                                <div class="max-h-72 space-y-3 overflow-y-auto pr-1">
                                    @forelse ($promoters as $promoter)
                                        <label
                                            wire:key="promoter-{{ $promoter->id }}"
                                            class="flex cursor-pointer items-center justify-between rounded-2xl border border-slate-200/60 bg-white px-4 py-3 text-sm shadow-sm transition hover:border-emerald-300 hover:bg-emerald-50/60 dark:border-slate-700 dark:bg-slate-900/70 dark:hover:border-emerald-500/40 dark:hover:bg-emerald-500/10"
                                        >
                                            <div>
                                                <p class="font-semibold text-slate-800 dark:text-slate-100">
                                                    {{ $promoter->full_name }}
                                                </p>
                                                <span class="text-xs uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                                    {{ '@' . $promoter->username }}
                                                </span>
                                            </div>
                                            <input
                                                type="radio"
                                                wire:model="selectedPromoterId"
                                                value="{{ $promoter->id }}"
                                                class="h-4 w-4 border-slate-300 text-emerald-500 focus:ring-emerald-400 dark:border-slate-600 dark:bg-slate-900"
                                            >
                                        </label>
                                    @empty
                                        <div class="rounded-2xl border border-dashed border-slate-300/70 px-4 py-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                            No se encontraron promotores activos.
                                        </div>
                                    @endforelse
                                </div>
                                @error('selectedPromoterId')
                                    <p class="text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="space-y-4">
                                <div class="rounded-2xl border border-emerald-200/60 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-700 shadow-sm dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-100">
                                    Gestionando tiendas para <strong>{{ optional($promoters->firstWhere('id', $selectedPromoterId))->full_name }}</strong>
                                    <span class="ml-2 text-xs uppercase tracking-wide text-emerald-600/80 dark:text-emerald-200/80">
                                        {{ '@' . optional($promoters->firstWhere('id', $selectedPromoterId))->username }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex-1">
                                        <input
                                            type="search"
                                            wire:model.live.debounce.300ms="storeSearch"
                                            class="w-full rounded-2xl border border-slate-200/70 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                                            placeholder="Buscar tienda por nombre o ciudad"
                                        >
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            wire:click="selectAllStores"
                                            class="rounded-xl border border-emerald-200/70 px-3 py-1.5 text-xs font-semibold text-emerald-600 transition hover:border-emerald-300 hover:bg-emerald-50 dark:border-emerald-500/40 dark:text-emerald-200 dark:hover:bg-emerald-500/10"
                                        >
                                            Seleccionar todas
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="clearStoreSelection"
                                            class="rounded-xl border border-slate-200/70 px-3 py-1.5 text-xs font-semibold text-slate-500 transition hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800/70"
                                        >
                                            Limpiar selección
                                        </button>
                                    </div>
                                </div>

                                <div class="max-h-80 space-y-2 overflow-y-auto pr-1">
                                    @forelse ($stores as $store)
                                        <label
                                            wire:key="store-{{ $store['id'] }}"
                                            class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200/60 bg-white px-4 py-3 text-sm shadow-sm transition hover:border-emerald-300 hover:bg-emerald-50/60 dark:border-slate-700 dark:bg-slate-900/70 dark:hover:border-emerald-500/40 dark:hover:bg-emerald-500/10"
                                        >
                                            <input
                                                type="checkbox"
                                                wire:model="selectedStoreIds"
                                                value="{{ $store['id'] }}"
                                                class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-400 dark:border-slate-600 dark:bg-slate-900"
                                            >
                                            <div>
                                                <p class="font-semibold text-slate-800 dark:text-slate-100">
                                                    {{ $store['name'] }}
                                                </p>
                                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-400 dark:text-slate-500">
                                                    @if ($store['chain'])
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                            <i data-lucide="layers" class="h-3 w-3"></i>
                                                            {{ $store['chain'] }}
                                                        </span>
                                                    @endif
                                                    @if ($store['zone'])
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                            <i data-lucide="map-pin" class="h-3 w-3"></i>
                                                            {{ $store['zone'] }}
                                                        </span>
                                                    @endif
                                                    @if ($store['city'])
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                            <i data-lucide="navigation" class="h-3 w-3"></i>
                                                            {{ $store['city'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    @empty
                                        <div class="rounded-2xl border border-dashed border-slate-300/70 px-4 py-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                            No hay tiendas activas disponibles.
                                        </div>
                                    @endforelse
                                </div>
                                @error('selectedStoreIds')
                                    <p class="text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex items-center justify-between border-t border-slate-200/70 pt-4 dark:border-slate-700">
                        <div class="text-xs text-slate-400 dark:text-slate-500">
                            {{ count($selectedStoreIds) }} tiendas seleccionadas
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="rounded-xl border border-slate-200/70 px-4 py-2 text-sm font-semibold text-slate-500 transition hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800/70"
                            >
                                Cancelar
                            </button>

                            @if ($modalStep === 1)
                                <button
                                    type="button"
                                    wire:click="nextStep"
                                    class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300 dark:bg-emerald-500/80 dark:hover:bg-emerald-400"
                                >
                                    Continuar
                                </button>
                            @else
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        wire:click="goToStep(1)"
                                        class="rounded-xl border border-slate-200/70 px-4 py-2 text-sm font-semibold text-slate-500 transition hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800/70"
                                    >
                                        Regresar
                                    </button>
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300 dark:bg-emerald-500/80 dark:hover:bg-emerald-400"
                                    >
                                        <i data-lucide="save" class="h-4 w-4"></i>
                                        Guardar asignaciones
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</section>
