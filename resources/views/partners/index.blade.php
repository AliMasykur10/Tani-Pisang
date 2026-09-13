<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Partner</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-xl bg-success/10 p-4 text-sm text-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 grid grid-cols-3 gap-4">
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Penyedia & Pembeli</p>
                    <p class="mt-1 text-lg font-bold text-ink">{{ $totalPenyediaPembeli }}</p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Pemilik Lahan</p>
                    <p class="mt-1 text-lg font-bold text-ink">{{ $totalPemilikLahan }}</p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Kesepakatan Aktif</p>
                    <p class="mt-1 text-lg font-bold text-primary">{{ $totalKesepakatanAktif }}</p>
                </div>
            </div>

            <div class="mb-6 flex justify-end">
                <a class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90"
                    href="{{ route('partners.create') }}">
                    + Tambah Partner
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @forelse ($partners as $partner)
                    <x-data-card accent="primary">
                        <div class="mb-3 flex items-start justify-between">
                            <div>
                                <p class="font-medium text-ink">{{ $partner->nama }}</p>
                                <x-badge tone="primary">
                                    {{ $partner->tipe === 'penyedia_pembeli' ? 'Penyedia Bibit & Pembeli' : 'Pemilik Lahan' }}
                                </x-badge>
                            </div>
                        </div>

                        @if ($partner->kontak)
                            <p class="mb-2 text-sm text-ink-muted">{{ $partner->kontak }}</p>
                        @endif

                        @if ($partner->agreements->where('is_active', true)->count() > 0)
                            <div class="mb-3 space-y-1 border-t border-line pt-3 text-sm">
                                @foreach ($partner->agreements->where('is_active', true) as $agreement)
                                    <p class="text-ink">
                                        {{ $agreement->lahan->nama }} —
                                        {{ $agreement->skema === 'sewa'
                                            ? 'Sewa Rp ' . number_format($agreement->nominal_sewa ?? 0, 0, ',', '.')
                                            : 'Bagi hasil ' . $agreement->persentase_bagi_hasil . '%' }}
                                    </p>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center justify-between border-t border-line pt-2 text-xs">
                            <a class="text-primary hover:underline" href="{{ route('partners.show', $partner) }}">Lihat
                                Detail</a>
                            <div class="space-x-2">
                                <a class="text-warn hover:underline"
                                    href="{{ route('partners.edit', $partner) }}">Edit</a>
                                <form action="{{ route('partners.destroy', $partner) }}" class="inline" method="POST"
                                    onsubmit="return confirm('Yakin hapus partner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-danger hover:underline" type="submit">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </x-data-card>
                @empty
                    <x-empty-state message="Belum ada partner." />
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
