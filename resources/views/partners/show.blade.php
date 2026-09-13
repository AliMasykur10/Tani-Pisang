<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $partner->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-2 text-sm">
                <div><span class="text-gray-500">Tipe:</span> {{ $partner->tipe === 'penyedia_pembeli' ? 'Penyedia Bibit & Pembeli' : 'Pemilik Lahan' }}</div>
                @if ($partner->kontak)
                    <div><span class="text-gray-500">Kontak:</span> {{ $partner->kontak }}</div>
                @endif
                @if ($partner->catatan)
                    <div><span class="text-gray-500">Catatan:</span> {{ $partner->catatan }}</div>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium mb-4">Riwayat Kesepakatan</h3>

                <div class="space-y-3 mb-6">
                    @forelse ($partner->agreements as $agreement)
                        <div class="border rounded p-3 text-sm {{ !$agreement->is_active ? 'opacity-50' : '' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">{{ $agreement->lahan->nama }}</p>
                                    <p>Skema: {{ $agreement->skema === 'sewa' ? 'Sewa' : 'Bagi Hasil' }}</p>
                                    @if ($agreement->skema === 'sewa')
                                        <p>Nominal Sewa: Rp {{ number_format($agreement->nominal_sewa ?? 0, 0, ',', '.') }}</p>
                                    @else
                                        <p>Persentase Bagi Hasil: {{ $agreement->persentase_bagi_hasil }}%</p>
                                    @endif
                                    <p class="text-gray-500 text-xs mt-1">
                                        Mulai {{ $agreement->tanggal_mulai->format('d M Y') }}
                                        @if ($agreement->tanggal_berakhir)
                                            — Berakhir {{ $agreement->tanggal_berakhir->format('d M Y') }}
                                        @endif
                                    </p>
                                    @if ($agreement->is_active)
                                        <span class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-800">Aktif</span>
                                    @endif
                                </div>
                                @if ($agreement->is_active)
                                    <a href="{{ route('partner-agreements.edit', $agreement) }}" class="text-yellow-600 hover:underline text-xs">Edit Nominal</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Belum ada kesepakatan.</p>
                    @endforelse
                </div>

                <a href="{{ route('partner-agreements.create') }}?partner_id={{ $partner->id }}" class="text-sm px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 inline-block">
                    + Tambah Kesepakatan Baru
                </a>
            </div>

            <a href="{{ route('partners.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke daftar</a>

        </div>
    </div>
</x-app-layout>