<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-ink">{{ $lahan->nama }}</h2>
            <a class="text-sm text-primary hover:underline" href="{{ route('lahans.edit', $lahan) }}">Edit Info Lahan</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-6xl space-y-6 sm:px-6 lg:px-8">

            <div class="flex items-center gap-2">
                <x-badge tone="primary">{{ str_replace('_', ' ', ucfirst($lahan->fase_saat_ini)) }}</x-badge>
                <span class="text-sm text-ink-muted">
                    {{ $lahan->luas_panjang_m }}m × {{ $lahan->luas_lebar_m }}m ·
                    {{ $lahan->estimasi_jumlah_pohon }} pohon ·
                    jarak tanam {{ $lahan->jarak_tanam_m }}m
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Total Pemasukan</p>
                    <p class="mt-1 text-lg font-bold text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Total Pengeluaran</p>
                    <p class="mt-1 text-lg font-bold text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Total Profit</p>
                    <p class="{{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }} mt-1 text-lg font-bold">
                        Rp {{ number_format($totalProfit, 0, ',', '.') }}
                    </p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Biaya / Pohon</p>
                    <p class="mt-1 text-lg font-bold text-ink">Rp {{ number_format($biayaPerPohon, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div class="rounded-xl border border-line bg-surface p-6">
                    <h3 class="mb-4 font-medium text-ink">Tren Profit 6 Bulan</h3>
                    <canvas height="120" id="profitTrendChart"></canvas>
                </div>

                <div class="rounded-xl border border-line bg-surface p-6">
                    <h3 class="mb-4 font-medium text-ink">Hasil Panen per Siklus</h3>
                    @if ($panenLabels->count() > 0)
                        <canvas height="120" id="panenChart"></canvas>
                    @else
                        <p class="py-12 text-center text-sm text-ink-muted">Belum ada siklus panen tercatat.</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                @if ($lahan->activeAgreement)
                    <div class="rounded-xl border border-line bg-surface p-6">
                        <h3 class="mb-3 font-medium text-ink">Kesepakatan Aktif</h3>
                        <p class="text-sm text-ink">
                            Partner: <span class="font-medium">{{ $lahan->activeAgreement->partner->nama }}</span>
                        </p>
                        <p class="mt-1 text-sm text-ink-muted">
                            Skema: {{ $lahan->activeAgreement->skema === 'sewa' ? 'Sewa' : 'Bagi Hasil' }}
                            @if ($lahan->activeAgreement->skema === 'sewa')
                                — Rp {{ number_format($lahan->activeAgreement->nominal_sewa ?? 0, 0, ',', '.') }}
                            @else
                                — {{ $lahan->activeAgreement->persentase_bagi_hasil }}%
                            @endif
                        </p>
                    </div>
                @endif

                <div class="rounded-xl border border-line bg-surface p-6">
                    <h3 class="mb-3 font-medium text-ink">Aset di Lahan Ini</h3>
                    @forelse ($lahan->assetAllocations as $alloc)
                        <div class="flex items-center justify-between py-1 text-sm">
                            <span class="text-ink">{{ $alloc->asset->nama }}</span>
                            <x-badge :tone="$alloc->asset->kondisi === 'baik' ? 'success' : ($alloc->asset->kondisi === 'rusak' ? 'danger' : 'warn')">
                                {{ str_replace('_', ' ', ucfirst($alloc->asset->kondisi)) }}
                            </x-badge>
                        </div>
                    @empty
                        <p class="text-sm text-ink-muted">Belum ada aset tercatat untuk lahan ini.</p>
                    @endforelse
                </div>
            </div>

            @if ($lahan->troubleReports->count() > 0)
                <div class="rounded-xl border border-danger/20 bg-danger/5 p-6">
                    <h3 class="mb-3 font-medium text-danger">⚠ Trouble Aktif ({{ $lahan->troubleReports->count() }})
                    </h3>
                    @foreach ($lahan->troubleReports as $trouble)
                        <a class="block text-sm hover:underline" href="{{ route('trouble-reports.show', $trouble) }}">
                            {{ $trouble->judul }} — {{ $trouble->status }}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div class="rounded-xl border border-line bg-surface p-6">
                    <h3 class="mb-3 font-medium text-ink">Progress Terbaru</h3>
                    @forelse ($lahan->progressLogs as $log)
                        <a class="block py-1 text-sm hover:underline" href="{{ route('progress-logs.show', $log) }}">
                            {{ $log->tanggal->format('d M Y') }} — {{ Str::limit($log->keterangan, 40) }}
                        </a>
                    @empty
                        <p class="text-sm text-ink-muted">Belum ada log perkembangan.</p>
                    @endforelse
                </div>

                <div class="rounded-xl border border-line bg-surface p-6">
                    <h3 class="mb-3 font-medium text-ink">Jadwal Mendatang</h3>
                    @forelse ($lahan->schedules as $jadwal)
                        <a class="block py-1 text-sm hover:underline" href="{{ route('schedules.show', $jadwal) }}">
                            {{ $jadwal->jenis }} — {{ $jadwal->next_date->format('d M Y') }}
                        </a>
                    @empty
                        <p class="text-sm text-ink-muted">Tidak ada jadwal mendatang.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Chart(document.getElementById('profitTrendChart'), {
                    type: 'line',
                    data: {
                        labels: @json($trendLabels),
                        datasets: [{
                            label: 'Profit',
                            data: @json($trendProfit),
                            borderColor: '#2F5233',
                            backgroundColor: '#2F523320',
                            tension: 0.3,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: v => 'Rp ' + (v / 1000) + 'rb'
                                }
                            }
                        }
                    }
                });

                @if ($panenLabels->count() > 0)
                    new Chart(document.getElementById('panenChart'), {
                        type: 'bar',
                        data: {
                            labels: @json($panenLabels),
                            datasets: [{
                                label: 'Hasil (kg)',
                                data: @json($panenHasil),
                                backgroundColor: '#B8863A',
                                borderRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
