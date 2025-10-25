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
    <body class="min-h-screen bg-background font-sans text-foreground antialiased transition-colors duration-200">
        <div class="flex min-h-screen flex-col md:flex-row">
            <aside class="relative hidden w-full flex-1 items-center justify-center overflow-hidden bg-gradient-to-br from-[#9333EA] via-[#7C3AED] to-[#14B8A6] px-12 py-12 text-white md:flex">
                <div class="absolute inset-0">
                    <div class="absolute -left-24 bottom-20 h-72 w-72 rounded-full border border-white/20"></div>
                    <div class="absolute -right-20 top-24 h-60 w-60 rounded-full border border-white/20"></div>
                </div>
                <div class="relative z-10 flex max-w-md flex-col items-center text-center">
                    <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white/15 shadow-lg shadow-black/20 backdrop-blur">
                        <span class="text-4xl">🫐</span>
                    </div>
                    <h1 class="mt-6 text-3xl font-bold">{{ config('app.name', 'Berry Quality Inspector') }}</h1>
                    <p class="mt-4 text-base text-white/80">Sistema de Evaluación de Calidad en Campo</p>
                </div>
            </aside>

            <main class="flex min-h-screen w-full flex-1 items-center justify-center bg-background px-6 py-12 sm:px-10">
                <div class="w-full max-w-md space-y-6">
                    {{ $slot }}
                    <p class="text-center text-xs text-muted-foreground">
                        &copy; {{ now()->year }} {{ config('app.name', 'Berry Quality Inspector') }}. Todos los derechos reservados.
                    </p>
                </div>
            </main>
        </div>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
