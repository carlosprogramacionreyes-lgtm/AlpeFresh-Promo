<script>
    import { onDestroy, onMount } from 'svelte';

    let index = 0;

    const highlights = [
        {
            title: 'Integraciones',
            description: 'Conecta APIs externas y sincroniza marketing automation sin salir de Laravel.',
        },
        {
            title: 'Escalabilidad',
            description: 'Livewire + queues manejan picos de tráfico y mantienen la interfaz fluida.',
        },
        {
            title: 'Productividad',
            description: 'Componentes compartidos con Tailwind, Flowbite y daisyUI para prototipos veloces.',
        },
    ];

    let interval;

    onMount(() => {
        interval = setInterval(() => {
            index = (index + 1) % highlights.length;
        }, 4000);
    });

    onDestroy(() => {
        clearInterval(interval);
    });
</script>

<section class="rounded-2xl bg-white/90 p-6 shadow-xl ring-1 ring-slate-200/70">
    <header class="mb-5 flex items-center gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600">
            <i data-lucide="activity" class="h-6 w-6"></i>
        </span>
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Svelte Demo Carousel</h2>
            <p class="text-sm text-slate-500">Rotación automática con animaciones suaves</p>
        </div>
    </header>

    <article class="overflow-hidden">
        <div class="rounded-xl bg-emerald-500/10 p-5 transition" data-aos="zoom-in">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-500">
                {highlights[index].title}
            </p>
            <p class="mt-3 text-base text-slate-600">{highlights[index].description}</p>
        </div>
    </article>

    <footer class="mt-4 flex gap-2">
        {#each highlights as _, i}
            <span
                class={`h-1 flex-1 rounded-full transition ${
                    i === index ? 'bg-emerald-500' : 'bg-slate-200'
                }`}
            ></span>
        {/each}
    </footer>
</section>
