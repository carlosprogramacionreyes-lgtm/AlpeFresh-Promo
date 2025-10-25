<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Alpe Fresh Promotoras') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $navigation = config('navigation');
        $menuItems = $navigation['items'] ?? [];
        $currentUser = auth()->user();
    @endphp
    <body
        x-data="appLayout()"
        x-init="init(); return () => destroy()"
        x-bind:class="{'dark': darkMode}"
        class="min-h-full bg-slate-50 font-sans antialiased text-slate-900 transition-colors dark:bg-slate-950 dark:text-slate-100"
    >
        <div class="flex min-h-screen">
            <div
                x-cloak
                x-show="mobileSidebarOpen && !isDesktop"
                @click="closeMobileSidebar()"
                class="fixed inset-0 z-20 bg-slate-950/70 backdrop-blur-sm transition-opacity lg:hidden"
            ></div>

            <aside
                class="fixed inset-y-0 left-0 z-30 flex transform flex-col border-r border-white/10 bg-gradient-to-br from-[#4C1D95] via-[#5B21B6] to-[#14B8A6] text-slate-50 shadow-2xl shadow-purple-900/30 transition-all duration-300 lg:translate-x-0"
                :class="{
                    '-translate-x-full': !mobileSidebarOpen && !isDesktop,
                    'translate-x-0': mobileSidebarOpen || isDesktop,
                    'w-16': sidebarCollapsed && isDesktop,
                    'w-[260px]': !sidebarCollapsed || !isDesktop
                }"
            >
                <div class="flex h-20 items-center justify-between px-5 py-3" :class="sidebarCollapsed ? 'px-2.5' : 'px-5'">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-xl font-semibold shadow-inner">
                            AF
                        </div>
                        <div x-show="!sidebarCollapsed" x-transition class="space-y-0.5">
                            <p class="text-[10px] uppercase tracking-[0.35em] text-white/70">Alpe Fresh</p>
                            <p class="text-sm font-semibold text-white">{{ $navigation['brand']['name'] ?? 'Promotoras' }}</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        class="hidden rounded-full border border-white/20 bg-white/10 p-2 text-white transition hover:bg-white/20 lg:inline-flex"
                        @click="toggleMobileSidebar()"
                        :title="sidebarCollapsed ? 'Expandir menú' : 'Colapsar menú'"
                    >
                        <i data-lucide="panel-right-close" x-show="!sidebarCollapsed" class="h-4 w-4"></i>
                        <i data-lucide="panel-right-open" x-show="sidebarCollapsed" class="h-4 w-4"></i>
                    </button>
                </div>

                <nav class="flex-1 space-y-3 overflow-y-auto px-2 pb-5" :class="sidebarCollapsed ? 'px-2' : 'px-3.5'">
                    @foreach ($menuItems as $item)
                        @php
                            $isGroup = isset($item['children']);
                            $isActive = false;
                            if (! $isGroup && isset($item['route'])) {
                                $isActive = request()->routeIs($item['route']);
                            }
                            if ($isGroup) {
                                $isActive = collect($item['children'])->contains(fn ($child) => isset($child['route']) && request()->routeIs($child['route']));
                            }
                        @endphp
                        <div @class(['space-y-2' => $isGroup])>
                            @if ($isGroup)
                                <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="space-y-2">
                                    <button
                                        type="button"
                                        @class([
                                            'group flex w-full items-center gap-2.5 rounded-xl px-3 py-1.5 text-[13px] font-medium transition-all duration-200',
                                            'bg-white/20 text-white shadow-inner shadow-white/10' => $isActive,
                                            'hover:bg-white/10 hover:text-white/90' => ! $isActive,
                                        ])
                                        @click="open = !open"
                                    >
                                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/10 text-white shadow-inner shadow-purple-900/40">
                                            <i data-lucide="{{ $item['icon'] ?? 'circle' }}" class="h-4 w-4"></i>
                                        </span>
                                        <span
                                            x-show="!sidebarCollapsed"
                                            x-transition
                                            class="flex-1 text-left text-[13px]"
                                        >
                                            {{ $item['label'] }}
                                        </span>
                                        <span x-show="!sidebarCollapsed" x-transition class="text-xs text-white/60">
                                            <i data-lucide="chevron-down" class="h-4 w-4" x-bind:class="{'rotate-180': open}"></i>
                                        </span>
                                    </button>

                                    <div
                                        x-show="open"
                                        x-transition
                                        class="space-y-1 pl-4"
                                    >
                                        @foreach ($item['children'] as $child)
                                            @php
                                                $childActive = isset($child['route']) && request()->routeIs($child['route']);
                                            @endphp
                                            <a
                                                @class([
                                                    'flex items-center gap-2.5 rounded-xl px-3 py-1.5 text-[13px] transition duration-200',
                                                    'bg-white/20 text-white shadow-inner shadow-white/10' => $childActive,
                                                    'text-white/80 hover:bg-white/10 hover:text-white' => ! $childActive,
                                                ])
                                                @if (isset($child['route']))
                                                    wire:navigate
                                                    href="{{ route($child['route']) }}"
                                                @endif
                                                @click="closeMobileSidebar()"
                                            >
                                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/10">
                                                    <i data-lucide="{{ $child['icon'] ?? 'circle' }}" class="h-4 w-4"></i>
                                                </span>
                                                <span x-show="!sidebarCollapsed" x-transition class="text-[13px]">{{ $child['label'] }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a
                                    @class([
                                        'group flex items-center gap-2.5 rounded-xl px-3 py-1.5 text-[13px] font-medium transition-all duration-200',
                                        'bg-white/20 text-white shadow-inner shadow-white/10' => $isActive,
                                        'hover:bg-white/10 hover:text-white/90' => ! $isActive,
                                    ])
                                    @if(isset($item['route']))
                                        wire:navigate
                                        href="{{ route($item['route']) }}"
                                    @endif
                                    @click="closeMobileSidebar()"
                                >
                                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/10 text-white shadow-inner shadow-purple-900/40">
                                        <i data-lucide="{{ $item['icon'] ?? 'circle' }}" class="h-4 w-4"></i>
                                    </span>
                                    <span
                                        x-show="!sidebarCollapsed"
                                        x-transition
                                        class="flex-1 text-left text-[13px]"
                                    >
                                        {{ $item['label'] }}
                                    </span>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </nav>
            </aside>

            <div class="flex flex-1 flex-col" :class="sidebarCollapsed && isDesktop ? 'lg:pl-16' : 'lg:pl-[260px]'">
                <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 px-4 py-4 shadow-sm backdrop-blur dark:border-slate-700 dark:bg-slate-900/80">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-purple-300 hover:text-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-400/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-teal-400 dark:hover:text-teal-300"
                                @click="toggleMobileSidebar()"
                            >
                                <i data-lucide="menu" class="h-5 w-5"></i>
                            </button>
                            <div>
                                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">
                                    {{ now()->translatedFormat('l d \\d\\e F \\d\\e Y') }}
                                </p>
                                <h1 class="text-xl font-semibold text-slate-900 dark:text-white">
                                    {{ $pageTitle ?? ($title ?? 'Panel principal') }}
                                </h1>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-purple-300 hover:text-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-400/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-teal-400 dark:hover:text-teal-300"
                                @click="toggleDark()"
                                :title="darkMode ? 'Modo claro' : 'Modo oscuro'"
                            >
                                <i data-lucide="moon-star" x-show="!darkMode" class="h-5 w-5"></i>
                                <i data-lucide="sun" x-show="darkMode" class="h-5 w-5"></i>
                            </button>
                            <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-teal-400 text-sm font-semibold text-white shadow-lg">
                                    {{ strtoupper(substr($currentUser?->first_name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="hidden text-right sm:block">
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-100">
                                        {{ $currentUser?->full_name }}
                                    </p>
                                    <p class="text-xs uppercase tracking-wide text-teal-500">
                                        {{ $currentUser?->role?->label() ?? '' }}
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-600 to-teal-500 text-white shadow-md shadow-purple-500/30 transition hover:from-purple-500 hover:to-teal-400"
                                        title="Cerrar sesión"
                                    >
                                        <i data-lucide="log-out" class="h-4 w-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main
                    class="flex-1 bg-slate-50/80 px-4 py-8 transition-[padding] duration-300 dark:bg-slate-950/80"
                    :class="mainPadding"
                >
                    <div
                        class="mx-auto flex w-full flex-col transition-[max-width] duration-300"
                        :class="contentContainer"
                    >
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
