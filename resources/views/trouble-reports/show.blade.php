<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $troubleReport->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-2xl space-y-6 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded bg-red-100 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-3 overflow-hidden bg-white p-6 text-sm shadow-sm sm:rounded-lg">
                <div><span class="text-gray-500">Lahan:</span> {{ $troubleReport->lahan->nama }}</div>
                <div><span class="text-gray-500">Tanggal Lapor:</span>
                    {{ $troubleReport->created_at->format('d M Y, H:i') }}</div>

                @if ($troubleReport->selesai_at)
                    <div><span class="text-gray-500">Tanggal Selesai:</span>
                        {{ $troubleReport->selesai_at->format('d M Y, H:i') }}</div>
                    <div><span class="text-gray-500">Durasi Penyelesaian:</span>
                        {{ $troubleReport->created_at->diffForHumans($troubleReport->selesai_at, true) }}</div>
                @endif
                <div><span class="text-gray-500">Urgensi:</span> {{ ucfirst($troubleReport->urgensi) }}</div>

                <div>
                    <span class="text-gray-500">Status:</span>
                    <span @class([
                        'px-2 py-1 text-xs rounded',
                        'bg-gray-200 text-gray-800' => $troubleReport->status === 'dilaporkan',
                        'bg-blue-100 text-blue-800' => $troubleReport->status === 'ditindaklanjuti',
                        'bg-green-100 text-green-800' => $troubleReport->status === 'selesai',
                    ])>
                        {{ ucfirst(str_replace('_', ' ', $troubleReport->status)) }}
                    </span>
                </div>
                @if ($troubleReport->deskripsi)
                    <div><span class="text-gray-500">Deskripsi:</span> {{ $troubleReport->deskripsi }}</div>
                @endif
                <div><span class="text-gray-500">Dilaporkan oleh:</span> {{ $troubleReport->user->name }}</div>

                @if (!empty($troubleReport->foto_urls))
                    <div class="flex flex-wrap gap-2">
                        @foreach ($troubleReport->foto_urls as $foto)
                            <img class="h-24 w-24 rounded border object-cover" src="{{ Storage::url($foto) }}">
                        @endforeach
                    </div>
                @endif

                @if ($troubleReport->status !== 'selesai')
                    <form action="{{ route('trouble-reports.advance-status', $troubleReport) }}" class="pt-2"
                        method="POST">
                        @csrf
                        <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
                            type="submit">
                            @if ($troubleReport->status === 'dilaporkan')
                                Tandai Sedang Ditindaklanjuti
                            @else
                                Tandai Selesai
                            @endif
                        </button>
                    </form>
                @endif
            </div>

            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="mb-4 font-medium">Riwayat Tindak Lanjut</h3>

                <div class="mb-6 space-y-3">
                    @forelse ($troubleReport->updates as $update)
                        <div class="rounded border p-3 text-sm">
                            <p class="mb-1 text-xs text-gray-500">{{ $update->user->name }} —
                                {{ $update->created_at->format('d M Y H:i') }}</p>
                            <p>{{ $update->komentar }}</p>
                            @if (!empty($update->foto_urls))
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($update->foto_urls as $foto)
                                        <img class="h-16 w-16 rounded border object-cover"
                                            src="{{ Storage::url($foto) }}">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada tindak lanjut.</p>
                    @endforelse
                </div>
                @if ($troubleReport->status !== 'selesai')
                    <form action="{{ route('trouble-reports.add-update', $troubleReport) }}"
                        class="space-y-3 border-t pt-4" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium">Tambah Update</label>
                            <textarea class="mt-1 block w-full rounded border-gray-300" name="komentar"
                                placeholder="mis. sudah disemprot obat X, kondisi membaik" rows="3"></textarea>
                            @error('komentar')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input accept="image/*" class="block w-full text-sm" multiple name="foto[]" type="file">
                        </div>
                        <button class="rounded bg-gray-800 px-4 py-2 text-sm text-white hover:bg-gray-700"
                            type="submit">Kirim Update</button>
                    </form>
                @else
                    <p class="border-t pt-4 text-sm text-gray-500">Laporan ini sudah selesai, tidak bisa menambah tindak
                        lanjut baru.</p>
                @endif
            </div>

            <a class="text-sm text-blue-600 hover:underline" href="{{ route('trouble-reports.index') }}">&larr; Kembali
                ke daftar</a>

        </div>
    </div>
</x-app-layout>
