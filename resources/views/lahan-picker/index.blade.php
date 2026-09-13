<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-ink">
            Pilih Lahan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">

            <p class="mb-6 text-sm text-ink-muted">
                {{ auth()->user()->role === 'admin' ? 'Pilih lahan untuk melihat data spesifiknya, atau lihat semua lahan sekaligus dari dashboard.' : 'Pilih lahan yang ingin kamu catat perkembangannya.' }}
            </p>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach ($lahans as $lahan)
                    <form action="{{ route('lahan-picker.select', $lahan) }}" method="POST">
                        @csrf
                        <button
                            class="w-full rounded-xl border border-line bg-surface p-5 text-left transition-all hover:border-primary hover:shadow-sm"
                            type="submit">
                            <p class="font-medium text-ink">{{ $lahan->nama }}</p>
                            <x-badge
                                tone="primary">{{ str_replace('_', ' ', ucfirst($lahan->fase_saat_ini)) }}</x-badge>
                            <div class="mt-3 space-y-1 text-xs text-ink-muted">
                                <p>{{ $lahan->transactions_count }} transaksi tercatat</p>
                                @if ($lahan->trouble_reports_count > 0)
                                    <p class="text-danger">{{ $lahan->trouble_reports_count }} masalah aktif</p>
                                @endif
                            </div>
                        </button>
                    </form>
                @endforeach
            </div>

            @if (auth()->user()->role === 'admin')
                <form action="{{ route('lahan-picker.select-all') }}" class="mt-6" method="POST">
                    @csrf
                    <button class="text-sm text-primary hover:underline" type="submit">
                        &rarr; Lihat semua lahan sekaligus di dashboard
                    </button>
                </form>
            @endif

        </div>
    </div>
</x-app-layout>
