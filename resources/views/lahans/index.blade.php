<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Daftar Lahan</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-xl bg-success/10 p-4 text-sm text-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex justify-end">
                <a class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90"
                    href="{{ route('lahans.create') }}">
                    + Tambah Lahan
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($lahans as $lahan)
                    <x-data-card accent="primary">
                        <div class="mb-3 flex items-start justify-between">
                            <div>
                                <p class="font-medium text-ink">
                                    <a class="hover:underline"
                                        href="{{ route('lahans.show', $lahan) }}">{{ $lahan->nama }}</a>
                                </p>
                                <x-badge
                                    tone="primary">{{ str_replace('_', ' ', ucfirst($lahan->fase_saat_ini)) }}</x-badge>
                            </div>
                            @if ($lahan->trouble_aktif_count > 0)
                                <x-badge tone="danger">{{ $lahan->trouble_aktif_count }} trouble</x-badge>
                            @endif
                        </div>

                        <p class="mb-3 text-xs text-ink-muted">
                            {{ $lahan->luas_panjang_m }}m × {{ $lahan->luas_lebar_m }}m ·
                            {{ $lahan->estimasi_jumlah_pohon ?? '-' }} pohon
                        </p>

                        <div class="mb-3 grid grid-cols-2 gap-3 border-b border-line pb-3">
                            <div>
                                <p class="text-xs text-ink-muted">Total Profit</p>
                                <p
                                    class="{{ $lahan->total_profit >= 0 ? 'text-success' : 'text-danger' }} text-sm font-semibold">
                                    Rp {{ number_format($lahan->total_profit, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-ink-muted">Total Transaksi</p>
                                <p class="text-sm font-semibold text-ink">{{ $lahan->transactions_count }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <a class="text-primary hover:underline" href="{{ route('lahans.show', $lahan) }}">Lihat
                                Detail</a>
                            <div class="space-x-2">
                                <a class="text-warn hover:underline" href="{{ route('lahans.edit', $lahan) }}">Edit</a>
                                <form action="{{ route('lahans.destroy', $lahan) }}" class="inline" method="POST"
                                    onsubmit="return confirm('Yakin hapus lahan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-danger hover:underline" type="submit">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </x-data-card>
                @empty
                    <x-empty-state message="Belum ada lahan." />
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
