<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $asset->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="space-y-3 overflow-hidden bg-white p-6 text-sm shadow-sm sm:rounded-lg">
                <div><span class="text-gray-500">Jenis:</span> {{ $asset->jenis ?? '-' }}</div>
                <div><span class="text-gray-500">Tanggal Beli:</span> {{ $asset->tanggal_beli?->format('d M Y') ?? '-' }}
                </div>
                <div><span class="text-gray-500">Harga Beli:</span> Rp
                    {{ number_format($asset->harga_beli ?? 0, 0, ',', '.') }}</div>
                <div><span class="text-gray-500">Kondisi:</span> {{ str_replace('_', ' ', ucfirst($asset->kondisi)) }}
                </div>

                <div class="border-t pt-3">
                    <p class="mb-1 text-gray-500">Alokasi Penggunaan:</p>
                    @foreach ($asset->allocations as $allocation)
                        <p>{{ $allocation->lahan->nama }} — {{ $allocation->porsi_persen }}%</p>
                    @endforeach
                </div>

                <div class="border-t pt-4">
                    <a class="text-blue-600 hover:underline" href="{{ route('assets.index') }}">&larr; Kembali ke
                        daftar</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
