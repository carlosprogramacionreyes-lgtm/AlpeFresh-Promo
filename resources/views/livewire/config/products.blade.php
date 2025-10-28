@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

<section class="space-y-6">
    <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        {{ $editingId ? 'Editar producto' : 'Registrar nuevo producto' }}
                    </h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">
                        Centraliza nombre, categoría, presentación y galería de imágenes para mantener un catálogo confiable para evaluaciones y reportes.
                    </p>
                </div>
                <span class="inline-flex h-10 items-center rounded-full bg-gradient-to-br from-emerald-500 via-teal-500 to-sky-500 px-4 text-sm font-semibold text-white shadow-lg shadow-emerald-400/40">
                    {{ $editingId ? 'Modo edición' : 'Alta rápida' }}
                </span>
            </div>

            <form wire:submit.prevent="save" class="mt-6 space-y-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2 md:col-span-2">
                        <label for="product-name" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                            Nombre del producto*
                        </label>
                        <input
                            id="product-name"
                            type="text"
                            placeholder="Ej. Mix Primavera"
                            wire:model.defer="form.name"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-emerald-300"
                        />
                        @error('form.name')
                            <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="product-category" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                            Categoría / Tipo
                        </label>
                        <input
                            id="product-category"
                            type="text"
                            placeholder="Ej. Ensalada, Berry, Hortaliza"
                            wire:model.defer="form.category"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-emerald-300"
                        />
                        @error('form.category')
                            <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="product-presentation" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                            Presentación
                        </label>
                        <input
                            id="product-presentation"
                            type="text"
                            placeholder="Ej. Bolsa 10 oz, Caja 12 unidades"
                            wire:model.defer="form.presentation"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-emerald-300"
                        />
                        @error('form.presentation')
                            <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="product-description" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                            Descripción breve
                        </label>
                        <textarea
                            id="product-description"
                            rows="3"
                            placeholder="Notas breves para equipo comercial o de evaluación."
                            wire:model.defer="form.short_description"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-emerald-300"
                        ></textarea>
                        @error('form.short_description')
                            <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="product-status" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                            Estado
                        </label>
                        <select
                            id="product-status"
                            wire:model.defer="form.is_active"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-emerald-300"
                        >
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        @error('form.is_active')
                            <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                                Galería de imágenes
                            </h3>
                            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                JPG, PNG o WEBP. Se guardan en <code class="rounded bg-slate-100 px-1.5 py-0.5 text-[0.68rem] text-slate-600 dark:bg-slate-800 dark:text-slate-300">storage/app/public/products</code>.
                            </p>
                        </div>
                        <button
                            type="button"
                            wire:click="addImageInput"
                            class="inline-flex items-center gap-2 rounded-2xl border border-emerald-400/60 bg-emerald-50 px-4 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-emerald-600/40 dark:bg-emerald-900/30 dark:text-emerald-200 dark:hover:bg-emerald-900/50"
                        >
                            <i data-lucide="image-plus" class="h-4 w-4"></i>
                            Agregar imagen
                        </button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($existingImages as $image)
                            <div
                                wire:key="existing-image-{{ $image['id'] }}"
                                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80"
                            >
                                <img
                                    src="{{ Storage::disk('public')->url($image['path']) }}"
                                    alt="Imagen del producto"
                                    class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-105"
                                >
                                <button
                                    type="button"
                                    wire:click="removeExistingImage({{ $image['id'] }})"
                                    class="absolute right-3 top-3 inline-flex items-center justify-center rounded-full bg-rose-500/90 p-1.5 text-white opacity-0 shadow-sm transition hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-300/60 group-hover:opacity-100"
                                >
                                    <i data-lucide="x" class="h-4 w-4"></i>
                                </button>
                            </div>
                        @endforeach

                        @foreach ($imageInputs as $inputKey)
                            <div
                                wire:key="upload-field-{{ $inputKey }}"
                                class="relative flex h-full flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white/80 px-4 py-6 text-center text-sm text-slate-500 shadow-inner transition hover:border-emerald-300 hover:bg-emerald-50/40 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-300 dark:hover:border-emerald-500 dark:hover:bg-slate-900/60"
                            >
                                @if (isset($uploads[$inputKey]) && $uploads[$inputKey])
                                    <img
                                        src="{{ $uploads[$inputKey]->temporaryUrl() }}"
                                        alt="Previsualización"
                                        class="mb-3 aspect-[4/3] w-full rounded-xl object-cover"
                                    >
                                    <button
                                        type="button"
                                        wire:click="removeImageInput('{{ $inputKey }}')"
                                        class="inline-flex items-center gap-1 rounded-xl bg-rose-500 px-3 py-1 text-xs font-semibold text-white shadow-sm transition hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-200/70"
                                    >
                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                        Quitar
                                    </button>
                                @else
                                    <label class="flex w-full cursor-pointer flex-col items-center gap-3">
                                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 shadow-sm dark:bg-emerald-900/40 dark:text-emerald-200">
                                            <i data-lucide="upload-cloud" class="h-5 w-5"></i>
                                        </span>
                                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400 dark:text-slate-500">
                                            Seleccionar
                                        </span>
                                        <input
                                            type="file"
                                            accept=".jpg,.jpeg,.png,.webp"
                                            wire:model="uploads.{{ $inputKey }}"
                                            class="sr-only"
                                        >
                                        <span class="text-[0.7rem] text-slate-400 dark:text-slate-500">
                                            Arrastra o haz clic para subir
                                        </span>
                                    </label>
                                    <button
                                        type="button"
                                        wire:click="removeImageInput('{{ $inputKey }}')"
                                        class="absolute right-3 top-3 inline-flex items-center justify-center rounded-full bg-slate-200/90 p-1.5 text-slate-500 shadow-sm transition hover:bg-rose-500/90 hover:text-white focus:outline-none focus:ring-2 focus:ring-rose-200/70 dark:bg-slate-700/80 dark:text-slate-300 dark:hover:bg-rose-500 dark:hover:text-white"
                                    >
                                        <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                    </button>
                                @endif

                                @error('uploads.' . $inputKey)
                                    <p class="mt-3 text-xs font-medium text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
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
                        wire:target="save,uploads"
                        class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/40 transition hover:from-emerald-500 hover:to-teal-400 focus:outline-none focus:ring-2 focus:ring-emerald-300"
                    >
                        <i data-lucide="{{ $editingId ? 'save' : 'sparkles' }}" class="h-4 w-4"></i>
                        <span wire:loading.remove wire:target="save">{{ $editingId ? 'Actualizar producto' : 'Crear producto' }}</span>
                        <span wire:loading wire:target="save">Guardando...</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Tips de catálogo</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">
                        Mantén fotos actualizadas para que el equipo de campo identifique rápidamente el producto correcto.
                    </p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                    <i data-lucide="sparkle" class="h-3.5 w-3.5"></i>
                    Mejores prácticas
                </span>
            </div>

            <ul class="mt-6 space-y-4 text-sm text-slate-500 dark:text-slate-300">
                <li class="flex items-start gap-3">
                    <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-200">
                        <i data-lucide="image" class="h-3.5 w-3.5"></i>
                    </span>
                    <span>Usa la primera imagen como referencia principal; será la miniatura en listados y evaluaciones.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-200">
                        <i data-lucide="folder-symlink" class="h-3.5 w-3.5"></i>
                    </span>
                    <span>Agrupa productos por categoría para facilitar filtros y reportes segmentados.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-200">
                        <i data-lucide="shield-check" class="h-3.5 w-3.5"></i>
                    </span>
                    <span>Si se detectan evaluaciones vinculadas, el sistema mantendrá el producto como referencia inactiva en lugar de eliminarlo.</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Productos registrados</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">
                    Visualiza el catálogo, filtra por categoría o estado y gestiona ediciones rápidas.
                </p>
            </div>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto_auto]">
            <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus-within:border-emerald-400 focus-within:ring-2 focus-within:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus-within:border-emerald-400">
                <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                <input
                    type="search"
                    placeholder="Buscar por nombre, categoría o presentación..."
                    wire:model.live.debounce.400ms="search"
                    class="w-full border-none bg-transparent text-sm text-slate-700 focus:outline-none dark:text-slate-100"
                />
            </div>

            <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus-within:border-emerald-400 focus-within:ring-2 focus-within:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus-within:border-emerald-400">
                <i data-lucide="package" class="h-4 w-4 text-slate-400"></i>
                <select
                    wire:model.live="categoryFilter"
                    class="w-full border-none bg-transparent text-sm text-slate-700 focus:outline-none dark:text-slate-100"
                >
                    <option value="">Todas las categorías</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus-within:border-emerald-400 focus-within:ring-2 focus-within:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus-within:border-emerald-400">
                <i data-lucide="toggle-right" class="h-4 w-4 text-slate-400"></i>
                <select
                    wire:model.live="statusFilter"
                    class="w-full border-none bg-transparent text-sm text-slate-700 focus:outline-none dark:text-slate-100"
                >
                    <option value="">Todos los estados</option>
                    <option value="active">Sólo activos</option>
                    <option value="inactive">Sólo inactivos</option>
                </select>
            </div>
        </div>

        <div class="relative mt-6 overflow-hidden">
            <div wire:loading.flex wire:target="search,categoryFilter,statusFilter,delete,edit,save,uploads,removeExistingImage" class="absolute inset-0 z-10 hidden items-center justify-center bg-white/80 backdrop-blur dark:bg-slate-900/80">
                <div class="flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white px-4 py-3 text-sm font-medium text-slate-500 shadow-sm dark:border-slate-700/60 dark:bg-slate-900/80 dark:text-slate-300">
                    <span class="h-2.5 w-2.5 animate-ping rounded-full bg-emerald-500"></span>
                    Actualizando listado...
                </div>
            </div>

            <table class="w-full table-fixed border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.25em] text-slate-400 dark:text-slate-500">
                        <th class="rounded-l-2xl bg-slate-100 px-4 py-2 dark:bg-slate-800/70">Imagen</th>
                        <th class="bg-slate-100 px-4 py-2 dark:bg-slate-800/70">Nombre</th>
                        <th class="bg-slate-100 px-4 py-2 dark:bg-slate-800/70">Categoría</th>
                        <th class="bg-slate-100 px-4 py-2 dark:bg-slate-800/70">Presentación</th>
                        <th class="bg-slate-100 px-4 py-2 dark:bg-slate-800/70">Estado</th>
                        <th class="rounded-r-2xl bg-slate-100 px-4 py-2 text-center dark:bg-slate-800/70">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($products as $product)
                        @php
                            $cover = $product->images->first();
                        @endphp
                        <tr class="rounded-3xl border border-slate-200/80 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/80">
                            <td class="rounded-l-3xl px-4 py-4">
                                <div class="flex items-center justify-center">
                                    @if ($cover)
                                        <img
                                            src="{{ Storage::disk('public')->url($cover->path) }}"
                                            alt="Miniatura del producto"
                                            class="h-14 w-14 rounded-2xl object-cover shadow-sm"
                                        >
                                    @else
                                        <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 shadow-inner dark:bg-slate-800 dark:text-slate-500">
                                            <i data-lucide="package-open" class="h-6 w-6"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 align-middle">
                                <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $product->name }}</div>
                                @if ($product->short_description)
                                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                        {{ Str::limit($product->short_description, 70) }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-middle text-slate-600 dark:text-slate-300">
                                {{ $product->category ?? '—' }}
                            </td>
                            <td class="px-4 py-4 align-middle text-slate-600 dark:text-slate-300">
                                {{ $product->presentation ?? '—' }}
                            </td>
                            <td class="px-4 py-4 align-middle">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200' : 'bg-slate-200 text-slate-600 dark:bg-slate-800/70 dark:text-slate-400' }}">
                                    <span class="h-2 w-2 rounded-full {{ $product->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="rounded-r-3xl px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $product->id }})"
                                        class="inline-flex items-center gap-1 rounded-md bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 shadow-sm transition hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-200 dark:bg-slate-700/70 dark:text-slate-200 dark:hover:bg-slate-700"
                                    >
                                        <i data-lucide="pencil-line" class="h-3.5 w-3.5"></i>
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="delete({{ $product->id }})"
                                        x-data="{}"
                                        x-on:click="if (! confirm('¿Eliminar el producto {{ addslashes($product->name) }}? Si tiene evaluaciones se marcará como inactivo.')) { $event.stopImmediatePropagation(); return false; }"
                                        class="inline-flex items-center gap-1 rounded-md bg-rose-500 px-3 py-1 text-xs font-semibold text-white shadow-sm transition hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-200/70 dark:bg-rose-500 dark:hover:bg-rose-600"
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
                                Aún no registras productos. Crea el primero para compartir la referencia con evaluaciones y reportes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</section>
