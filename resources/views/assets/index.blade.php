<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Aset</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-xl bg-success/10 p-4 text-sm text-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Total Nilai Aset</p>
                    <p class="mt-1 text-lg font-bold text-ink">Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Kondisi Baik</p>
                    <p class="mt-1 text-lg font-bold text-success">{{ $totalBaik }}</p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Perlu Servis</p>
                    <p class="mt-1 text-lg font-bold text-warn">{{ $totalPerluServis }}</p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Rusak</p>
                    <p class="mt-1 text-lg font-bold text-danger">{{ $totalRusak }}</p>
                </div>
            </div>

            <div class="mb-6 flex justify-end">
                <a class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90"
                    href="{{ route('assets.create') }}">
                    + Tambah Aset
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($assets as $asset)
                    <x-data-card :accent="$asset->kondisi === 'baik' ? 'success' : ($asset->kondisi === 'rusak' ? 'danger' : 'warn')">
                        <div class="mb-2 flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-ink">
                                    <a class="hover:underline"
                                        href="{{ route('assets.show', $asset) }}">{{ $asset->nama }}</a>
                                </p>
                                <p class="text-xs text-ink-muted">{{ $asset->jenis ?? '-' }}</p>
                            </div>
                            <x-badge :tone="$asset->kondisi === 'baik' ? 'success' : ($asset->kondisi === 'rusak' ? 'danger' : 'warn')">
                                {{ str_replace('_', ' ', ucfirst($asset->kondisi)) }}
                            </x-badge>
                        </div>

                        <p class="mb-2 text-lg font-semibold text-ink">
                            Rp {{ number_format($asset->harga_beli ?? 0, 0, ',', '.') }}
                        </p>

                        <p class="mb-3 text-xs text-ink-muted">
                            Dipakai di: {{ $asset->allocations->pluck('lahan.nama')->join(', ') ?: '-' }}
                        </p>

                        <div class="flex items-center justify-between border-t border-line pt-2 text-xs">
                            <span class="text-ink-muted">{{ $asset->tanggal_beli?->format('d M Y') ?? '-' }}</span>
                            <div class="space-x-2">
                                <a class="text-warn hover:underline" href="{{ route('assets.edit', $asset) }}">Edit</a>
                                <form action="{{ route('assets.destroy', $asset) }}" class="inline" method="POST"
                                    onsubmit="return confirm('Yakin hapus aset ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-danger hover:underline" type="submit">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </x-data-card>
                @empty
                    <x-empty-state message="Belum ada aset." />
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
