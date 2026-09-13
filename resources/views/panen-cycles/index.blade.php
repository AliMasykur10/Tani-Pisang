<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Siklus Panen</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-success/10 text-success rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-surface rounded-xl border border-line p-4 text-center">
                    <p class="text-xs text-ink-muted">Total Hasil Keseluruhan</p>
                    <p class="text-lg font-bold text-ink mt-1">{{ number_format($totalHasilKeseluruhan, 1) }} kg</p>
                </div>
                <div class="bg-surface rounded-xl border border-line p-4 text-center">
                    <p class="text-xs text-ink-muted">Total Pemasukan dari Panen</p>
                    <p class="text-lg font-bold text-success mt-1">Rp {{ number_format($totalPemasukanPanen, 0, ',', '.') }}</p>
                </div>
                <div class="bg-surface rounded-xl border border-line p-4 text-center">
                    <p class="text-xs text-ink-muted">Rata-rata Hasil/Pohon</p>
                    <p class="text-lg font-bold text-ink mt-1">{{ $rataRataPerPohon }} kg</p>
                </div>
            </div>

            @if ($panenLabels->count() > 0)
            <div class="bg-surface rounded-xl border border-line p-6">
                <h3 class="font-medium text-ink mb-4">Tren Hasil Panen per Siklus</h3>
                <canvas id="panenTrendChart" height="90"></canvas>
            </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('panen-cycles.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 text-sm font-medium">
                    + Catat Panen Baru
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($panenCycles as $cycle)
                    <x-data-card accent="primary">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-medium text-ink text-sm">{{ $cycle->lahan->nama }}</p>
                                <p class="text-xs text-ink-muted">Siklus #{{ $cycle->nomor_siklus }}</p>
                            </div>
                            <x-badge tone="primary">{{ $cycle->tanggal_panen->format('d M Y') }}</x-badge>
                        </div>

                        <p class="text-lg font-semibold text-ink mb-1">
                            {{ number_format($cycle->total_hasil_kg, 1) }} kg
                        </p>
                        <p class="text-xs text-ink-muted mb-3">
                            {{ $cycle->jumlah_pohon_produktif }} pohon produktif ·
                            {{ $cycle->jumlah_pohon_produktif > 0 ? round($cycle->total_hasil_kg / $cycle->jumlah_pohon_produktif, 2) : 0 }} kg/pohon
                        </p>

                        <p class="text-sm font-medium text-success mb-3">
                            Rp {{ number_format($cycle->total_pemasukan, 0, ',', '.') }}
                        </p>

                        @if ($cycle->anakanRecord)
                            <div class="text-xs text-ink-muted border-t border-line pt-2 mb-3">
                                Anakan: {{ $cycle->anakanRecord->jumlah_muncul }} muncul
                                ({{ $cycle->anakanRecord->jumlah_dijual }} dijual, {{ $cycle->anakanRecord->jumlah_dipindah_lahan_lain }} dipindah)
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-2 border-t border-line text-xs">
                            <a href="{{ route('panen-cycles.show', $cycle) }}" class="text-primary hover:underline">Lihat Detail</a>
                            <form action="{{ route('panen-cycles.destroy', $cycle) }}" method="POST" onsubmit="return confirm('Yakin hapus siklus panen ini? Transaksi terkait juga akan dihapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger hover:underline">Hapus</button>
                            </form>
                        </div>
                    </x-data-card>
                @empty
                    <x-empty-state message="Belum ada siklus panen tercatat." />
                @endforelse
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($panenLabels->count() > 0)
            new Chart(document.getElementById('panenTrendChart'), {
                type: 'line',
                data: {
                    labels: @json($panenLabels),
                    datasets: [
                        {
                            label: 'Total Hasil (kg)',
                            data: @json($panenHasilKg),
                            borderColor: '#B8863A',
                            backgroundColor: '#B8863A20',
                            tension: 0.3,
                            fill: true,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Hasil per Pohon (kg)',
                            data: @json($panenHasilPerPohon),
                            borderColor: '#2F5233',
                            backgroundColor: 'transparent',
                            tension: 0.3,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        y: { type: 'linear', position: 'left', title: { display: true, text: 'kg total' } },
                        y1: { type: 'linear', position: 'right', title: { display: true, text: 'kg/pohon' }, grid: { drawOnChartArea: false } }
                    }
                }
            });
            @endif
        });
    </script>
    @endpush
</x-app-layout>