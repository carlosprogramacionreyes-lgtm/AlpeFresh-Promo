<section class="space-y-6">
    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                    Permisos y matriz de acceso
                </h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">
                    Define qué acciones puede realizar cada rol dentro de la plataforma: desde configuraciones estratégicas hasta tareas operativas de campo.
                </p>
            </div>
            <button class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-purple-600 to-teal-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-purple-500/40 transition hover:from-purple-500 hover:to-teal-400">
                <i data-lucide="shield-check" class="h-4 w-4"></i>
                Editar matriz
            </button>
        </div>
    </div>

    <div class="rounded-3xl border border-dashed border-slate-300/80 bg-slate-50/70 p-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400">
        Aquí se mostrará la tabla de permisos por rol, con la posibilidad de activar/desactivar accesos, definir restricciones y exportar la matriz para auditoría.
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Roles principales</h3>
            <ul class="mt-4 space-y-3 text-sm text-slate-500 dark:text-slate-300">
                <li>- Admin: control total y configuración global.</li>
                <li>- Supervisor: gestión de promotoras y catálogos locales.</li>
                <li>- Analista: reportes y exportaciones.</li>
                <li>- Promotor: captura y consulta de evaluaciones.</li>
            </ul>
        </div>
        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Auditoría y seguridad</h3>
            <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">
                Se registrarán logs de cambios, firmas digitales y reglas de aprobación para modificaciones críticas en catálogos o evaluaciones.
            </p>
        </div>
    </div>
</section>
