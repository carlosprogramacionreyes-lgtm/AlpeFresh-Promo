<section class="space-y-6">
    <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        {{ $editingId ? 'Editar Cadena' : 'Crear Cadena' }}
                    </h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">
                        Registra una nueva cadena comercial o actualiza información relevante para mantener el directorio al día.
                    </p>
                </div>
                <span class="inline-flex h-10 items-center rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-amber-400 px-4 text-sm font-semibold text-white shadow-lg shadow-indigo-400/40">
                    {{ $editingId ? 'Modo edición' : 'Nueva incorporación' }}
                </span>
            </div>

            <form wire:submit.prevent="save" class="mt-6 space-y-6">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="chain-name" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                            Nombre de la cadena
                        </label>
                        <input
                            id="chain-name"
                            type="text"
                            placeholder="Ej. Súper Norte"
                            wire:model.defer="name"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300"
                        />
                        @error('name')
                            <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label for="chain-description" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                            Descripción
                        </label>
                        <textarea
                            id="chain-description"
                            rows="3"
                            placeholder="Incluye formatos, cobertura y particularidades operativas."
                            wire:model.defer="description"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300"
                        ></textarea>
                        @error('description')
                            <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-end gap-3">
                    @if ($editingId)
                        <button
                            type="button"
                            wire:click="cancelEdit"
                            class="inline-flex items-center gap-2 rounded-2xl border border-transparent bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-200 dark:bg-slate-700/70 dark:text-slate-200 dark:hover:bg-slate-700"
                        >
                            <i data-lucide="x" class="h-4 w-4"></i>
                            Cancelar
                        </button>
                    @endif
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-purple-600 to-teal-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-purple-500/40 transition hover:from-purple-500 hover:to-teal-400 focus:outline-none focus:ring-2 focus:ring-purple-300"
                    >
                        <i data-lucide="{{ $editingId ? 'save' : 'sparkles' }}" class="h-4 w-4"></i>
                        <span wire:loading.remove wire:target="save">{{ $editingId ? 'Actualizar Cadena' : 'Crear Cadena' }}</span>
                        <span wire:loading wire:target="save">Guardando...</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Cadenas Registradas
                </h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">
                    Controla las cadenas activas, sus descripciones y fechas de creación.
                </p>
            </div>
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-indigo-300 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-indigo-400 dark:hover:text-indigo-300"
            >
                <i data-lucide="download" class="h-4 w-4"></i>
                Exportar listado
            </button>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200/70 dark:border-slate-700">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                <thead class="bg-slate-50/70 dark:bg-slate-900/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                        <th scope="col" class="px-6 py-4">Nombre</th>
                        <th scope="col" class="px-6 py-4">Descripción</th>
                        <th scope="col" class="px-6 py-4">Fecha de creación</th>
                        <th scope="col" class="px-6 py-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white/60 dark:divide-slate-800 dark:bg-slate-900/60">
                    @forelse ($chains as $chain)
                        <tr wire:key="chain-row-{{ $chain->id }}" class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/60">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-gradient-to-r from-purple-600 via-purple-500 to-indigo-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                    {{ $chain->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-300">
                                {{ $chain->description ?: 'Sin descripción registrada.' }}
                            </td>
                            <td class="px-6 py-4 text-slate-400 dark:text-slate-500">
                                {{ optional($chain->created_at)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $chain->id }})"
                                        class="inline-flex items-center gap-1 rounded-2xl border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-indigo-400 dark:hover:text-indigo-200"
                                    >
                                        <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="delete({{ $chain->id }})"
                                        x-data="{}"
                                        x-on:click="if (! confirm('Eliminar la cadena {{ addslashes($chain->name) }}? Esta acción no se puede deshacer.')) { $event.stopImmediatePropagation(); return false; }"
                                        class="inline-flex items-center gap-1 rounded-md bg-red-500 px-3 py-1 text-xs font-semibold text-white shadow-sm transition hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-200/70 dark:bg-red-500 dark:hover:bg-red-600"
                                    >
                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-400 dark:text-slate-500">
                                Aún no registras cadenas. Agrega la primera para comenzar a organizar tu operación.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
