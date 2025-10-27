<section class="space-y-5">
    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                    {{ $editingId ? 'Editar Tienda' : 'Crear Tienda' }}
                </h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">
                    Registra puntos de venta con información geográfica y operativa para habilitar visitas y evaluaciones controladas.
                </p>
            </div>
            <span class="inline-flex h-10 items-center rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-amber-400 px-4 text-sm font-semibold text-white shadow-lg shadow-indigo-400/40">
                {{ $editingId ? 'Modo edición' : 'Nueva incorporación' }}
            </span>
        </div>

        <form wire:submit.prevent="save" class="mt-6 space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2 md:col-span-2">
                    <label for="store-chain" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                        Cadena *
                    </label>
                    <select
                        id="store-chain"
                        wire:model.live="chainId"
                        @disabled($chains->isEmpty())
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300 dark:disabled:bg-slate-800 dark:disabled:text-slate-600"
                    >
                        <option value="">
                            {{ $chains->isEmpty() ? 'Registra una cadena antes de crear tiendas' : 'Selecciona una cadena' }}
                        </option>
                        @foreach ($chains as $chain)
                            <option value="{{ $chain->id }}">{{ $chain->name }}</option>
                        @endforeach
                    </select>
                    @error('chainId')
                        <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="store-zone" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                        Zona *
                    </label>
                    <select
                        id="store-zone"
                        wire:model.defer="zoneId"
                        @disabled(!$chainId || $zonesOptions->isEmpty())
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300 dark:disabled:bg-slate-800 dark:disabled:text-slate-600"
                    >
                        <option value="">
                            {{ $chainId ? ($zonesOptions->isEmpty() ? 'No hay zonas disponibles para esta cadena' : 'Selecciona una zona') : 'Selecciona una cadena primero' }}
                        </option>
                        @foreach ($zonesOptions as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                    @error('zoneId')
                        <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="store-name" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                        Nombre de la tienda *
                    </label>
                    <input
                        id="store-name"
                        type="text"
                        placeholder="Ej. HEB Cumbres"
                        wire:model.defer="name"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300"
                    />
                    @error('name')
                        <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="store-city" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                        Ciudad
                    </label>
                    <input
                        id="store-city"
                        type="text"
                        placeholder="Ej. San Pedro Garza García"
                        wire:model.defer="city"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300"
                    />
                    @error('city')
                        <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="store-address" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                        Dirección
                    </label>
                    <textarea
                        id="store-address"
                        rows="3"
                        placeholder="Incluye la dirección completa y referencias relevantes."
                        wire:model.defer="address"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300"
                    ></textarea>
                    @error('address')
                        <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="store-latitude" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                        Latitud
                    </label>
                    <input
                        id="store-latitude"
                        type="number"
                        step="0.000001"
                        placeholder="Ej. 25.6866"
                        wire:model.defer="latitude"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300"
                    />
                    @error('latitude')
                        <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="store-longitude" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                        Longitud
                    </label>
                    <input
                        id="store-longitude"
                        type="number"
                        step="0.000001"
                        placeholder="Ej. -100.3161"
                        wire:model.defer="longitude"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300"
                    />
                    @error('longitude')
                        <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="store-geofence" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                        Radio de geocerca (m) *
                    </label>
                    <input
                        id="store-geofence"
                        type="number"
                        min="10"
                        max="1000"
                        step="5"
                        wire:model.defer="geofenceRadius"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300"
                    />
                    @error('geofenceRadius')
                        <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-400 dark:text-slate-500">
                        Valor predeterminado de 50 m; ajusta según la extensión de la tienda.
                    </p>
                </div>

                <div class="space-y-2">
                    <label for="store-status" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                        Estado
                    </label>
                    <select
                        id="store-status"
                        wire:model.defer="isActive"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-indigo-300"
                    >
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            @if ($chains->isEmpty())
                <p class="text-xs font-medium text-amber-600 dark:text-amber-400">
                    Registra al menos una cadena y una zona para poder crear tiendas.
                </p>
            @endif

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
                    @disabled($chains->isEmpty())
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-purple-600 to-teal-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-purple-500/40 transition hover:from-purple-500 hover:to-teal-400 focus:outline-none focus:ring-2 focus:ring-purple-300 disabled:cursor-not-allowed disabled:opacity-70"
                >
                    <i data-lucide="{{ $editingId ? 'save' : 'sparkles' }}" class="h-4 w-4"></i>
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Actualizar Tienda' : 'Crear Tienda' }}</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </button>
            </div>
        </form>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                Tiendas registradas
            </h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">
                Consulta todas las tiendas dadas de alta y gestiona su información operativa.
            </p>

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200/70 dark:border-slate-700">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                    <thead class="bg-slate-50/70 dark:bg-slate-900/80">
                        <tr class="text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                            <th scope="col" class="px-6 py-4">Cadena</th>
                            <th scope="col" class="px-6 py-4">Zona</th>
                            <th scope="col" class="px-6 py-4">Nombre</th>
                            <th scope="col" class="px-6 py-4">Ciudad</th>
                            <th scope="col" class="px-6 py-4">Estado</th>
                            <th scope="col" class="px-6 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white/60 dark:divide-slate-800 dark:bg-slate-900/60">
                        @forelse ($stores as $store)
                            <tr wire:key="store-row-{{ $store->id }}" class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/60">
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-200">
                                    {{ $store->chain?->name ?? 'Sin cadena' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-200">
                                    {{ $store->zone?->name ?? 'Sin zona' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-200">
                                    {{ $store->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-300">
                                    {{ $store->city ?: '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold shadow-sm',
                                        $store->is_active ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                                    ])>
                                        <span @class(['h-2 w-2 rounded-full', $store->is_active ? 'bg-emerald-500' : 'bg-slate-500'])></span>
                                        {{ $store->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            wire:click="edit({{ $store->id }})"
                                            class="inline-flex items-center gap-1 rounded-2xl border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-indigo-400 dark:hover:text-indigo-200"
                                        >
                                            <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                            Editar
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="delete({{ $store->id }})"
                                            x-data="{}"
                                            x-on:click="if (! confirm('Eliminar la tienda {{ addslashes($store->name) }}? Esta acción no se puede deshacer.')) { $event.stopImmediatePropagation(); return false; }"
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
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400 dark:text-slate-500">
                                    Aún no registras tiendas. Crea una nueva tienda y asígnala a la cadena y zona correspondientes para habilitar las visitas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
                <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                    Función de geocerca GPS
                </h3>
                <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">
                    El radio establecido se utiliza para validar la ubicación del promotor durante una visita.
                </p>
                <ul class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    <li><span class="font-semibold text-emerald-600 dark:text-emerald-300">Dentro del radio →</span> visita permitida sin restricciones.</li>
                    <li><span class="font-semibold text-amber-600 dark:text-amber-400">Fuera del radio →</span> se muestra una advertencia, pero la visita puede continuar.</li>
                </ul>
                <p class="mt-4 text-xs text-slate-400 dark:text-slate-500">
                    Asegura coordenadas precisas y un radio acorde a la operación para evitar falsos positivos.
                </p>
            </div>

            <div class="rounded-3xl border border-indigo-200/70 bg-gradient-to-br from-indigo-50 via-white to-slate-50 p-6 shadow-sm dark:border-indigo-500/30 dark:from-indigo-900/40 dark:via-slate-900/80 dark:to-slate-900/60">
                <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-300">
                    Integridad operativa
                </h3>
                <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                    <li>✅ Control de visitas: sólo se generan evaluaciones en tiendas registradas.</li>
                    <li>✅ Validación GPS: garantiza la presencia física en campo.</li>
                    <li>✅ Asignaciones sincronizadas: define qué promotores pueden visitar cada tienda.</li>
                    <li>✅ Informes confiables: reportes filtrados por cadena, zona y tienda.</li>
                    <li>✅ Datos íntegros: cada evaluación queda vinculada a una tienda específica.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
