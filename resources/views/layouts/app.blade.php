<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    
    <link href="https://fonts.bunny.net" rel="preconnect">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="/banana.png" rel="icon" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="flex min-h-screen bg-bg" x-data="{ sidebarOpen: false }">

        {{-- Overlay mobile --}}
        <div @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/30 lg:hidden" x-cloak x-show="sidebarOpen">
        </div>

        {{-- Sidebar --}}
        @include('layouts.side-bar')


        {{-- Main content --}}
        <div class="flex min-w-0 flex-1 flex-col">

            {{-- Top bar mobile --}}
            <div class="sticky top-0 z-20 flex items-center gap-3 border-b border-line bg-surface px-4 py-3 lg:hidden">
                <button @click="sidebarOpen = true" class="text-ink">
                    <svg class="h-6 w-6" fill="none" stroke-width="1.8" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <span class="font-semibold text-ink">Tani Pisang</span>
            </div>

            @isset($header)
                <header class="border-b border-line bg-surface px-4 py-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </header>
            @endisset

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
