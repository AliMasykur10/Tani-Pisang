<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>Tani Pisang — Sistem Manajemen Perkebunan</title>

    <link href="https://fonts.bunny.net" rel="preconnect">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="/banana.png" rel="icon" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-bg font-sans text-ink antialiased">

    <div class="flex min-h-screen flex-col">

        {{-- Nav sederhana --}}
        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-5">
            <div class="flex items-center gap-2">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-semibold text-white">
                    P</div>
                <span class="font-semibold text-ink">Tani Pisang</span>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90"
                        href="{{ route('dashboard') }}">
                        Buka Dashboard
                    </a>
                @else
                    <a class="text-sm text-ink-muted hover:text-ink" href="{{ route('login') }}">Masuk</a>
                    @if (Route::has('register'))
                        <a class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90"
                            href="{{ route('register') }}">
                            Daftar
                        </a>
                    @endif
                @endauth
            </div>
        </header>

        {{-- Hero --}}
        <main class="flex flex-1 items-center">
            <div class="mx-auto w-full max-w-6xl px-6 py-16">
                <div class="max-w-2xl">
                    <span
                        class="mb-4 inline-block rounded-full bg-primary-tint px-3 py-1 text-xs font-medium text-primary">
                        Manajemen Perkebunan Pisang Cavendish
                    </span>
                    <h1 class="mb-4 text-4xl font-bold leading-tight text-ink md:text-5xl">
                        Kelola kebun pisang, dari lahan sampai laporan.
                    </h1>
                    <p class="mb-8 text-lg text-ink-muted">
                        Catat keuangan, pantau perkembangan lahan, kelola siklus panen, dan buat laporan konsolidasi —
                        semua dalam satu sistem, per lahan, mandiri.
                    </p>
                    <div class="flex gap-3">
                        @auth
                            <a class="rounded-lg bg-primary px-6 py-3 font-medium text-white hover:bg-primary/90"
                                href="{{ route('dashboard') }}">
                                Buka Dashboard
                            </a>
                        @else
                            <a class="rounded-lg bg-primary px-6 py-3 font-medium text-white hover:bg-primary/90"
                                href="{{ route('login') }}">
                                Masuk ke Sistem
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Fitur singkat --}}
                <div class="mt-16 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-line bg-surface p-5">
                        <p class="mb-1 font-medium text-ink">Keuangan per Lahan</p>
                        <p class="text-sm text-ink-muted">Pemasukan, pengeluaran, dan profit tercatat rapi, terpisah
                            tiap lahan.</p>
                    </div>
                    <div class="rounded-xl border border-line bg-surface p-5">
                        <p class="mb-1 font-medium text-ink">Progress & Trouble Report</p>
                        <p class="text-sm text-ink-muted">Pantau perkembangan dan masalah lahan dari jarak jauh, lengkap
                            dengan foto.</p>
                    </div>
                    <div class="rounded-xl border border-line bg-surface p-5">
                        <p class="mb-1 font-medium text-ink">Siklus Panen & Laporan</p>
                        <p class="text-sm text-ink-muted">Catat hasil tiap siklus panen dan ekspor laporan konsolidasi
                            kapan saja.</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-6 text-center text-xs text-ink-muted">
            Tani Pisang &copy; {{ date('Y') }}
        </footer>
    </div>

</body>

</html>
