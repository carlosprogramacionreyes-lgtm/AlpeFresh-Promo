<div class="flex h-full flex-col gap-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                Actividad reciente
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Actualizado {{ now()->format('H:i') }} hrs
            </p>
        </div>
        <button
            type="button"
            wire:click="$refresh"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-purple-300 hover:text-purple-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-teal-400 dark:hover:text-teal-200"
        >
            <i data-lucide="refresh-cw" class="h-4 w-4"></i>
            Actualizar
        </button>
    </div>

    @if ($activities->isEmpty())
        <div class="flex flex-1 flex-col items-center justify-center gap-3 rounded-2xl border border-dashed border-slate-300/70 bg-slate-50/70 p-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-300">
            <i data-lucide="activity" class="h-6 w-6 text-purple-500"></i>
            <p class="font-semibold text-slate-700 dark:text-slate-100">Sin actividad por el momento</p>
            <p class="text-xs text-slate-400">Cuando se generen evaluaciones aparecerán los últimos movimientos aquí.</p>
        </div>
    @else
        <ul class="space-y-3">
            @foreach ($activities as $activity)
                <li class="flex items-start gap-3 rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-teal-400 text-white shadow-glow">
                        <i data-lucide="{{ $activity['icon'] }}" class="h-5 w-5"></i>
                    </span>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $activity['title'] }}</p>
                            <span class="text-xs font-semibold text-teal-500 dark:text-teal-300">
                                {{ optional($activity['timestamp'])->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ $activity['subtitle'] ?? '' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-300">{{ $activity['description'] }}</p>
                        <div class="flex flex-wrap gap-3 pt-2 text-xs text-slate-500 dark:text-slate-400">
                            <span class="inline-flex items-center gap-1 rounded-full bg-white/60 px-2 py-1 dark:bg-slate-900/50">
                                <i data-lucide="star" class="h-3 w-3 text-amber-400"></i>
                                {{ $activity['quality'] ?? 'N/A' }} / 5
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-white/60 px-2 py-1 dark:bg-slate-900/50">
                                <i data-lucide="alert-octagon" class="h-3 w-3 text-purple-500"></i>
                                {{ $activity['incidents'] }} incidencias
                            </span>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
