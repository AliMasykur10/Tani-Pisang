<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Panen {{ $panenCycle->lahan->nama }} — Siklus #{{ $panenCycle->nomor_siklus }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-2xl space-y-6 sm:px-6 lg:px-8">

            <div class="space-y-2 overflow-hidden bg-white p-6 text-sm shadow-sm sm:rounded-lg">
                <div><span class="text-gray-500">Tanggal Panen:</span> {{ $panenCycle->tanggal_panen->format('d M Y') }}
                </div>
                <div><span class="text-gray-500">Jumlah Pohon Produktif:</span> {{ $panenCycle->jumlah_pohon_produktif }}
                </div>
                <div><span class="text-gray-500">Total Hasil:</span> {{ number_format($panenCycle->total_hasil_kg, 1) }}
                    kg</div>
                <div><span class="text-gray-500">Harga per Kg:</span> Rp
                    {{ number_format($panenCycle->harga_per_kg, 0, ',', '.') }}</div>
                <div><span class="text-gray-500">Total Pemasukan:</span> <span class="font-medium text-green-600">Rp
                        {{ number_format($panenCycle->total_pemasukan, 0, ',', '.') }}</span></div>
                <div><span class="text-gray-500">Hasil per Pohon:</span>
                    {{ $panenCycle->jumlah_pohon_produktif > 0 ? number_format($panenCycle->total_hasil_kg / $panenCycle->jumlah_pohon_produktif, 2) : 0 }}
                    kg/pohon</div>
            </div>

            @if ($panenCycle->anakanRecord)
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="mb-3 font-medium">Pengelolaan Anakan</h3>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-500">Jumlah Muncul:</span>
                            {{ $panenCycle->anakanRecord->jumlah_muncul }}</div>
                        <div><span class="text-gray-500">Disisakan (siklus berikutnya):</span>
                            {{ $panenCycle->anakanRecord->jumlah_disisakan }}</div>
                        <div><span class="text-gray-500">Dijual sebagai bibit:</span>
                            {{ $panenCycle->anakanRecord->jumlah_dijual }}</div>
                        <div><span class="text-gray-500">Dipindah ke lahan lain:</span>
                            {{ $panenCycle->anakanRecord->jumlah_dipindah_lahan_lain }}
                            @if ($panenCycle->anakanRecord->lahanTujuan)
                                (ke {{ $panenCycle->anakanRecord->lahanTujuan->nama }})
                            @endif
                        </div>
                        <div><span class="text-gray-500">Dibuang / jadi pupuk:</span>
                            {{ $panenCycle->anakanRecord->jumlah_dibuang }}</div>
                    </div>
                </div>
            @endif

            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="mb-3 font-medium">Transaksi Terkait</h3>
                <div class="space-y-2 text-sm">
                    @forelse ($panenCycle->transactions as $t)
                        <div class="rounded border p-2">
                            <p>{{ $t->kategori }} — Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                {{ !$t->is_cash ? '(non-kas)' : '' }}</p>
                            <p class="text-xs text-gray-500">{{ $t->keterangan }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">Tidak ada transaksi terkait.</p>
                    @endforelse
                </div>
            </div>

            <a class="text-sm text-blue-600 hover:underline" href="{{ route('panen-cycles.index') }}">&larr; Kembali ke
                daftar</a>

        </div>
    </div>
</x-app-layout>
