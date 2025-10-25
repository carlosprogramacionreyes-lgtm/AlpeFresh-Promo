<section class="space-y-6">
    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                    Inteligencia operativa
                </p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">
                    Reportes y análisis en tiempo real
                </h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">
                    Filtra por rango de fechas, cadena, zona, tienda, producto o promotora. Esta vista mostrará métricas y gráficas clave.
                </p>
            </div>
            <div class="flex gap-3">
                <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-purple-300 hover:text-purple-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-teal-400 dark:hover:text-teal-200">
                    <i data-lucide="share" class="h-4 w-4"></i>
                    Compartir
                </button>
                <button class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-purple-600 to-teal-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-purple-500/40 transition hover:from-purple-500 hover:to-teal-400">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Exportar CSV
                </button>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3 xl:grid-cols-6">
            @foreach (['Fecha inicio', 'Fecha fin', 'Cadena', 'Zona', 'Tienda', 'Promotor'] as $filter)
                <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                    {{ $filter }}
                    <input
                        type="text"
                        placeholder="Selecciona"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/40 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-teal-400 dark:focus:ring-teal-400/30"
                    >
                </label>
            @endforeach
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Evaluaciones totales', 'value' => '0', 'change' => 'Meta mensual 180'],
            ['label' => 'Calidad promedio', 'value' => '0%', 'change' => 'Objetivo 95%'],
            ['label' => 'Incidencias reportadas', 'value' => '0', 'change' => 'Sin incidencias críticas'],
            ['label' => 'Tiendas evaluadas', 'value' => '0', 'change' => 'Cobertura en progreso'],
        ] as $summary)
            <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-300">{{ $summary['label'] }}</p>
                <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $summary['value'] }}</p>
                <p class="mt-2 text-sm text-teal-600 dark:text-teal-300">{{ $summary['change'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        @foreach ([
            'Calidad promedio por tienda',
            'Incidencias por tipo',
            'Cumplimiento de precios',
            'Tendencia semanal',
        ] as $chart)
            <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                        {{ $chart }}
                    </h3>
                    <span class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-500 dark:border-slate-700 dark:text-slate-300">
                        Próximamente
                    </span>
                </div>
                <div class="mt-6 flex h-48 items-center justify-center rounded-2xl border border-dashed border-slate-300/80 bg-slate-50/60 text-sm text-slate-400 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-500">
                    Área reservada para visualización gráfica.
                </div>
            </div>
        @endforeach
    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                Tabla de detalle
            </h3>
            <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-purple-300 hover:text-purple-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-teal-400 dark:hover:text-teal-200">
                <i data-lucide="table" class="h-4 w-4"></i>
                Exportar a Excel
            </button>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl border border-dashed border-slate-300/80 bg-slate-50/70 dark:border-slate-700 dark:bg-slate-900/40">
            <div class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                La tabla dinámica de evaluaciones aparecerá aquí con filtros avanzados y exportaciones.
            </div>
        </div>
    </div>
</section>
