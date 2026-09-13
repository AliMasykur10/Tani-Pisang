<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Tani Pisang Cavendish</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        h2 {
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 8px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            text-align: left;
            padding: 6px;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f5f5f5;
        }

        .summary-box {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .summary-item {
            display: table-cell;
            width: 33%;
            padding: 10px;
            background: #f9f9f9;
            text-align: center;
        }

        .summary-item .label {
            font-size: 10px;
            color: #666;
        }

        .summary-item .value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 4px;
        }

        .text-green {
            color: #16a34a;
        }

        .text-red {
            color: #dc2626;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .badge-selesai {
            background: #dcfce7;
            color: #166534;
        }

        .badge-aktif {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>

    <h1>Laporan Usaha Tani Pisang Cavendish</h1>
    <p class="subtitle">
        Lahan: {{ $lahans->count() === 1 ? $lahans->first()->nama : 'Semua Lahan (Konsolidasi)' }}<br>
        Periode: {{ \Carbon\Carbon::parse($periode_mulai)->format('d M Y') }} —
        {{ \Carbon\Carbon::parse($periode_selesai)->format('d M Y') }}<br>
        Dicetak: {{ now()->format('d M Y H:i') }}
    </p>

    <h2>Ringkasan Keuangan</h2>
    <div class="summary-box">
        <div class="summary-item">
            <div class="label">Total Pemasukan</div>
            <div class="value text-green">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Pengeluaran</div>
            <div class="value text-red">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Profit</div>
            <div class="value {{ $profit >= 0 ? 'text-green' : 'text-red' }}">Rp
                {{ number_format($profit, 0, ',', '.') }}</div>
        </div>
    </div>

    <h2>Pengeluaran &amp; Pemasukan per Kategori</h2>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactionsByKategori as $kategori => $jumlah)
                <tr>
                    <td>{{ $kategori }}</td>
                    <td>Rp {{ number_format($jumlah['pemasukan'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($jumlah['pengeluaran'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Tidak ada transaksi di periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Detail Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Lahan</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $t)
                <tr>
                    <td>{{ $t->tanggal->format('d M Y') }}</td>
                    <td>{{ $t->lahan->nama }}</td>
                    <td>{{ ucfirst($t->jenis) }}</td>
                    <td>{{ $t->kategori }}</td>
                    <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Progress Tracking</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Lahan</th>
                <th>Keterangan</th>
                <th>Dicatat oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($progressLogs as $log)
                <tr>
                    <td>{{ $log->tanggal->format('d M Y') }}</td>
                    <td>{{ $log->lahan->nama }}</td>
                    <td>{{ $log->keterangan ?? '-' }}</td>
                    <td>{{ $log->user->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tidak ada log perkembangan di periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Ringkasan Trouble Report</h2>
    <div class="summary-box">
        <div class="summary-item">
            <div class="label">Total Laporan</div>
            <div class="value">{{ $troubleSummary['total'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Selesai</div>
            <div class="value text-green">{{ $troubleSummary['selesai'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Belum Selesai</div>
            <div class="value text-red">{{ $troubleSummary['belum_selesai'] }}</div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Lahan</th>
                <th>Judul</th>
                <th>Urgensi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($troubleReports as $report)
                <tr>
                    <td>{{ $report->lahan->nama }}</td>
                    <td>{{ $report->judul }}</td>
                    <td>{{ ucfirst($report->urgensi) }}</td>
                    <td>
                        <span class="badge {{ $report->status === 'selesai' ? 'badge-selesai' : 'badge-aktif' }}">
                            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tidak ada laporan masalah di periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Ringkasan Aset</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Aset</th>
                <th>Jenis</th>
                <th>Kondisi</th>
                <th>Harga Beli</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assets as $asset)
                <tr>
                    <td>{{ $asset->nama }}</td>
                    <td>{{ $asset->jenis ?? '-' }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($asset->kondisi)) }}</td>
                    <td>Rp {{ number_format($asset->harga_beli ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tidak ada aset tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
