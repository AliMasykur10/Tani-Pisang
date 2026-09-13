<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Transaksi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-3 text-sm">
                <div><span class="text-gray-500">Lahan:</span> {{ $transaction->lahan->nama }}</div>
                <div><span class="text-gray-500">Jenis:</span> {{ ucfirst($transaction->jenis) }}</div>
                <div><span class="text-gray-500">Kategori:</span> {{ $transaction->kategori }}</div>
                <div><span class="text-gray-500">Jumlah:</span> Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}</div>
                <div><span class="text-gray-500">Kas:</span> {{ $transaction->is_cash ? 'Ya' : 'Non-kas (estimasi)' }}</div>
                <div><span class="text-gray-500">Tanggal:</span> {{ $transaction->tanggal->format('d M Y') }}</div>
                <div><span class="text-gray-500">Dicatat oleh:</span> {{ $transaction->user->name }}</div>
                @if ($transaction->keterangan)
                    <div><span class="text-gray-500">Keterangan:</span> {{ $transaction->keterangan }}</div>
                @endif

                <div class="pt-4 border-t dark:border-gray-700">
                    <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke daftar</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>