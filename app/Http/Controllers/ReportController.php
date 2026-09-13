<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Transaction;
use App\Models\ProgressLog;
use App\Models\TroubleReport;
use App\Models\Asset;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function form()
    {
        $lahans = Lahan::all();

        return view('reports.form', compact('lahans'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'lahan_id' => 'nullable|exists:lahans,id',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
        ]);

        $lahanId = $validated['lahan_id'] ?? null;
        $mulai = $validated['periode_mulai'];
        $selesai = $validated['periode_selesai'];

        // Filter dasar per lahan (kalau dipilih) dan rentang tanggal
        $lahans = $lahanId ? Lahan::where('id', $lahanId)->get() : Lahan::all();

        $transactions = Transaction::with('lahan')
            ->when($lahanId, fn($q) => $q->where('lahan_id', $lahanId))
            ->whereBetween('tanggal', [$mulai, $selesai])
            ->orderBy('tanggal')
            ->get();

        $totalPemasukan = $transactions->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transactions->where('jenis', 'pengeluaran')->sum('jumlah');
        $profit = $totalPemasukan - $totalPengeluaran;

        $transactionsByKategori = $transactions->groupBy('kategori')->map(function ($group) {
            return [
                'pemasukan' => $group->where('jenis', 'pemasukan')->sum('jumlah'),
                'pengeluaran' => $group->where('jenis', 'pengeluaran')->sum('jumlah'),
            ];
        });

        $progressLogs = ProgressLog::with('lahan', 'user')
            ->when($lahanId, fn($q) => $q->where('lahan_id', $lahanId))
            ->whereBetween('tanggal', [$mulai, $selesai])
            ->orderBy('tanggal')
            ->get();

        $troubleReports = TroubleReport::with('lahan')
            ->when($lahanId, fn($q) => $q->where('lahan_id', $lahanId))
            ->whereBetween('created_at', [$mulai, $selesai])
            ->get();

        $troubleSummary = [
            'total' => $troubleReports->count(),
            'selesai' => $troubleReports->where('status', 'selesai')->count(),
            'belum_selesai' => $troubleReports->where('status', '!=', 'selesai')->count(),
        ];

        $assets = $lahanId
            ? Asset::whereHas('allocations', fn($q) => $q->where('lahan_id', $lahanId))->get()
            : Asset::all();

        $data = [
            'lahans' => $lahans,
            'periode_mulai' => $mulai,
            'periode_selesai' => $selesai,
            'transactions' => $transactions,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'profit' => $profit,
            'transactionsByKategori' => $transactionsByKategori,
            'progressLogs' => $progressLogs,
            'troubleReports' => $troubleReports,
            'troubleSummary' => $troubleSummary,
            'assets' => $assets,
        ];

        $pdf = Pdf::loadView('reports.pdf', $data);

        return $pdf->download('laporan-tani-pisang-' . now()->format('Y-m-d') . '.pdf');
    }
}
