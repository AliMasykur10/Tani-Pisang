<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Log Perkembangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-3 text-sm">
                <div><span class="text-gray-500">Lahan:</span> {{ $progressLog->lahan->nama }}</div>
                <div><span class="text-gray-500">Tanggal:</span> {{ $progressLog->tanggal->format('d M Y') }}</div>
                <div><span class="text-gray-500">Dicatat oleh:</span> {{ $progressLog->user->name }}</div>
                @if ($progressLog->keterangan)
                    <div><span class="text-gray-500">Keterangan:</span> {{ $progressLog->keterangan }}</div>
                @endif

                @if (!empty($progressLog->foto_urls))
                    <div>
                        <span class="text-gray-500">Foto:</span>
                        <div class="mt-2 flex gap-2 flex-wrap">
                            @foreach ($progressLog->foto_urls as $foto)
                                <img src="{{ Storage::url($foto) }}" class="w-32 h-32 object-cover rounded border">
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="pt-4 border-t">
                    <a href="{{ route('progress-logs.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke daftar</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>