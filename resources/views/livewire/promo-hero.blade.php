<section class="overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-500 via-purple-500 to-slate-900 p-8 text-white shadow-2xl ring-1 ring-indigo-300/40">
    <div class="grid gap-8 lg:grid-cols-[1.1fr_1fr]">
        <div class="space-y-6">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.4em] text-indigo-50">
                <i data-lucide="radar" class="h-4 w-4"></i>
                Lanzamiento
            </span>

            <div data-aos="fade-right">
                <h1 class="text-3xl font-bold leading-tight sm:text-4xl">
                    Despliega campañas omnicanal impulsadas por Livewire en minutos.
                </h1>
                <p class="mt-4 text-lg text-indigo-50/80">
                    Orquesta flujos personalizados con Tailwind, Alpine, Flowbite y daisyUI sin perder la velocidad del backend Laravel.
                </p>
            </div>

            <ul class="grid gap-4 sm:grid-cols-3" data-aos="fade-up" data-aos-delay="200">
                @foreach ($metrics as $metric)
                    <li class="rounded-2xl bg-white/10 p-4 shadow-lg shadow-black/10 backdrop-blur">
                        <p class="text-xs uppercase tracking-wide text-indigo-100/70">{{ $metric['label'] }}</p>
                        <p class="mt-2 text-2xl font-semibold">{{ $metric['value'] }}</p>
                        <p class="text-xs text-emerald-200">{{ $metric['trend'] }} vs. semana anterior</p>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl bg-slate-950/40 p-6 shadow-xl shadow-black/20 ring-1 ring-white/20 backdrop-blur" data-aos="fade-left">
                <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-indigo-100/80">Plan de despliegue</h2>
                <ol class="mt-4 space-y-3 text-sm text-indigo-100/80">
                    @foreach ($ctaSteps as $index => $step)
                        <li class="flex items-start gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 font-semibold">{{ $index + 1 }}</span>
                            <span class="leading-relaxed">{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="flex flex-wrap gap-4">
                <button type="button" class="btn btn-primary btn-md gap-2 shadow-lg shadow-indigo-900/40">
                    <i data-lucide="rocket" class="h-4 w-4"></i>
                    Activar campaña
                </button>
                <a href="#blueprint" class="btn btn-outline btn-secondary gap-2 border-white/40 text-white hover:bg-white/10">
                    <i data-lucide="book-open" class="h-4 w-4"></i>
                    Ver blueprint
                </a>
            </div>
        </div>
    </div>
</section>
