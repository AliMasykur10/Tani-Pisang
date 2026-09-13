<?php

namespace App\Http\Controllers;

use App\Models\PanenCycle;
use App\Models\Lahan;
use Illuminate\Http\Request;
use App\Support\ActiveLahan;

class PanenCycleController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    public function index()
    {
        $this->authorize('viewAny', PanenCycle::class);
        $query = PanenCycle::with('lahan', 'anakanRecord')->latest('tanggal_panen');

        if (!ActiveLahan::isAllSelected()) {
            $query->where('lahan_id', ActiveLahan::id());
        }

        $panenCycles = $query->get();

        // Data untuk chart tren hasil panen (semua siklus, urut kronologis)
        $chartData = $panenCycles->sortBy('tanggal_panen')->values();
        $panenLabels = $chartData->map(fn($p) => $p->lahan->nama . ' #' . $p->nomor_siklus);
        $panenHasilKg = $chartData->pluck('total_hasil_kg');
        $panenHasilPerPohon = $chartData->map(fn($p) => $p->jumlah_pohon_produktif > 0
            ? round($p->total_hasil_kg / $p->jumlah_pohon_produktif, 2)
            : 0);

        $totalHasilKeseluruhan = $panenCycles->sum('total_hasil_kg');
        $totalPemasukanPanen = $panenCycles->sum('total_pemasukan');
        $rataRataPerPohon = $chartData->count() > 0 ? round($panenHasilPerPohon->avg(), 2) : 0;

        return view('panen-cycles.index', compact(
            'panenCycles',
            'panenLabels',
            'panenHasilKg',
            'panenHasilPerPohon',
            'totalHasilKeseluruhan',
            'totalPemasukanPanen',
            'rataRataPerPohon'
        ));
    }

    public function create()
    {
        $this->authorize('create', PanenCycle::class);
        $activeLahan = ActiveLahan::get();
        $lahans = $activeLahan ? collect([$activeLahan]) : Lahan::all();

        return view('panen-cycles.create', compact('lahans'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', PanenCycle::class);
        $validated = $request->validate([
            'lahan_id' => 'required|exists:lahans,id',
            'nomor_siklus' => 'required|integer|min:1',
            'tanggal_panen' => 'required|date',
            'jumlah_pohon_produktif' => 'required|integer|min:0',
            'total_hasil_kg' => 'required|numeric|min:0',
            'harga_per_kg' => 'required|numeric|min:0',

            // Data anakan (opsional)
            'jumlah_muncul' => 'nullable|integer|min:0',
            'jumlah_disisakan' => 'nullable|integer|min:0',
            'jumlah_dijual' => 'nullable|integer|min:0',
            'harga_jual_per_batang' => 'nullable|numeric|min:0',
            'jumlah_dipindah_lahan_lain' => 'nullable|integer|min:0',
            'lahan_tujuan_id' => 'nullable|exists:lahans,id',
            'nilai_estimasi_per_batang' => 'nullable|numeric|min:0',
            'jumlah_dibuang' => 'nullable|integer|min:0',
        ]);

        $totalPemasukan = $validated['total_hasil_kg'] * $validated['harga_per_kg'];

        $panenCycle = PanenCycle::create([
            'lahan_id' => $validated['lahan_id'],
            'nomor_siklus' => $validated['nomor_siklus'],
            'tanggal_panen' => $validated['tanggal_panen'],
            'jumlah_pohon_produktif' => $validated['jumlah_pohon_produktif'],
            'total_hasil_kg' => $validated['total_hasil_kg'],
            'harga_per_kg' => $validated['harga_per_kg'],
            'total_pemasukan' => $totalPemasukan,
        ]);

        // Catat transaksi pemasukan dari penjualan panen
        $panenCycle->transactions()->create([
            'lahan_id' => $validated['lahan_id'],
            'user_id' => auth()->id(),
            'jenis' => 'pemasukan',
            'kategori' => 'Penjualan Panen',
            'jumlah' => $totalPemasukan,
            'is_cash' => true,
            'tanggal' => $validated['tanggal_panen'],
            'keterangan' => "Panen siklus ke-{$validated['nomor_siklus']}: {$validated['total_hasil_kg']} kg",
        ]);

        // Kalau ada data anakan, buat catatannya + transaksi terkait
        if (!empty($validated['jumlah_muncul'])) {
            $panenCycle->anakanRecord()->create([
                'jumlah_muncul' => $validated['jumlah_muncul'],
                'jumlah_disisakan' => $validated['jumlah_disisakan'] ?? 0,
                'jumlah_dijual' => $validated['jumlah_dijual'] ?? 0,
                'jumlah_dipindah_lahan_lain' => $validated['jumlah_dipindah_lahan_lain'] ?? 0,
                'jumlah_dibuang' => $validated['jumlah_dibuang'] ?? 0,
                'nilai_estimasi_per_batang' => $validated['nilai_estimasi_per_batang'] ?? null,
                'lahan_tujuan_id' => $validated['lahan_tujuan_id'] ?? null,
            ]);

            // Transaksi pemasukan kalau ada anakan yang dijual sebagai bibit
            if (!empty($validated['jumlah_dijual']) && !empty($validated['harga_jual_per_batang'])) {
                $totalJualBibit = $validated['jumlah_dijual'] * $validated['harga_jual_per_batang'];

                $panenCycle->transactions()->create([
                    'lahan_id' => $validated['lahan_id'],
                    'user_id' => auth()->id(),
                    'jenis' => 'pemasukan',
                    'kategori' => 'Penjualan Bibit/Anakan',
                    'jumlah' => $totalJualBibit,
                    'is_cash' => true,
                    'tanggal' => $validated['tanggal_panen'],
                    'keterangan' => "Jual {$validated['jumlah_dijual']} anakan sebagai bibit",
                ]);
            }

            // Transaksi non-kas kalau ada anakan dipindah ke lahan lain dengan nilai estimasi
            if (!empty($validated['jumlah_dipindah_lahan_lain']) && !empty($validated['nilai_estimasi_per_batang']) && !empty($validated['lahan_tujuan_id'])) {
                $totalNilaiEstimasi = $validated['jumlah_dipindah_lahan_lain'] * $validated['nilai_estimasi_per_batang'];

                $panenCycle->transactions()->create([
                    'lahan_id' => $validated['lahan_tujuan_id'],
                    'user_id' => auth()->id(),
                    'jenis' => 'pemasukan',
                    'kategori' => 'Bibit Non-Kas (Anakan Dipindah)',
                    'jumlah' => $totalNilaiEstimasi,
                    'is_cash' => false,
                    'tanggal' => $validated['tanggal_panen'],
                    'keterangan' => "{$validated['jumlah_dipindah_lahan_lain']} anakan dipindah dari lahan asal (nilai estimasi)",
                ]);
            }
        }

        return redirect()->route('panen-cycles.index')->with('success', 'Siklus panen berhasil dicatat.');
    }

    public function show(PanenCycle $panenCycle)
    {
        $this->authorize('view', $panenCycle);
        $panenCycle->load('lahan', 'anakanRecord.lahanTujuan', 'transactions');

        return view('panen-cycles.show', compact('panenCycle'));
    }

    public function destroy(PanenCycle $panenCycle)
    {
        $this->authorize('delete', $panenCycle);
        // Hapus transaksi terkait dulu (soft-delete tetap tersimpan ebagai riwayat)
        $panenCycle->transactions()->delete();
        $panenCycle->anakanRecord()?->delete();
        $panenCycle->delete();

        return redirect()->route('panen-cycles.index')->with('success', 'Siklus panen berhasil dihapus.');
    }
}
