<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Progress Tracking
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
                        href="{{ route('progress-logs.create') }}">
                        + Tambah Log
                    </a>
                </div>
                <div class="mb-6 grid grid-cols-2 gap-4">
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Log Bulan Ini</p>
                        <p class="mt-1 text-lg font-bold text-ink">{{ $totalLogBulanIni }}</p>
                    </div>
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Update Terakhir</p>
                        <p class="mt-1 text-lg font-bold text-ink">
                            {{ $logTerakhir ? $logTerakhir->tanggal->diffForHumans() : '-' }}
                        </p>
                    </div>
                </div>

                <div class="max-w-3xl">
                    @forelse ($progressLogs as $log)
                        <x-timeline-item :date="$log->tanggal->translatedFormat('d M Y')" accent="primary">
                            <div class="mb-2 flex items-start justify-between">
                                <p class="text-sm font-medium text-ink">{{ $log->lahan->nama }}</p>
                                <span class="text-xs text-ink-muted">{{ $log->user->name }}</span>
                            </div>

                            @if ($log->keterangan)
                                <p class="mb-3 text-sm text-ink">{{ $log->keterangan }}</p>
                            @endif

                            @if (!empty($log->foto_urls))
                                <div class="mb-3 flex gap-1.5">
                                    @foreach (array_slice($log->foto_urls, 0, 4) as $foto)
                                        <img class="h-16 w-16 rounded-lg border border-line object-cover"
                                            src="{{ Storage::url($foto) }}">
                                    @endforeach
                                    @if (count($log->foto_urls) > 4)
                                        <div
                                            class="flex h-16 w-16 items-center justify-center rounded-lg bg-bg text-xs text-ink-muted">
                                            +{{ count($log->foto_urls) - 4 }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="flex items-center gap-3 border-t border-line pt-2 text-xs">
                                <a class="text-primary hover:underline"
                                    href="{{ route('progress-logs.show', $log) }}">Lihat</a>
                                <a class="text-warn hover:underline"
                                    href="{{ route('progress-logs.edit', $log) }}">Edit</a>
                                <form action="{{ route('progress-logs.destroy', $log) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus log ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-danger hover:underline" type="submit">Hapus</button>
                                </form>
                            </div>
                        </x-timeline-item>
                    @empty
                        <x-empty-state message="Belum ada log perkembangan." />
                    @endforelse
                </div>

                <div class="mt-4 max-w-3xl">
                    {{ $progressLogs->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
