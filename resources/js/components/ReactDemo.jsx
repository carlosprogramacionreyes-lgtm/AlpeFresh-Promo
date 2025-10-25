import React from 'react';

const features = [
    {
        title: 'Automatización',
        description: 'Flujos orquestados con Livewire y Jobs para responder en segundos.',
        icon: 'bot',
    },
    {
        title: 'Segmentación',
        description: 'Reglas dinámicas que combinan Alpine.js con componentes Tailwind.',
        icon: 'filter',
    },
    {
        title: 'Reportes',
        description: 'Dashboards enriquecidos con Vue, React o Svelte según el caso.',
        icon: 'pie-chart',
    },
];

export default function ReactDemo() {
    const [activeIndex, setActiveIndex] = React.useState(0);

    return (
        <section className="rounded-2xl bg-slate-900 p-6 text-slate-50 shadow-lg ring-1 ring-slate-800/80">
            <header className="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h2 className="text-lg font-semibold">React Demo Panel</h2>
                    <p className="text-sm text-slate-400">
                        Cambia entre beneficios para ver animaciones con AOS.
                    </p>
                </div>
                <span className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-700 text-indigo-300">
                    <i data-lucide="atom" className="h-6 w-6"></i>
                </span>
            </header>

            <div className="grid gap-4 md:grid-cols-[180px_1fr]">
                <ul className="flex md:flex-col gap-2">
                    {features.map((feature, index) => (
                        <li key={feature.title}>
                            <button
                                type="button"
                                className={`w-full rounded-xl border border-transparent px-4 py-3 text-left transition ${
                                    activeIndex === index
                                        ? 'bg-indigo-500/90 text-white shadow-lg shadow-indigo-500/20'
                                        : 'bg-slate-800/60 text-slate-300 hover:border-indigo-400/50 hover:bg-slate-800'
                                }`}
                                onClick={() => setActiveIndex(index)}
                            >
                                <span className="flex items-center gap-2 text-sm font-medium uppercase tracking-wide">
                                    <i data-lucide={feature.icon} className="h-4 w-4"></i>
                                    {feature.title}
                                </span>
                            </button>
                        </li>
                    ))}
                </ul>

                <article
                    className="rounded-xl border border-indigo-500/30 bg-slate-900/70 p-5"
                    data-aos="fade-left"
                    key={features[activeIndex].title}
                >
                    <h3 className="mb-2 text-xl font-semibold">{features[activeIndex].title}</h3>
                    <p className="text-slate-300">{features[activeIndex].description}</p>
                </article>
            </div>
        </section>
    );
}
