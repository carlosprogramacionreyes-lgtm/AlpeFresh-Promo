<section class="space-y-6">
    @if (session()->has('status'))
        <div class="rounded-3xl border border-emerald-200/80 bg-emerald-50 px-6 py-4 text-sm font-medium text-emerald-800 shadow-sm dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-100">
            {{ session('status') }}
        </div>
    @endif

    @error('activation')
        <div class="rounded-3xl border border-red-200/80 bg-red-50 px-6 py-4 text-sm font-medium text-red-700 shadow-sm dark:border-red-500/40 dark:bg-red-500/10 dark:text-red-100">
            {{ $message }}
        </div>
    @enderror
    @error('authorization')
        <div class="rounded-3xl border border-red-200/80 bg-red-50 px-6 py-4 text-sm font-medium text-red-700 shadow-sm dark:border-red-500/40 dark:bg-red-500/10 dark:text-red-100">
            {{ $message }}
        </div>
    @enderror
    @error('delete')
        <div class="rounded-3xl border border-red-200/80 bg-red-50 px-6 py-4 text-sm font-medium text-red-700 shadow-sm dark:border-red-500/40 dark:bg-red-500/10 dark:text-red-100">
            {{ $message }}
        </div>
    @enderror

    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div>
            <h3 class="text-xl font-semibold text-slate-900 dark:text-white">
                {{ $editingUserId ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
            </h3>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">
                {{ $editingUserId ? 'Actualiza la información del usuario seleccionado.' : 'Registra un nuevo usuario con las credenciales iniciales.' }}
            </p>
        </div>

        <form wire:submit.prevent="save" class="mt-6 grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300">Nombre completo</label>
                <input
                    type="text"
                    wire:model.defer="form.nombre_completo"
                    class="mt-1 w-full rounded-2xl border border-slate-200/60 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                >
                @error('form.nombre_completo') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300">Nombre de usuario</label>
                <input
                    type="text"
                    wire:model.defer="form.username"
                    class="mt-1 w-full rounded-2xl border border-slate-200/60 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                >
                @error('form.username') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300">Correo electrónico</label>
                <input
                    type="email"
                    wire:model.defer="form.email"
                    class="mt-1 w-full rounded-2xl border border-slate-200/60 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                >
                @error('form.email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300">
                    {{ $editingUserId ? 'Nueva contraseña (opcional)' : 'Contraseña' }}
                </label>
                <input
                    type="password"
                    wire:model.defer="form.password"
                    autocomplete="new-password"
                    class="mt-1 w-full rounded-2xl border border-slate-200/60 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                >
                @error('form.password') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300">Rol</label>
                <select
                    wire:model.defer="form.rol"
                    class="mt-1 w-full rounded-2xl border border-slate-200/60 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                >
                    @foreach ($roleOptions as $option)
                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                    @endforeach
                </select>
                @error('form.rol') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2 flex items-center justify-end gap-3">
                @if ($editingUserId)
                    <button
                        type="button"
                        wire:click="cancelEdit"
                        class="inline-flex items-center gap-2 rounded-2xl border border-slate-300/70 px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-slate-400 hover:text-slate-800 dark:border-slate-600 dark:text-slate-300 dark:hover:border-slate-500 dark:hover:text-white"
                    >
                        Cancelar
                    </button>
                @endif
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-br from-purple-600 to-teal-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-purple-500/40 transition hover:from-purple-500 hover:to-teal-400 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-500/60"
                >
                    {{ $editingUserId ? 'Guardar Cambios' : 'Crear Usuario' }}
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-0 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-900">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-3">Usuario</th>
                        <th class="px-6 py-3">Contacto</th>
                        <th class="px-6 py-3">Rol</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/70 dark:divide-slate-700/70">
                    @forelse ($users as $user)
                        <tr wire:key="user-{{ $user->id }}" class="bg-white transition hover:bg-slate-50 dark:bg-slate-900 dark:hover:bg-slate-800/80">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900 dark:text-white">{{ $user->nombre_completo }}</div>
                                <div class="mt-1 text-xs uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                    {{ $user->username }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                <div>{{ $user->email ?: '—' }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500">
                                    {{ $user->telefono ?: 'Sin teléfono' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                    {{ $user->role->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->activo)
                                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-100">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-red-700 dark:bg-red-500/20 dark:text-red-100">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Inactivo
                                    </span>
                                @endif
                                <div class="mt-2 text-xs text-slate-400 dark:text-slate-500">
                                    Último acceso: {{ optional($user->last_login_at)->diffForHumans() ?? 'Nunca' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="openEditForm({{ $user->id }})"
                                        class="inline-flex items-center gap-1 rounded-xl border border-slate-300/70 px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:border-slate-400 hover:text-slate-800 dark:border-slate-600 dark:text-slate-300 dark:hover:border-slate-500 dark:hover:text-white"
                                    >
                                        <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                        Editar
                                    </button>
                                    @can('delete-users')
                                        @if (auth()->id() !== $user->id)
                                        <button
                                            type="button"
                                            x-data="{}"
                                            x-on:click.prevent="if (confirm('¿Eliminar este usuario? Esta acción es permanente.')) { $wire.delete({{ $user->id }}) }"
                                            class="inline-flex items-center gap-1 rounded-xl border border-transparent px-3 py-1.5 text-xs font-semibold transition focus:outline-none focus:ring-2 focus:ring-red-200 focus:ring-offset-1 dark:focus:ring-red-400 dark:focus:ring-offset-slate-900"
                                            style="background-color: #991b1b; color: #ffffff;"
                                        >
                                            <span class="inline-flex items-center gap-1">
                                                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                <span>Eliminar</span>
                                            </span>
                                        </button>
                                        @endif
                                    @endcan
                                    @can('toggle-user-status')
                                        <button
                                            type="button"
                                            wire:click="toggleActivation({{ $user->id }})"
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center gap-1 rounded-xl border border-transparent px-3 py-1.5 text-xs font-medium text-white transition focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-slate-900
                                                {{ $user->is_active ? 'bg-red-500 hover:bg-red-400 focus:ring-red-200' : 'bg-emerald-600 hover:bg-emerald-500 focus:ring-emerald-200' }}"
                                        >
                                            <i data-lucide="{{ $user->is_active ? 'user-x' : 'user-check' }}" class="h-3.5 w-3.5"></i>
                                            {{ $user->activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400 dark:text-slate-500">
                                No se encontraron usuarios con los filtros actuales.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200/70 px-6 py-4 dark:border-slate-700">
            {{ $users->links() }}
        </div>
    </div>
</section>
