<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Jadwal & Reminder
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
                @if (session('error'))
                    <div class="mb-4 rounded bg-red-100 p-4 text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4 flex justify-end">
                    <a class="rounded bg-gray-800 px-4 py-2 text-white hover:bg-gray-700"
                        href="{{ route('schedules.create') }}">
                        + Tambah Jadwal
                    </a>
                </div>

                <div class="mb-6 grid grid-cols-3 gap-4">
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Aktif</p>
                        <p class="mt-1 text-lg font-bold text-ink">{{ $totalAktif }}</p>
                    </div>
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Terlewat</p>
                        <p class="{{ $totalTerlewat > 0 ? 'text-danger' : 'text-ink' }} mt-1 text-lg font-bold">
                            {{ $totalTerlewat }}</p>
                    </div>
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Selesai</p>
                        <p class="mt-1 text-lg font-bold text-success">{{ $totalSelesai }}</p>
                    </div>
                </div>

                <div class="max-w-3xl">
                    @forelse ($schedules as $schedule)
                        <x-timeline-item :accent="$schedule->next_date->isPast() && $schedule->status === 'aktif' ? 'danger' : ($schedule->status === 'selesai' ? 'success' : 'primary')" :date="$schedule->next_date->translatedFormat('d M Y')">

                            <div class="mb-2 flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-ink">
                                        <a class="hover:underline"
                                            href="{{ route('schedules.show', $schedule) }}">{{ $schedule->jenis }}</a>
                                    </p>
                                    <p class="text-xs text-ink-muted">{{ $schedule->lahan->nama }}</p>
                                </div>
                                <x-badge :tone="$schedule->status === 'aktif' ? 'primary' : ($schedule->status === 'selesai' ? 'success' : 'default')">
                                    {{ ucfirst($schedule->status) }}
                                </x-badge>
                            </div>

                            <p class="mb-3 text-xs text-ink-muted">
                                {{ match ($schedule->recurring_pattern) {
                                    'harian' => 'Harian',
                                    'mingguan' => 'Mingguan',
                                    'dua_mingguan' => '2 Mingguan',
                                    'bulanan' => 'Bulanan',
                                    default => 'Tidak berulang',
                                } }}
                                @if ($schedule->next_date->isPast() && $schedule->status === 'aktif')
                                    <span class="text-danger">· Terlewat</span>
                                @endif
                            </p>

                            <div class="flex items-center justify-end gap-3 border-t border-line pt-2 text-xs">
                                <a class="text-warn hover:underline"
                                    href="{{ route('schedules.edit', $schedule) }}">Edit</a>
                                @if ($schedule->status !== 'selesai')
                                    <form action="{{ route('schedules.destroy', $schedule) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-danger hover:underline" type="submit">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </x-timeline-item>
                    @empty
                        <x-empty-state message="Belum ada jadwal." />
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
