<section class="space-y-6">
    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                    Flujo de evaluación
                </p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">
                    Paso {{ $currentStep }} · {{ $steps[$currentStep]['title'] ?? '' }}
                </h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">
                    {{ $steps[$currentStep]['description'] ?? '' }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700 dark:bg-purple-500/10 dark:text-purple-200">
                    Geovalla 800m
                </span>
                <span class="rounded-full bg-teal-100 px-3 py-1 text-xs font-medium text-teal-700 dark:bg-teal-500/10 dark:text-teal-200">
                    Guardado seguro
                </span>
            </div>
        </div>

        <ol class="mt-6 grid gap-4 md:grid-cols-3 xl:grid-cols-6">
            @foreach ($steps as $index => $step)
                <li
                    wire:key="wizard-step-{{ $index }}"
                    @class([
                        'rounded-2xl border p-4 transition text-sm',
                        'border-purple-300 bg-purple-100/70 text-purple-900 shadow-md shadow-purple-200/60 dark:border-purple-500/50 dark:bg-purple-500/10 dark:text-purple-100' => $index === $currentStep,
                        'border-slate-200 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-900/50 dark:text-slate-300' => $index !== $currentStep,
                    ])
                >
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Paso {{ $index }}</p>
                    <p class="mt-2 font-semibold">{{ $step['title'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>

    <form wire:submit.prevent="submitEvaluation" class="grid gap-6 lg:grid-cols-[2fr_1fr]">
        <div class="space-y-6">
            @if ($currentStep === 1)
                <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80 space-y-5">
                    <div class="space-y-2">
                        <label for="store" class="text-sm font-semibold text-slate-700 dark:text-slate-100">Tienda asignada</label>
                        <select
                            id="store"
                            wire:model="storeId"
                            class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">Selecciona una tienda</option>
                            @foreach ($assignments as $option)
                                <option value="{{ $option['store_id'] }}">
                                    {{ $option['store_name'] }} @if($option['chain_name']) · {{ $option['chain_name'] }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('storeId')
                            <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                        @enderror
                        @if (empty($assignments))
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                No tienes tiendas asignadas activas. Contacta a tu supervisor para recibir una asignación.
                            </p>
                        @endif
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Latitud actual
                            <input
                                type="text"
                                wire:model="currentLatitude"
                                placeholder="25.6751"
                                class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            >
                            @error('currentLatitude')
                                <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Longitud actual
                            <input
                                type="text"
                                wire:model="currentLongitude"
                                placeholder="-100.309"
                                class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            >
                            @error('currentLongitude')
                                <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button
                            type="button"
                            x-data="{}"
                            x-on:click="if (navigator.geolocation) { navigator.geolocation.getCurrentPosition((position) => { Livewire.dispatch('location-captured', { latitude: position.coords.latitude, longitude: position.coords.longitude }); }, () => window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'No se pudo obtener la ubicación.' } }))); } else { window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Geolocalización no soportada.' } })); }"
                            class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-purple-300 hover:text-purple-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-teal-400 dark:hover:text-teal-200"
                        >
                            <i data-lucide="crosshair" class="h-4 w-4"></i>
                            Usar mi ubicación
                        </button>
                        <button
                            type="button"
                            wire:click="verifyGeofence"
                            class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-purple-600 to-teal-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-purple-500/40 transition hover:from-purple-500 hover:to-teal-400"
                        >
                            <i data-lucide="shield-check" class="h-4 w-4"></i>
                            Validar geovalla
                        </button>
                    </div>

                    @error('geofence')
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600 dark:border-red-500/40 dark:bg-red-500/10 dark:text-red-200">
                            {{ $message }}
                        </div>
                    @enderror

                    @if ($geofenceChecked)
                        <div class="rounded-2xl border {{ $geofenceValid ? 'border-teal-200 bg-teal-50 text-teal-700' : 'border-red-200 bg-red-50 text-red-600' }} px-4 py-3 text-sm dark:border-slate-700">
                            @if ($geofenceValid)
                                Dentro de la geovalla · distancia estimada {{ $geofenceDistance ? number_format($geofenceDistance, 2) : '0.00' }} m.
                            @else
                                Fuera del radio permitido. Distancia {{ number_format($geofenceDistance, 2) }} m.
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            @if ($currentStep === 2)
                <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Disponibilidad por producto</h3>
                        <button type="button" wire:click="addAvailabilityRow" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-purple-300 hover:text-purple-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-teal-400 dark:hover:text-teal-200">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                            Añadir producto
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach ($availabilityRows as $index => $row)
                            <div class="rounded-2xl border border-slate-200/80 bg-white/60 p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900/60" wire:key="availability-{{ $index }}">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-100">Producto #{{ $index + 1 }}</p>
                                    @if (count($availabilityRows) > 1)
                                        <button type="button" wire:click="removeAvailabilityRow({{ $index }})" class="inline-flex items-center gap-1 rounded-full border border-red-200 px-3 py-1 text-xs font-semibold text-red-500 transition hover:bg-red-50 dark:border-red-500/40 dark:text-red-200 dark:hover:bg-red-500/10">
                                            <i data-lucide="trash-2" class="h-3 w-3"></i>
                                            Quitar
                                        </button>
                                    @endif
                                </div>
                                <div class="mt-4 grid gap-4 md:grid-cols-2">
                                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                        Producto
                                        <select wire:model="availabilityRows.{{ $index }}.product_id" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            <option value="">Selecciona</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product['id'] }}">
                                                    {{ $product['name'] }}
                                                    @if($product['presentation'])
                                                        · {{ $product['presentation'] }}
                                                    @endif
                                                    @if($product['category'])
                                                        · {{ $product['category'] }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('availabilityRows.' . $index . '.product_id')
                                            <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                        Ubicación
                                        <input type="text" wire:model="availabilityRows.{{ $index }}.location" placeholder="Góndola principal" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                        @error('availabilityRows.' . $index . '.location')
                                            <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                                        @enderror
                                    </label>
                                </div>
                                <div class="mt-4 grid gap-4 md:grid-cols-2">
                                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                        Estado
                                        <select wire:model="availabilityRows.{{ $index }}.status" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            <option value="available">Disponible</option>
                                            <option value="out_of_stock">Agotado</option>
                                        </select>
                                        @error('availabilityRows.' . $index . '.status')
                                            <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                        Evidencia fotográfica (opcional)
                                        <input type="file" wire:model="availabilityRows.{{ $index }}.photo" accept="image/*" class="rounded-2xl border border-dashed border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                        @error('availabilityRows.' . $index . '.photo')
                                            <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                                        @enderror
                                        @if (!empty($row['photo']))
                                            <span class="text-xs text-teal-500">Archivo seleccionado</span>
                                        @endif
                                    </label>
                                </div>
                                <label class="mt-4 flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Comentarios
                                    <textarea wire:model="availabilityRows.{{ $index }}.notes" rows="2" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"></textarea>
                                    @error('availabilityRows.' . $index . '.notes')
                                        <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($currentStep === 3)
                <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80 space-y-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Calidad del producto</h3>
                    <div class="flex flex-wrap gap-3">
                        @for ($rating = 1; $rating <= 5; $rating++)
                            <label class="inline-flex items-center gap-2 rounded-2xl border {{ $qualityRating === $rating ? 'border-purple-400 bg-purple-50 text-purple-700 dark:border-purple-500/70 dark:bg-purple-500/10 dark:text-purple-200' : 'border-slate-200 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300' }} px-4 py-2 text-sm font-semibold shadow-sm">
                                <input type="radio" class="hidden" value="{{ $rating }}" wire:model="qualityRating">
                                <i data-lucide="star" class="h-4 w-4"></i>
                                {{ $rating }}
                            </label>
                        @endfor
                    </div>
                    @error('qualityRating')
                        <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                    @enderror

                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        Observaciones
                        <textarea wire:model="qualityObservations" rows="3" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"></textarea>
                        @error('qualityObservations')
                            <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        Foto del producto
                        <input type="file" wire:model="qualityPhoto" accept="image/*" class="rounded-2xl border border-dashed border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        @error('qualityPhoto')
                            <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                        @enderror
                        @if ($qualityPhoto)
                            <span class="text-xs text-teal-500">Archivo seleccionado</span>
                        @endif
                    </label>
                </div>
            @endif

            @if ($currentStep === 4)
                <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80 space-y-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Precios</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Precio observado
                            <input type="number" step="0.01" wire:model="priceObserved" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                            @error('priceObserved')
                                <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" wire:model="hasPromotion" class="h-4 w-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                            Precio en promoción
                        </label>
                    </div>
                    @if ($hasPromotion)
                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Precio regular
                                <input type="number" step="0.01" wire:model="priceRegular" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                @error('priceRegular')
                                    <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                                @enderror
                            </label>
                            <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Precio con descuento
                                <input type="number" step="0.01" wire:model="priceDiscount" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                @error('priceDiscount')
                                    <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                    @endif

                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        Foto de etiqueta / anaquel
                        <input type="file" wire:model="pricePhoto" accept="image/*" class="rounded-2xl border border-dashed border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        @error('pricePhoto')
                            <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                        @enderror
                        @if ($pricePhoto)
                            <span class="text-xs text-teal-500">Archivo seleccionado</span>
                        @endif
                    </label>
                </div>
            @endif

            @if ($currentStep === 5)
                <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80 space-y-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Incidencias</h3>
                    <div class="grid gap-3 md:grid-cols-2">
                        @foreach ($incidentOptions as $key => $label)
                            <label class="flex items-center gap-3 rounded-2xl border {{ in_array($key, $incidentCategories, true) ? 'border-teal-300 bg-teal-50 text-teal-700 dark:border-teal-400/60 dark:bg-teal-500/10 dark:text-teal-200' : 'border-slate-200 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300' }} px-4 py-3 text-sm font-semibold">
                                <input type="checkbox" value="{{ $key }}" wire:model="incidentCategories" class="h-4 w-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    @error('incidentCategories')
                        <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                    @enderror

                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        Comentarios adicionales
                        <textarea wire:model="incidentComments" rows="3" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"></textarea>
                        @error('incidentComments')
                            <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        Evidencias fotográficas
                        <input type="file" wire:model="incidentPhotos" multiple accept="image/*" class="rounded-2xl border border-dashed border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        @if ($errors->has('incidentPhotos.*'))
                            <span class="text-xs font-semibold text-purple-600">{{ $errors->first('incidentPhotos.*') }}</span>
                        @endif
                        @if (!empty($incidentPhotos))
                            <span class="text-xs text-teal-500">{{ count($incidentPhotos) }} archivo(s) seleccionados</span>
                        @endif
                    </label>
                </div>
            @endif

            @if ($currentStep === 6)
                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Resumen de la evaluación</h3>
                        <dl class="mt-4 grid gap-3 text-sm text-slate-600 dark:text-slate-300">
                            <div>
                                <dt class="font-semibold text-slate-700 dark:text-slate-100">Tienda</dt>
                                <dd>{{ $selectedStore?->name ?? 'Sin seleccionar' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-slate-700 dark:text-slate-100">Disponibilidad</dt>
                                <dd>
                                    @foreach ($availabilityRows as $row)
                                        @php
                                            $productSummary = collect($products)->firstWhere('id', $row['product_id']);
                                        @endphp
                                        <p>- {{ $productSummary['name'] ?? 'Producto sin nombre' }} · {{ ($row['status'] ?? 'available') === 'available' ? 'Disponible' : 'Agotado' }}</p>
                                    @endforeach
                                </dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-slate-700 dark:text-slate-100">Calidad</dt>
                                <dd>{{ $qualityRating ? $qualityRating . '/5' : 'Pendiente' }} · {{ $qualityObservations ?: 'Sin observaciones' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-slate-700 dark:text-slate-100">Precio</dt>
                                <dd>
                                    ${{ number_format($priceObserved ?? 0, 2) }}
                                    @if ($hasPromotion)
                                        (Regular ${{ number_format($priceRegular ?? 0, 2) }} | Promo ${{ number_format($priceDiscount ?? 0, 2) }})
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-slate-700 dark:text-slate-100">Incidencias</dt>
                                <dd>
                                    @if (empty($incidentCategories))
                                        Ninguna registrada
                                    @else
                                        {{ collect($incidentCategories)->map(fn ($key) => $incidentOptions[$key] ?? $key)->join(', ') }}
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
                        <label class="flex flex-col gap-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Notas finales al supervisor
                            <textarea wire:model="reviewNotes" rows="3" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"></textarea>
                            @error('reviewNotes')
                                <span class="text-xs font-semibold text-purple-600">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>
                </div>
            @endif
        </div>

        <aside class="space-y-4">
            <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
                <h4 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Contexto de tienda</h4>
                @if ($selectedStore)
                    <div class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                        <p class="font-semibold text-slate-700 dark:text-slate-100">{{ $selectedStore->name }}</p>
                        <p>{{ $selectedStore->chain?->name }}</p>
                        <p>{{ $selectedStore->address }}</p>
                        <p class="text-xs text-slate-400">Lat: {{ $selectedStore->latitude ?? 'N/D' }} · Lng: {{ $selectedStore->longitude ?? 'N/D' }}</p>
                        <p class="text-xs text-slate-400">Zona: {{ $selectedStore->zone?->name ?? 'N/D' }}</p>
                    </div>
                @else
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">Selecciona una tienda para ver el detalle.</p>
                @endif
            </div>

            <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
                <h4 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Pasos</h4>
                <ul class="mt-4 space-y-2 text-xs text-slate-500 dark:text-slate-300">
                    <li>- Captura tu ubicación y valida el radio.</li>
                    <li>- Registra disponibilidad por producto.</li>
                    <li>- Documenta calidad con evidencias.</li>
                    <li>- Confirma precios y promociones.</li>
                    <li>- Reporta incidencias y notas.</li>
                    <li>- Revisa y envía la evaluación.</li>
                </ul>
            </div>

            <div class="flex flex-col gap-3">
                @if ($currentStep > 1)
                    <button type="button" wire:click="previousStep" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-purple-300 hover:text-purple-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-teal-400 dark:hover:text-teal-200">
                        <i data-lucide="arrow-left" class="mr-2 inline-block h-4 w-4"></i>
                        Paso anterior
                    </button>
                @endif

                @if ($currentStep < 6)
                    <button type="button" wire:click="nextStep" class="rounded-2xl bg-gradient-to-br from-purple-600 to-teal-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-purple-500/40 transition hover:from-purple-500 hover:to-teal-400">
                        Siguiente paso
                        <i data-lucide="arrow-right" class="ml-2 inline-block h-4 w-4"></i>
                    </button>
                @else
                    <button type="submit" class="rounded-2xl bg-gradient-to-br from-purple-600 to-teal-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-purple-500/40 transition hover:from-purple-500 hover:to-teal-400 disabled:opacity-60" wire:loading.attr="disabled">
                        <span wire:loading.remove>Enviar evaluación</span>
                        <span wire:loading class="flex items-center justify-center gap-2">
                            <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                            Guardando...
                        </span>
                    </button>
                @endif
            </div>

            @if (session('status'))
                <div class="rounded-2xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-700 dark:border-teal-500/40 dark:bg-teal-500/10 dark:text-teal-200">
                    {{ session('status') }}
                </div>
            @endif
        </aside>
    </form>
</section>
