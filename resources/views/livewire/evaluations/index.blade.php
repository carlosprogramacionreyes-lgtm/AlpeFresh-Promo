<section class="space-y-6">
    @php
        $exportParams = array_filter([
            'store' => $filters['store'] ?? null,
            'status' => $filters['status'] ?? null,
            'date_from' => $filters['date_from'] ?? null,
            'date_to' => $filters['date_to'] ?? null,
            'search' => $searchTerm ?? null,
        ], fn ($value) => filled($value));
    @endphp

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-300">Total evaluaciones</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($metrics['total'] ?? 0) }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-300">Este mes</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($metrics['current_month'] ?? 0) }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-300">Pendientes</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($metrics['pending'] ?? 0) }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-300">Incidencias registradas</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($metrics['incidents'] ?? 0) }}</p>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div class="grid flex-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                    Búsqueda
                    <input
                        type="text"
                        placeholder="Código, tienda o notas"
                        wire:model.debounce.400ms="searchTerm"
                        class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    >
                </label>
                <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                    Tienda
                    <select wire:model.live="filters.store" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        <option value="">Todas</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                    Estado
                    <select wire:model.live="filters.status" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        <option value="">Todos</option>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-2">
                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                        Desde
                        <input type="date" wire:model.live="filters.date_from" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    </label>
                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                        Hasta
                        <input type="date" wire:model.live="filters.date_to" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    </label>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('evaluations.export', array_merge(['format' => 'xlsx'], $exportParams)) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-purple-300 hover:text-purple-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-teal-400 dark:hover:text-teal-200">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Exportar Excel
                </a>
                <a href="{{ route('evaluations.export', array_merge(['format' => 'pdf'], $exportParams)) }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-purple-600 to-teal-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-purple-500/40 transition hover:from-purple-500 hover:to-teal-400">
                    <i data-lucide="file-text" class="h-4 w-4"></i>
                    Descargar PDF
                </a>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white/90 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        @if ($evaluations->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900/60">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                            <th class="px-6 py-3">Código</th>
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3">Tienda</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3">Calidad</th>
                            <th class="px-6 py-3">Incidencias</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach ($evaluations as $evaluation)
                            <tr wire:key="evaluation-row-{{ $evaluation->id }}" class="text-slate-600 dark:text-slate-300">
                                <td class="px-6 py-3 font-semibold text-slate-800 dark:text-slate-100">{{ $evaluation->code }}</td>
                                <td class="px-6 py-3">{{ optional($evaluation->visited_at ?? $evaluation->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-slate-700 dark:text-slate-100">{{ $evaluation->store?->name }}</span>
                                        <span class="text-xs text-slate-400">{{ $evaluation->store?->chain?->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <span @class([
                                        'inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold',
                                        'bg-teal-100 text-teal-700 dark:bg-teal-500/10 dark:text-teal-200' => $evaluation->status === 'submitted',
                                        'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-200' => $evaluation->status !== 'submitted',
                                    ])>
                                        <span class="h-2 w-2 rounded-full {{ $evaluation->status === 'submitted' ? 'bg-teal-500' : 'bg-amber-400' }}"></span>
                                        {{ $statusOptions[$evaluation->status] ?? ucfirst($evaluation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center gap-1 text-sm font-semibold">
                                        <i data-lucide="star" class="h-4 w-4 text-amber-400"></i>
                                        {{ $evaluation->quality_rating ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    @php
                                        $incidentCount = collect($evaluation->incidents['categories'] ?? [])->filter()->count();
                                    @endphp
                                    <span class="inline-flex items-center gap-2 text-sm">
                                        <i data-lucide="alert-triangle" class="h-4 w-4 text-purple-500"></i>
                                        {{ $incidentCount }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <button type="button" wire:click="selectEvaluation({{ $evaluation->id }})" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-purple-300 hover:text-purple-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-teal-400 dark:hover:text-teal-200">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                        Ver detalle
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-700">
                {{ $evaluations->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center gap-3 px-8 py-16 text-center text-slate-500 dark:text-slate-300">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-teal-400 text-white shadow-glow">
                    <i data-lucide="inbox" class="h-6 w-6"></i>
                </span>
                <p class="text-base font-semibold text-slate-700 dark:text-slate-100">Aún no hay visitas registradas</p>
                <p class="max-w-lg text-sm">Cuando completes evaluaciones aparecerán aquí para que puedas filtrarlas, revisarlas y exportarlas.</p>
                <a wire:navigate href="{{ route('evaluations.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-purple-600 to-teal-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-purple-500/40 transition hover:from-purple-500 hover:to-teal-400">
                    <i data-lucide="zap" class="h-4 w-4"></i>
                    Registrar primera visita
                </a>
            </div>
        @endif
    </div>

    @if ($showDetail && !empty($selectedEvaluation))
        <div class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 shadow-lg shadow-purple-500/10 dark:border-slate-700 dark:bg-slate-900/90">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Detalle de evaluación</p>
                    <h3 class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">{{ $selectedEvaluation['code'] ?? '' }}</h3>
                    <p class="text-xs text-slate-400">{{ $selectedEvaluation['visited_at'] ?? '' }}</p>
                </div>
                <button type="button" wire:click="closeEvaluationDetail" class="rounded-full border border-slate-200 p-2 text-slate-500 transition hover:border-purple-300 hover:text-purple-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-teal-400 dark:hover:text-teal-200">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>

            <div class="mt-4 grid gap-6 lg:grid-cols-3">
                <div class="space-y-4">
                    <div class="rounded-2xl border border-slate-200/70 bg-white/80 p-4 dark:border-slate-700 dark:bg-slate-900/70">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Tienda</p>
                        <p class="mt-1 text-sm font-semibold text-slate-700 dark:text-slate-100">{{ $selectedEvaluation['store']['name'] ?? 'N/D' }}</p>
                        <p class="text-xs text-slate-400">{{ $selectedEvaluation['store']['chain'] ?? '' }}</p>
                        <p class="text-xs text-slate-400">{{ $selectedEvaluation['store']['address'] ?? '' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-white/80 p-4 dark:border-slate-700 dark:bg-slate-900/70">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Calidad</p>
                        <p class="mt-1 text-sm font-semibold text-slate-700 dark:text-slate-100">{{ $selectedEvaluation['quality_rating'] ?? 'N/A' }} / 5</p>
                        <p class="text-xs text-slate-400">Precio observado: ${{ number_format($selectedEvaluation['price_observed'] ?? 0, 2) }}</p>
                        @if ($selectedEvaluation['has_promotion'] ?? false)
                            <p class="text-xs text-slate-400">Promo ${{ number_format($selectedEvaluation['price_discount'] ?? 0, 2) }} · Regular ${{ number_format($selectedEvaluation['price_regular'] ?? 0, 2) }}</p>
                        @endif
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-white/80 p-4 dark:border-slate-700 dark:bg-slate-900/70">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Incidencias</p>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            @php
                                $detailIncidents = collect($selectedEvaluation['incidents']['categories'] ?? [])->map(fn ($key) => $incidentOptions[$key] ?? $key);
                            @endphp
                            {{ $detailIncidents->isNotEmpty() ? $detailIncidents->join(', ') : 'Sin incidencias' }}
                        </p>
                        @if (!empty($selectedEvaluation['incident_comments']))
                            <p class="mt-2 text-xs text-slate-400">{{ $selectedEvaluation['incident_comments'] }}</p>
                        @endif
                    </div>
                </div>
                <div class="lg:col-span-2 space-y-4">
                    <div class="rounded-2xl border border-slate-200/70 bg-white/80 p-4 dark:border-slate-700 dark:bg-slate-900/70">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Disponibilidad</p>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            @foreach ($selectedEvaluation['availability'] ?? [] as $item)
                                <li class="flex items-center justify-between gap-3 rounded-xl border border-slate-100/60 bg-white/60 px-3 py-2 dark:border-slate-700 dark:bg-slate-900/60">
                                    <span>{{ $item['product_name'] ?? 'Producto' }}</span>
                                    <span class="text-xs font-semibold {{ ($item['status'] ?? 'available') === 'available' ? 'text-teal-500' : 'text-amber-500' }}">
                                        {{ ($item['status'] ?? 'available') === 'available' ? 'Disponible' : 'Agotado' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if (!empty($selectedEvaluation['photos']))
                        <div class="rounded-2xl border border-slate-200/70 bg-white/80 p-4 dark:border-slate-700 dark:bg-slate-900/70">
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Evidencias</p>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($selectedEvaluation['photos'] as $photo)
                                    @if ($photo['url'])
                                        <a href="{{ $photo['url'] }}" target="_blank" class="group relative block overflow-hidden rounded-xl border border-slate-200/70 bg-slate-50/80 shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-900/70">
                                            <img src="{{ $photo['url'] }}" alt="Foto {{ $photo['label'] }}" class="h-36 w-full object-cover transition group-hover:scale-105">
                                            <span class="absolute inset-x-0 bottom-0 bg-slate-900/70 px-3 py-1 text-xs font-semibold text-white">{{ ucfirst($photo['step'] ?? 'foto') }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if (!empty($selectedEvaluation['review_notes']))
                        <div class="rounded-2xl border border-slate-200/70 bg-white/80 p-4 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-300">
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Notas adicionales</p>
                            <p class="mt-2">{{ $selectedEvaluation['review_notes'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</section>
