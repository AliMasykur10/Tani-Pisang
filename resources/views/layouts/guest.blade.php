<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-bg">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">

        <a href="/" class="flex items-center gap-2 mb-6">
            <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-white text-base font-semibold">P</div>
            <span class="font-semibold text-ink text-lg">Tani Pisang</span>
        </a>

        <div class="w-full sm:max-w-md bg-surface rounded-xl border border-line shadow-sm px-6 py-8">
            {{ $slot }}
        </div>

    </div>
</body>
</html>