<section class="space-y-8">
    <div class="rounded-3xl bg-white/90 p-6 shadow-glow ring-1 ring-slate-200/60 transition dark:bg-slate-900/80 dark:ring-slate-700">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-300">
                    {{ now()->translatedFormat('l d \\d\\e F \\d\\e Y') }}
                </p>
                <h2 class="mt-1 text-3xl font-semibold text-slate-900 dark:text-white">
                    Bienvenido, {{ $user?->first_name }}.
                </h2>
                <p class="mt-2 max-w-xl text-sm text-slate-500 dark:text-slate-300">
                    Administra promotoras, visitas y reportes en un solo lugar. Elige una acción rápida o revisa la actividad reciente.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="flex items-center gap-3 rounded-2xl border border-purple-200/40 bg-purple-100/40 px-4 py-3 text-purple-800 dark:border-purple-500/30 dark:bg-purple-500/10 dark:text-purple-200">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/70 text-purple-600 shadow-sm dark:bg-purple-500/20 dark:text-purple-100">
                        <i data-lucide="shield-check" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-wide">Rol</p>
                        <p class="text-sm font-semibold">{{ $user?->role->label() ?? 'Sin rol' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-2xl border border-teal-200/40 bg-teal-100/40 px-4 py-3 text-teal-800 dark:border-teal-500/30 dark:bg-teal-500/10 dark:text-teal-200">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/70 text-teal-600 shadow-sm dark:bg-teal-500/20 dark:text-teal-100">
                        <i data-lucide="clock" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-wide">Último acceso</p>
                        <p class="text-sm font-semibold">
                            {{ optional($user?->last_login_at)->diffForHumans() ?? 'Primer ingreso' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($metricCards as $card)
            <div class="rounded-2xl border border-slate-200/70 bg-white/90 p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/80">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $card['value'] }}</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-teal-400 text-white shadow-glow">
                        <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5"></i>
                    </span>
                </div>
                <p class="mt-3 text-sm text-teal-600 dark:text-teal-300">{{ $card['change'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="flex flex-col gap-8 lg:flex-row">
        <div class="w-full rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80 lg:w-1/2">
            <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                Acciones rápidas
            </h3>
            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                @foreach ($quickActions as $action)
                    <a
                        wire:navigate
                        href="{{ route($action['route']) }}"
                        class="group flex flex-col items-start gap-3 rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4 text-left transition hover:-translate-y-1 hover:border-purple-400 hover:bg-white hover:shadow-md dark:border-slate-700 dark:bg-slate-800/60 dark:hover:border-teal-400 dark:hover:bg-slate-800"
                    >
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-teal-400 text-white shadow-glow">
                            <i data-lucide="{{ $action['icon'] }}" class="h-5 w-5"></i>
                        </span>
                        <span class="text-sm font-semibold text-slate-700 transition group-hover:text-purple-600 dark:text-slate-100 dark:group-hover:text-teal-300">
                            {{ $action['label'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="w-full rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80 lg:w-1/2">
            <livewire:dashboard.recent-activity />
        </div>
    </div>
</section>
