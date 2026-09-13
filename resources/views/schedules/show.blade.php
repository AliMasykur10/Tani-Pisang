<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $schedule->jenis }} — {{ $schedule->lahan->nama }}
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
                <div><span class="text-gray-500">Lahan:</span> {{ $schedule->lahan->nama }}</div>
                <div><span class="text-gray-500">Jenis:</span> {{ $schedule->jenis }}</div>
                <div>
                    <span class="text-gray-500">Pola Berulang:</span>
                    {{ match($schedule->recurring_pattern) {
                        'harian' => 'Harian',
                        'mingguan' => 'Mingguan',
                        'dua_mingguan' => '2 Mingguan',
                        'bulanan' => 'Bulanan',
                        default => 'Tidak berulang',
                    } }}
                </div>
                <div><span class="text-gray-500">Jadwal Berikutnya:</span> {{ $schedule->next_date->format('d M Y') }}</div>
                <div>
                    <span class="text-gray-500">Status:</span>
                    <span @class([
                        'px-2 py-1 text-xs rounded',
                        'bg-blue-100 text-blue-800' => $schedule->status === 'aktif',
                        'bg-green-100 text-green-800' => $schedule->status === 'selesai',
                        'bg-gray-100 text-gray-800' => $schedule->status === 'dibatalkan',
                    ])>
                        {{ ucfirst($schedule->status) }}
                    </span>
                </div>

                @if ($schedule->status === 'aktif')
                    <form action="{{ route('schedules.mark-done', $schedule) }}" method="POST" class="pt-2 space-y-2">
                        @csrf
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Catatan (opsional)</label>
                            <input type="text" name="catatan" placeholder="mis. pupuk NPK 2kg" class="block w-full rounded border-gray-300 text-sm">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                            Tandai Selesai
                        </button>
                    </form>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium mb-4">Riwayat Perawatan</h3>

                <div class="space-y-3">
                    @forelse ($schedule->logs as $log)
                        <div class="border rounded p-3 text-sm">
                            <p class="font-medium">{{ $log->tanggal_dilakukan->format('d M Y') }}</p>
                            <p class="text-gray-500 text-xs">Dicatat oleh {{ $log->user->name }}</p>
                            @if ($log->catatan)
                                <p class="mt-1">{{ $log->catatan }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Belum ada riwayat perawatan.</p>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('schedules.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke daftar</a>

        </div>
    </div>
</x-app-layout>