<div class="space-y-6 rounded-3xl border border-border bg-card p-8 shadow-md shadow-purple-500/10">
    <div class="space-y-2">
        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-muted-foreground">Acceso</p>
        <h2 class="text-2xl font-bold text-foreground">Bienvenido</h2>
        <p class="text-sm text-muted-foreground">
            Ingresa tus credenciales para continuar en {{ config('app.name', 'Berry Quality Inspector') }}.
        </p>
    </div>

    @if ($errors->has('identifier') && ! session()->has('status'))
        <div class="flex items-start gap-3 rounded-2xl border border-destructive/40 bg-destructive/10 px-4 py-3 text-sm text-destructive">
            <i data-lucide="x-octagon" class="mt-0.5 h-4 w-4"></i>
            <div>
                <p class="font-semibold">Usuario o contraseña incorrectos</p>
                <p class="text-xs text-destructive/80">
                    Por favor verifica tus datos e inténtalo de nuevo.
                </p>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="authenticate" class="space-y-5">
        <div class="space-y-4">
            <label class="block space-y-2 text-sm font-medium text-foreground">
                <span>Usuario o Email</span>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-muted-foreground">
                        <i data-lucide="user" class="h-4 w-4"></i>
                    </span>
                    <input
                        type="text"
                        wire:model.defer="identifier"
                        autocomplete="username"
                        placeholder="usuario o email@ejemplo.com"
                        class="h-11 w-full rounded-md border border-input bg-background pl-10 pr-4 text-sm text-foreground transition duration-200 placeholder:text-muted-foreground/70 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/30"
                    >
                </div>
                @error('identifier')
                    <span class="flex items-center gap-2 text-xs font-semibold text-destructive">
                        <i data-lucide="alert-circle" class="h-3.5 w-3.5"></i>
                        {{ $message }}
                    </span>
                @enderror
            </label>

            <label class="block space-y-2 text-sm font-medium text-foreground">
                <span>Contraseña</span>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-muted-foreground">
                        <i data-lucide="lock" class="h-4 w-4"></i>
                    </span>
                    <button
                        type="button"
                        wire:click="togglePassword"
                        class="absolute inset-y-0 right-3 flex items-center text-muted-foreground transition hover:text-primary focus:outline-none"
                    >
                        <i data-lucide="{{ $showPassword ? 'eye-off' : 'eye' }}" class="h-4 w-4"></i>
                    </button>
                    <input
                        type="{{ $showPassword ? 'text' : 'password' }}"
                        wire:model.defer="password"
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="h-11 w-full rounded-md border border-input bg-background pl-10 pr-10 text-sm text-foreground transition duration-200 placeholder:text-muted-foreground/70 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/30"
                    >
                </div>
                @error('password')
                    <span class="flex items-center gap-2 text-xs font-semibold text-destructive">
                        <i data-lucide="alert-circle" class="h-3.5 w-3.5"></i>
                        {{ $message }}
                    </span>
                @enderror
            </label>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <label class="inline-flex items-center gap-2 text-sm text-muted-foreground">
                <input
                    type="checkbox"
                    wire:model="remember"
                    class="h-4 w-4 rounded border-input text-primary focus:ring-primary/40"
                >
                Recordar mi usuario
            </label>
            <a href="#" class="text-sm font-medium text-primary transition hover:text-primary/80">
                ¿Olvidaste tu contraseña?
            </a>
        </div>

        <button
            type="submit"
            class="relative inline-flex w-full items-center justify-center gap-2 rounded-md bg-gradient-to-r from-[#9333EA] to-[#7C3AED] px-4 py-3 text-sm font-semibold text-white shadow-md shadow-purple-500/30 transition hover:shadow-lg hover:shadow-purple-500/40 focus:outline-none focus:ring-2 focus:ring-primary/40 disabled:cursor-not-allowed disabled:opacity-70"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove wire:target="authenticate">Iniciar sesión</span>
            <span wire:loading wire:target="authenticate" class="flex items-center gap-2">
                <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                Iniciando sesión...
            </span>
        </button>

        <p class="text-center text-xs text-muted-foreground">
            Acceso restringido a personal autorizado. Se registrarán los intentos de inicio de sesión.
        </p>
    </form>
</div>
