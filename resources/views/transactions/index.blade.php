<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Transaksi Keuangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 shadow-sm dark:bg-gray-800 sm:rounded-lg">

                @if (session('success'))
                    <div class="mb-4 rounded bg-green-100 p-4 text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4 flex justify-end">
                    <a class="rounded bg-gray-800 px-4 py-2 text-white hover:bg-gray-700"
                        href="{{ route('transactions.create') }}">
                        + Catat Transaksi
                    </a>
                </div>

                <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Total Pemasukan</p>
                        <p class="mt-1 text-lg font-bold text-success">Rp
                            {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Total Pengeluaran</p>
                        <p class="mt-1 text-lg font-bold text-danger">Rp
                            {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-line bg-surface p-4 text-center">
                        <p class="text-xs text-ink-muted">Selisih</p>
                        @php $selisih = $totalPemasukan - $totalPengeluaran; @endphp
                        <p class="{{ $selisih >= 0 ? 'text-success' : 'text-danger' }} mt-1 text-lg font-bold">
                            Rp {{ number_format($selisih, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                @if ($breakdownKategori->count() > 0)
                    <div class="mb-6 rounded-xl border border-line bg-surface p-6">
                        <h3 class="mb-4 font-medium text-ink">Pengeluaran per Kategori</h3>
                        <canvas height="80" id="kategoriTransaksiChart"></canvas>
                    </div>
                @endif

                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th class="py-2">Tanggal</th>
                            <th class="py-2">Lahan</th>
                            <th class="py-2">Jenis</th>
                            <th class="py-2">Kategori</th>
                            <th class="py-2">Jumlah</th>
                            <th class="py-2">Kas?</th>
                            <th class="py-2">Dicatat oleh</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-2">{{ $transaction->tanggal->format('d M Y') }}</td>
                                <td class="py-2">{{ $transaction->lahan->nama }}</td>
                                <td class="py-2">
                                    <span
                                        class="{{ $transaction->jenis === 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded px-2 py-1 text-xs">
                                        {{ ucfirst($transaction->jenis) }}
                                    </span>
                                </td>
                                <td class="py-2">{{ $transaction->kategori }}</td>
                                <td class="py-2">Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}</td>
                                <td class="py-2">{{ $transaction->is_cash ? 'Ya' : 'Non-kas' }}</td>
                                <td class="py-2">{{ $transaction->user->name }}</td>
                                <td class="space-x-2 py-2">
                                    <a class="text-yellow-600 hover:underline"
                                        href="{{ route('transactions.edit', $transaction) }}">Edit</a>
                                    <form action="{{ route('transactions.destroy', $transaction) }}" class="inline"
                                        method="POST" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-4 text-center text-gray-500" colspan="8">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>

            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if ($breakdownKategori->count() > 0)
                    new Chart(document.getElementById('kategoriTransaksiChart'), {
                        type: 'bar',
                        data: {
                            labels: @json($breakdownKategori->keys()),
                            datasets: [{
                                label: 'Pengeluaran',
                                data: @json($breakdownKategori->values()),
                                backgroundColor: '#B3413A',
                                borderRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            indexAxis: 'y',
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        callback: v => 'Rp ' + (v / 1000) + 'rb'
                                    }
                                }
                            }
                        }
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
