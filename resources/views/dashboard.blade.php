<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-lg bg-white p-6 text-center shadow-sm">
                    <p class="text-xs text-gray-500">Pemasukan Bulan Ini</p>
                    <p class="mt-1 text-xl font-bold text-green-600">Rp
                        {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-lg bg-white p-6 text-center shadow-sm">
                    <p class="text-xs text-gray-500">Pengeluaran Bulan Ini</p>
                    <p class="mt-1 text-xl font-bold text-red-600">Rp
                        {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-lg bg-white p-6 text-center shadow-sm">
                    <p class="text-xs text-gray-500">Profit Bulan Ini</p>
                    @php $profitBulanIni = $totalPemasukanBulanIni - $totalPengeluaranBulanIni; @endphp
                    <p class="{{ $profitBulanIni >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1 text-xl font-bold">
                        Rp {{ number_format($profitBulanIni, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Lahan Aktif</p>
                    <p class="mt-1 text-lg font-bold text-ink">{{ $totalLahanAktif }}</p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Total Pohon</p>
                    <p class="mt-1 text-lg font-bold text-ink">{{ $totalPohonKeseluruhan }}</p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Rata² Biaya/Pohon</p>
                    <p class="mt-1 text-lg font-bold text-ink">Rp
                        {{ number_format($rataRataBiayaPerPohon, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl border border-line bg-surface p-4 text-center">
                    <p class="text-xs text-ink-muted">Aset Perlu Perhatian</p>
                    <p
                        class="{{ $assetsSummary['rusak'] + $assetsSummary['perlu_servis'] > 0 ? 'text-warn' : 'text-success' }} mt-1 text-lg font-bold">
                        {{ $assetsSummary['rusak'] + $assetsSummary['perlu_servis'] }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="rounded-xl border border-line bg-surface p-6 lg:col-span-2">
                    <h3 class="mb-4 font-medium text-ink">Tren Keuangan 6 Bulan Terakhir</h3>
                    <canvas height="100" id="trendChart"></canvas>
                </div>
                <div class="rounded-xl border border-line bg-surface p-6">
                    <h3 class="mb-4 font-medium text-ink">Pengeluaran Bulan Ini</h3>
                    @if ($breakdownKategori->count() > 0)
                        <canvas height="220" id="kategoriChart"></canvas>
                    @else
                        <p class="py-12 text-center text-sm text-ink-muted">Belum ada pengeluaran bulan ini.</p>
                    @endif
                </div>
            </div>

            @if ($perbandinganLahan->count() > 1)
                <div class="rounded-xl border border-line bg-surface p-6">
                    <h3 class="mb-4 font-medium text-ink">Perbandingan Profit Antar Lahan (Bulan Ini)</h3>
                    <canvas height="80" id="perbandinganChart"></canvas>
                </div>
            @endif

            <div>
                <h3 class="mb-4 font-medium text-ink">Ringkasan Lahan</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @forelse ($lahans as $lahan)
                        <a class="block rounded-xl border border-line bg-surface p-5 transition-all hover:border-primary hover:shadow-sm"
                            href="{{ route('lahans.show', $lahan) }}">

                            <div class="mb-3 flex items-start justify-between">
                                <div>
                                    <p class="font-medium text-ink">{{ $lahan->nama }}</p>
                                    <div class="mt-1 flex gap-1.5">
                                        <x-badge
                                            tone="primary">{{ str_replace('_', ' ', ucfirst($lahan->fase_saat_ini)) }}</x-badge>
                                        @if ($lahan->trouble_aktif_count > 0)
                                            <x-badge tone="danger">{{ $lahan->trouble_aktif_count }} trouble</x-badge>
                                        @endif
                                    </div>
                                </div>
                                <canvas height="32" id="spark-{{ $lahan->id }}" width="70"></canvas>
                            </div>

                            <div class="mb-3 grid grid-cols-2 gap-3 border-b border-line pb-3">
                                <div>
                                    <p class="text-xs text-ink-muted">Profit Bulan Ini</p>
                                    <p
                                        class="{{ $lahan->profit_bulan_ini >= 0 ? 'text-success' : 'text-danger' }} text-sm font-semibold">
                                        Rp {{ number_format($lahan->profit_bulan_ini, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-ink-muted">Biaya / Pohon</p>
                                    <p class="text-sm font-semibold text-ink">
                                        Rp {{ number_format($lahan->biaya_per_pohon, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-ink-muted">Jumlah Pohon</p>
                                    <p class="text-sm font-semibold text-ink">
                                        {{ $lahan->estimasi_jumlah_pohon ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-ink-muted">Total Transaksi</p>
                                    <p class="text-sm font-semibold text-ink">{{ $lahan->transactions_count }}</p>
                                </div>
                            </div>

                            <div class="flex justify-between text-xs text-ink-muted">
                                <span>
                                    @if ($lahan->progress_terakhir)
                                        Update: {{ $lahan->progress_terakhir->tanggal->diffForHumans() }}
                                    @else
                                        Belum ada update
                                    @endif
                                </span>
                                @if ($lahan->jadwal_terdekat)
                                    <span>Jadwal: {{ $lahan->jadwal_terdekat->next_date->format('d M') }}</span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <x-empty-state message="Belum ada lahan." />
                    @endforelse
                </div>
            </div>

            @if ($jadwalTerlewat->count() > 0)
                <div class="rounded-lg border border-red-200 bg-red-50 p-6">
                    <h3 class="mb-4 font-medium text-red-800">⚠ Jadwal Terlewat ({{ $jadwalTerlewat->count() }})</h3>
                    <div class="space-y-2">
                        @foreach ($jadwalTerlewat as $jadwal)
                            <a class="block text-sm hover:underline" href="{{ route('schedules.show', $jadwal) }}">
                                {{ $jadwal->jenis }} — {{ $jadwal->lahan->nama }} (harusnya
                                {{ $jadwal->next_date->format('d M Y') }})
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="rounded-lg bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-medium">Trouble Aktif ({{ $troubleAktif->count() }})</h3>
                    <div class="space-y-2">
                        @forelse ($troubleAktif as $trouble)
                            <a class="block text-sm hover:underline"
                                href="{{ route('trouble-reports.show', $trouble) }}">
                                <span class="font-medium">{{ $trouble->judul }}</span>
                                <span class="text-gray-500">— {{ $trouble->lahan->nama }}
                                    ({{ $trouble->status }})
                                </span>
                            </a>
                        @empty
                            <p class="text-sm text-gray-500">Tidak ada masalah aktif.</p>
                        @endforelse
                    </div>
                    <a class="mt-3 inline-block text-xs text-blue-600 hover:underline"
                        href="{{ route('trouble-reports.index') }}">Lihat semua &rarr;</a>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-medium">Jadwal Mendatang</h3>
                    <div class="space-y-2">
                        @forelse ($jadwalMendatang as $jadwal)
                            <a class="block text-sm hover:underline" href="{{ route('schedules.show', $jadwal) }}">
                                <span class="font-medium">{{ $jadwal->jenis }}</span>
                                <span class="text-gray-500">— {{ $jadwal->lahan->nama }}
                                    ({{ $jadwal->next_date->format('d M Y') }})
                                </span>
                            </a>
                        @empty
                            <p class="text-sm text-gray-500">Tidak ada jadwal mendatang.</p>
                        @endforelse
                    </div>
                    <a class="mt-3 inline-block text-xs text-blue-600 hover:underline"
                        href="{{ route('schedules.index') }}">Lihat semua &rarr;</a>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="mb-4 font-medium">Log Perkembangan Terbaru</h3>
                <div class="space-y-2">
                    @forelse ($progressTerbaru as $log)
                        <a class="block text-sm hover:underline" href="{{ route('progress-logs.show', $log) }}">
                            <span class="font-medium">{{ $log->lahan->nama }}</span>
                            <span class="text-gray-500">— {{ $log->tanggal->format('d M Y') }} oleh
                                {{ $log->user->name }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada log perkembangan.</p>
                    @endforelse
                </div>
                <a class="mt-3 inline-block text-xs text-blue-600 hover:underline"
                    href="{{ route('progress-logs.index') }}">Lihat semua &rarr;</a>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="rounded-xl border border-line bg-surface p-6">
                    <h3 class="mb-4 font-medium text-ink">Ringkasan Aset</h3>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div>
                            <p class="text-xl font-bold text-success">{{ $assetsSummary['baik'] }}</p>
                            <p class="text-xs text-ink-muted">Baik</p>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-warn">{{ $assetsSummary['perlu_servis'] }}</p>
                            <p class="text-xs text-ink-muted">Perlu Servis</p>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-danger">{{ $assetsSummary['rusak'] }}</p>
                            <p class="text-xs text-ink-muted">Rusak</p>
                        </div>
                    </div>
                    <a class="mt-4 inline-block text-xs text-primary hover:underline"
                        href="{{ route('assets.index') }}">Lihat semua &rarr;</a>
                </div>

                <div class="rounded-xl border border-line bg-surface p-6">
                    <h3 class="mb-4 font-medium text-ink">Panen Terbaru</h3>
                    <div class="space-y-2">
                        @forelse ($panenTerbaru as $panen)
                            <a class="block text-sm hover:underline" href="{{ route('panen-cycles.show', $panen) }}">
                                <span class="font-medium text-ink">{{ $panen->lahan->nama }}</span>
                                <span class="text-ink-muted">— {{ number_format($panen->total_hasil_kg, 1) }} kg
                                    ({{ $panen->tanggal_panen->format('d M Y') }})
                                </span>
                            </a>
                        @empty
                            <p class="text-sm text-ink-muted">Belum ada panen tercatat.</p>
                        @endforelse
                    </div>
                    <a class="mt-4 inline-block text-xs text-primary hover:underline"
                        href="{{ route('panen-cycles.index') }}">Lihat semua &rarr;</a>
                </div>
            </div>

        </div>
    </div>@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grafik tren keuangan
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: @json($trendLabels),
                    datasets: [{
                            label: 'Pemasukan',
                            data: @json($trendPemasukan),
                            borderColor: '#4A7856',
                            backgroundColor: '#4A785615',
                            tension: 0.3,
                            fill: true,
                        },
                        {
                            label: 'Pengeluaran',
                            data: @json($trendPengeluaran),
                            borderColor: '#B86B63',
                            backgroundColor: '#B86B6315',
                            tension: 0.3,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'rb';
                                }
                            }
                        }
                    }
                }
            });

            // Grafik breakdown kategori
            @if ($breakdownKategori->count() > 0)
                new Chart(document.getElementById('kategoriChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($breakdownKategori->keys()),
                        datasets: [{
                            data: @json($breakdownKategori->values()),
                            backgroundColor: ['#4A7856', '#C9A063', '#B86B63', '#8B8D82', '#6B8F71',
                                '#7C97A8'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            @endif
        });
        // Sparkline profit per lahan
        @foreach ($lahans as $lahan)
            new Chart(document.getElementById('spark-{{ $lahan->id }}'), {
                type: 'line',
                data: {
                    labels: ['', '', '', ''],
                    datasets: [{
                        data: @json($lahan->sparkline),
                        borderColor: '{{ collect($lahan->sparkline)->last() >= 0 ? '#4A7856' : '#B86B63' }}',
                        borderWidth: 1.5,
                        pointRadius: 0,
                        tension: 0.3,
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    },
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            display: false
                        }
                    },
                    elements: {
                        line: {
                            borderJoinStyle: 'round'
                        }
                    }
                }
            });
        @endforeach

        // Perbandingan profit antar lahan
        @if ($perbandinganLahan->count() > 1)
            new Chart(document.getElementById('perbandinganChart'), {
                type: 'bar',
                data: {
                    labels: @json($perbandinganLahan->pluck('nama')),
                    datasets: [{
                        label: 'Profit Bulan Ini',
                        data: @json($perbandinganLahan->pluck('profit')),
                        backgroundColor: @json($perbandinganLahan->pluck('profit'))
                            .map(p => p >= 0 ? '#4A7856' : '#B86B63'),
                        borderRadius: 6,
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
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'rb';
                                }
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endpush
</x-app-layout>
