<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Trouble Report
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">

                @if (session('success'))
                    <div class="mb-4 rounded bg-green-100 p-4 text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4 flex justify-end">
                    <a class="rounded bg-gray-800 px-4 py-2 text-white hover:bg-gray-700"
                        href="{{ route('trouble-reports.create') }}">
                        + Lapor Masalah
                    </a>
                </div>
                <div class="mb-6 grid grid-cols-3 gap-4">
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Aktif</p>
                        <p class="{{ $totalAktif > 0 ? 'text-warn' : 'text-ink' }} mt-1 text-lg font-bold">
                            {{ $totalAktif }}</p>
                    </div>
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Urgensi Tinggi</p>
                        <p class="{{ $totalTinggi > 0 ? 'text-danger' : 'text-ink' }} mt-1 text-lg font-bold">
                            {{ $totalTinggi }}</p>
                    </div>
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Selesai Bulan Ini</p>
                        <p class="mt-1 text-lg font-bold text-success">{{ $totalSelesaiBulanIni }}</p>
                    </div>
                </div>

                <div class="max-w-3xl">
                    @forelse ($troubleReports as $report)
                        <x-timeline-item :accent="$report->status === 'selesai' ? 'success' : ($report->urgensi === 'tinggi' ? 'danger' : 'warn')" :date="$report->created_at->translatedFormat('d M Y')">

                            <div class="mb-2 flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-ink">{{ $report->judul }}</p>
                                    <p class="text-xs text-ink-muted">{{ $report->lahan->nama }}</p>
                                </div>
                                <x-badge :tone="$report->urgensi === 'tinggi' ? 'danger' : ($report->urgensi === 'sedang' ? 'warn' : 'default')">
                                    {{ ucfirst($report->urgensi) }}
                                </x-badge>
                            </div>

                            @if ($report->deskripsi)
                                <p class="mb-3 text-sm text-ink-muted">{{ $report->deskripsi }}</p>
                            @endif

                            @if (!empty($report->foto_urls))
                                <div class="mb-3 flex gap-1.5">
                                    @foreach (array_slice($report->foto_urls, 0, 4) as $foto)
                                        <img class="h-16 w-16 rounded-lg border border-line object-cover"
                                            src="{{ Storage::url($foto) }}">
                                    @endforeach
                                </div>
                            @endif

                            <div class="space-y-2 border-t border-line pt-2 text-xs">
                                <div class="flex items-center gap-2">
                                    <x-badge :tone="$report->status === 'selesai' ? 'success' : 'default'">
                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                    </x-badge>
                                    @if ($report->selesai_at)
                                        <span
                                            class="text-ink-muted">({{ $report->created_at->diffForHumans($report->selesai_at, true) }})</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-end gap-3">
                                    <a class="text-primary hover:underline"
                                        href="{{ route('trouble-reports.show', $report) }}">Lihat</a>
                                    <a class="text-warn hover:underline"
                                        href="{{ route('trouble-reports.edit', $report) }}">Edit</a>
                                    <form action="{{ route('trouble-reports.destroy', $report) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-danger hover:underline" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </x-timeline-item>
                    @empty
                        <x-empty-state message="Belum ada laporan masalah." />
                    @endforelse
                </div>

                <div class="mt-4 max-w-3xl">
                    {{ $troubleReports->links() }}
                </div>

                <div class="mt-4">
                    {{ $troubleReports->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
