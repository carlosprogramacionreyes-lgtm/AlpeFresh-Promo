<template>
    <section class="rounded-2xl bg-white/80 p-6 shadow-lg ring-1 ring-slate-200 backdrop-blur">
        <header class="mb-4 flex items-center gap-3">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500/10 text-indigo-600">
                <i data-lucide="sparkles" class="h-6 w-6"></i>
            </span>
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Vue Demo Widget</h2>
                <p class="text-sm text-slate-500">Datos reactivos alimentados por Vue 3 + Tailwind</p>
            </div>
        </header>
        <ul class="grid gap-3 sm:grid-cols-2">
            <li
                v-for="item in stats"
                :key="item.label"
                class="rounded-xl border border-slate-200/70 bg-slate-50/70 p-4 transition hover:border-indigo-300 hover:bg-white"
            >
                <p class="text-xs uppercase tracking-wide text-slate-500">{{ item.label }}</p>
                <p class="mt-1 text-2xl font-semibold text-slate-900">{{ item.value }}</p>
            </li>
        </ul>
    </section>
</template>

<script setup>
const stats = [
    { label: 'Clientes activos', value: '1,248' },
    { label: 'Tasa de conversión', value: '4.6%' },
    { label: 'ROI semanal', value: '213%' },
    { label: 'Tickets resueltos', value: '312' },
];
</script>
