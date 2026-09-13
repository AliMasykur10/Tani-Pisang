<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Export Laporan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">

                <form action="{{ route('reports.generate') }}" class="space-y-4" method="POST">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium">Lahan</label>
                        <select class="mt-1 block w-full rounded border-gray-300" name="lahan_id">
                            <option value="">Semua Lahan (Konsolidasi)</option>
                            @foreach ($lahans as $lahan)
                                <option value="{{ $lahan->id }}">{{ $lahan->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Periode Mulai</label>
                            <input class="mt-1 block w-full rounded border-gray-300" name="periode_mulai" type="date"
                                value="{{ old('periode_mulai', now()->startOfMonth()->format('Y-m-d')) }}">
                            @error('periode_mulai')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Periode Selesai</label>
                            <input class="mt-1 block w-full rounded border-gray-300" name="periode_selesai"
                                type="date" value="{{ old('periode_selesai', now()->format('Y-m-d')) }}">
                            @error('periode_selesai')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <p class="text-xs text-gray-500">Laporan mencakup ringkasan keuangan, progress lahan, trouble
                        report, dan daftar aset sesuai periode dan lahan yang dipilih.</p>

                    <div class="flex justify-end">
                        <button class="rounded bg-gray-800 px-4 py-2 text-white hover:bg-gray-700" type="submit">
                            Generate & Download PDF
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
