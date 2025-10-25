@extends('layouts.app')

@section('content')
    <section class="grid gap-8 lg:grid-cols-[1.2fr_1fr]">
        <div class="space-y-6">
            @livewire('promo-hero')

            <div
                id="flowbite-accordion"
                data-accordion="collapse"
                class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur"
            >
                <h2 id="accordion-heading-1">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between gap-3 rounded-xl p-4 text-left text-slate-100 transition hover:bg-white/10"
                        data-accordion-target="#accordion-body-1"
                        aria-expanded="true"
                        aria-controls="accordion-body-1"
                    >
                        <span class="flex items-center gap-2 text-sm font-semibold tracking-wide uppercase">
                            <i data-lucide="layers" class="h-4 w-4"></i>
                            Stack Frontend
                        </span>
                        <i data-lucide="chevron-down" class="h-5 w-5"></i>
                    </button>
                </h2>
                <div id="accordion-body-1" class="space-y-3 p-4 pt-0 text-sm text-slate-300" aria-labelledby="accordion-heading-1">
                    <p><strong class="text-indigo-200">Tailwind + daisyUI:</strong> Diseños consistentes con componentes accesibles listos para usar.</p>
                    <p><strong class="text-emerald-200">Flowbite:</strong> Scripts interactivos ligeros para acordeones, modales y tooltips.</p>
                    <p><strong class="text-sky-200">Alpine:</strong> Micro-interacciones declarativas que conviven con Livewire.</p>
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-2xl border border-white/10 bg-white/10 p-6 text-slate-950 shadow-xl">
                <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-600">Widgets SPA</h3>
                <p class="mt-2 text-lg font-semibold text-slate-900">
                    Activa Vue, React o Svelte en zonas específicas sin abandonar Livewire.
                </p>
                <div class="mt-6 space-y-3 text-sm text-slate-600">
                    <p>— Monte un componente Vue en <code>#vue-app</code></p>
                    <p>— Renderiza experiencias React en <code>#react-root</code></p>
                    <p>— Crea micro widgets con Svelte en <code>#svelte-root</code></p>
                </div>
                <div class="mt-6 flex gap-3">
                    <button class="btn btn-primary btn-wide">Lanzar campaña</button>
                    <button class="btn btn-outline btn-secondary">Ver roadmap</button>
                </div>
            </div>
        </aside>
    </section>

    <section class="grid gap-8 lg:grid-cols-3">
        <div id="vue-app" class="col-span-1" data-aos="fade-up" data-aos-delay="150"></div>
        <div id="react-root" class="col-span-1" data-aos="fade-up" data-aos-delay="300"></div>
        <div id="svelte-root" class="col-span-1" data-aos="fade-up" data-aos-delay="450"></div>
    </section>
@endsection
