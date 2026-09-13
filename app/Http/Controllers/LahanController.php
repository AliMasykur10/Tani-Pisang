<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TroubleReport;


class LahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lahans = Lahan::withCount('transactions')->latest()->get()->map(function ($lahan) {
            $totalPemasukan = Transaction::where('lahan_id', $lahan->id)
                ->where('jenis', 'pemasukan')->sum('jumlah');
            $totalPengeluaran = Transaction::where('lahan_id', $lahan->id)
                ->where('jenis', 'pengeluaran')->sum('jumlah');
            $lahan->total_profit = $totalPemasukan - $totalPengeluaran;
            $lahan->trouble_aktif_count = TroubleReport::where('lahan_id', $lahan->id)
                ->where('status', '!=', 'selesai')->count();

            return $lahan;
        });

        return view('lahans.index', compact('lahans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lahans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'luas_panjang_m' => 'nullable|numeric|min:0',
            'luas_lebar_m' => 'nullable|numeric|min:0',
            'jarak_tanam_m' => 'nullable|numeric|min:0',
            'jarak_pagar_m' => 'nullable|numeric|min:0',
            'estimasi_jumlah_pohon' => 'nullable|integer|min:0',
        ]);

        $validated['fase_saat_ini'] = 'buka_lahan';

        Lahan::create($validated);

        return redirect()->route('lahans.index')->with('success', 'Lahan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lahan $lahan)
    {
        $lahan->load([
            'activeAgreement.partner',
            'progressLogs' => fn($q) => $q->latest('tanggal')->take(5),
            'troubleReports' => fn($q) => $q->where('status', '!=', 'selesai'),
            'panenCycles' => fn($q) => $q->orderBy('nomor_siklus'),
            'schedules' => fn($q) => $q->where('status', 'aktif')->orderBy('next_date')->take(5),
            'assetAllocations.asset',
        ]);

        // Ringkasan keuangan sepanjang waktu
        $totalPemasukan = Transaction::where('lahan_id', $lahan->id)
            ->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Transaction::where('lahan_id', $lahan->id)
            ->where('jenis', 'pengeluaran')->sum('jumlah');
        $totalProfit = $totalPemasukan - $totalPengeluaran;
        $biayaPerPohon = $lahan->estimasi_jumlah_pohon > 0
            ? $totalPengeluaran / $lahan->estimasi_jumlah_pohon
            : 0;

        // Grafik profit historis 6 bulan
        $trendLabels = [];
        $trendProfit = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $trendLabels[] = $bulan->translatedFormat('M Y');

            $masuk = Transaction::where('lahan_id', $lahan->id)
                ->where('jenis', 'pemasukan')
                ->whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->sum('jumlah');
            $keluar = Transaction::where('lahan_id', $lahan->id)
                ->where('jenis', 'pengeluaran')
                ->whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->sum('jumlah');

            $trendProfit[] = $masuk - $keluar;
        }

        // Hasil panen per siklus (untuk chart)
        $panenLabels = $lahan->panenCycles->map(fn($p) => "Siklus #{$p->nomor_siklus}");
        $panenHasil = $lahan->panenCycles->pluck('total_hasil_kg');

        return view('lahans.show', compact(
            'lahan',
            'totalPemasukan',
            'totalPengeluaran',
            'totalProfit',
            'biayaPerPohon',
            'trendLabels',
            'trendProfit',
            'panenLabels',
            'panenHasil'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lahan $lahan)
    {
        return view('lahans.edit', compact('lahan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lahan $lahan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'luas_panjang_m' => 'nullable|numeric|min:0',
            'luas_lebar_m' => 'nullable|numeric|min:0',
            'jarak_tanam_m' => 'nullable|numeric|min:0',
            'jarak_pagar_m' => 'nullable|numeric|min:0',
            'estimasi_jumlah_pohon' => 'nullable|integer|min:0',
        ]);

        $lahan->update($validated);

        return redirect()->route('lahans.index')->with('success', 'Lahan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lahan $lahan)
    {
        $lahan->delete();

        return redirect()->route('lahans.index')->with('success', 'Lahan berhasil dihapus.');
    }
}
