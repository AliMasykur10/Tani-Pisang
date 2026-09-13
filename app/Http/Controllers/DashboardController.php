<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Transaction;
use App\Models\TroubleReport;
use App\Models\Schedule;
use App\Models\ProgressLog;
use App\Support\ActiveLahan;

class DashboardController extends Controller
{
    public function index()
    {
        $lahanFilter = ActiveLahan::id();

        $bulanIni = now()->startOfMonth();

        $lahans = Lahan::withCount('transactions')
            ->when($lahanFilter, fn($q) => $q->where('id', $lahanFilter))
            ->get()
            ->map(function ($lahan) use ($bulanIni) {
                $lahan->pemasukan_bulan_ini = Transaction::where('lahan_id', $lahan->id)
                    ->where('jenis', 'pemasukan')
                    ->where('tanggal', '>=', $bulanIni)
                    ->sum('jumlah');

                $lahan->pengeluaran_bulan_ini = Transaction::where('lahan_id', $lahan->id)
                    ->where('jenis', 'pengeluaran')
                    ->where('tanggal', '>=', $bulanIni)
                    ->sum('jumlah');

                $lahan->profit_bulan_ini = $lahan->pemasukan_bulan_ini - $lahan->pengeluaran_bulan_ini;

                $lahan->trouble_aktif_count = TroubleReport::where('lahan_id', $lahan->id)
                    ->where('status', '!=', 'selesai')
                    ->count();

                $lahan->progress_terakhir = ProgressLog::where('lahan_id', $lahan->id)
                    ->latest('tanggal')
                    ->first();

                $totalBiaya = Transaction::where('lahan_id', $lahan->id)
                    ->where('jenis', 'pengeluaran')
                    ->sum('jumlah');
                $lahan->biaya_per_pohon = $lahan->estimasi_jumlah_pohon > 0
                    ? $totalBiaya / $lahan->estimasi_jumlah_pohon
                    : 0;

                $lahan->jadwal_terdekat = Schedule::where('lahan_id', $lahan->id)
                    ->where('status', 'aktif')
                    ->orderBy('next_date')
                    ->first();

                $sparkline = [];
                for ($i = 3; $i >= 0; $i--) {
                    $mingguMulai = now()->subWeeks($i)->startOfWeek();
                    $mingguSelesai = now()->subWeeks($i)->endOfWeek();

                    $masuk = Transaction::where('lahan_id', $lahan->id)
                        ->where('jenis', 'pemasukan')
                        ->whereBetween('tanggal', [$mingguMulai, $mingguSelesai])
                        ->sum('jumlah');
                    $keluar = Transaction::where('lahan_id', $lahan->id)
                        ->where('jenis', 'pengeluaran')
                        ->whereBetween('tanggal', [$mingguMulai, $mingguSelesai])
                        ->sum('jumlah');

                    $sparkline[] = $masuk - $keluar;
                }
                $lahan->sparkline = $sparkline;

                return $lahan;
            });
        $totalPemasukanBulanIni = Transaction::where('jenis', 'pemasukan')
            ->where('tanggal', '>=', $bulanIni)
            ->when($lahanFilter, fn($q) => $q->where('lahan_id', $lahanFilter))
            ->sum('jumlah');

        $totalPengeluaranBulanIni = Transaction::where('jenis', 'pengeluaran')
            ->where('tanggal', '>=', $bulanIni)
            ->when($lahanFilter, fn($q) => $q->where('lahan_id', $lahanFilter))
            ->sum('jumlah');

        $troubleAktif = TroubleReport::with('lahan')
            ->where('status', '!=', 'selesai')
            ->when($lahanFilter, fn($q) => $q->where('lahan_id', $lahanFilter))
            ->latest()
            ->take(5)
            ->get();

        $jadwalTerlewat = Schedule::with('lahan')
            ->where('status', 'aktif')
            ->where('next_date', '<', now())
            ->when($lahanFilter, fn($q) => $q->where('lahan_id', $lahanFilter))
            ->get();

        $jadwalMendatang = Schedule::with('lahan')
            ->where('status', 'aktif')
            ->where('next_date', '>=', now())
            ->when($lahanFilter, fn($q) => $q->where('lahan_id', $lahanFilter))
            ->orderBy('next_date')
            ->take(5)
            ->get();

        $progressTerbaru = ProgressLog::with('lahan', 'user')
            ->when($lahanFilter, fn($q) => $q->where('lahan_id', $lahanFilter))
            ->latest('tanggal')
            ->take(5)
            ->get();

        // Data untuk grafik tren 6 bulan terakhir
        $trendLabels = [];
        $trendPemasukan = [];
        $trendPengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $trendLabels[] = $bulan->translatedFormat('M Y');

            $trendPemasukan[] = Transaction::where('jenis', 'pemasukan')
                ->whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->when($lahanFilter, fn($q) => $q->where('lahan_id', $lahanFilter))
                ->sum('jumlah');

            $trendPengeluaran[] = Transaction::where('jenis', 'pengeluaran')
                ->whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->when($lahanFilter, fn($q) => $q->where('lahan_id', $lahanFilter))
                ->sum('jumlah');
        }

        // Data untuk breakdown pengeluaran per kategori (bulan ini)
        $breakdownKategori = Transaction::where('jenis', 'pengeluaran')
            ->where('tanggal', '>=', $bulanIni)
            ->when($lahanFilter, fn($q) => $q->where('lahan_id', $lahanFilter))
            ->selectRaw('kategori, SUM(jumlah) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        // Perbandingan profit antar lahan (bulan ini)
        $perbandinganLahan = $lahans->map(function ($lahan) {
            return [
                'nama' => $lahan->nama,
                'profit' => $lahan->profit_bulan_ini,
            ];
        });

        // Ringkasan global
        $totalPohonKeseluruhan = $lahans->sum('estimasi_jumlah_pohon');
        $rataRataBiayaPerPohon = $lahans->where('biaya_per_pohon', '>', 0)->avg('biaya_per_pohon') ?? 0;
        $totalLahanAktif = $lahans->count();

        // Ringkasan Aset
        $assetsSummary = [
            'baik' => \App\Models\Asset::where('kondisi', 'baik')->count(),
            'rusak' => \App\Models\Asset::where('kondisi', 'rusak')->count(),
            'perlu_servis' => \App\Models\Asset::where('kondisi', 'perlu_servis')->count(),
        ];

        // Panen terakhir
        $panenTerbaru = \App\Models\PanenCycle::with('lahan')
            ->when($lahanFilter, fn($q) => $q->where('lahan_id', $lahanFilter))
            ->latest('tanggal_panen')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'lahans',
            'totalPemasukanBulanIni',
            'totalPengeluaranBulanIni',
            'troubleAktif',
            'jadwalTerlewat',
            'jadwalMendatang',
            'progressTerbaru',
            'trendLabels',
            'trendPemasukan',
            'trendPengeluaran',
            'breakdownKategori',
            'perbandinganLahan',
            'totalPohonKeseluruhan',
            'rataRataBiayaPerPohon',
            'totalLahanAktif',
            'assetsSummary',
            'panenTerbaru'

        ));
    }
}
