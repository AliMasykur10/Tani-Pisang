<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Lahan;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with('allocations.lahan')->latest()->get();

        $totalBaik = $assets->where('kondisi', 'baik')->count();
        $totalRusak = $assets->where('kondisi', 'rusak')->count();
        $totalPerluServis = $assets->where('kondisi', 'perlu_servis')->count();
        $totalNilaiAset = $assets->sum('harga_beli');

        return view('assets.index', compact('assets', 'totalBaik', 'totalRusak', 'totalPerluServis', 'totalNilaiAset'));
    }

    public function create()
    {
        $lahans = Lahan::all();

        return view('assets.create', compact('lahans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:255',
            'tanggal_beli' => 'nullable|date',
            'harga_beli' => 'nullable|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak,perlu_servis',
            'lahan_ids' => 'required|array|min:1',
            'lahan_ids.*' => 'exists:lahans,id',
        ]);

        $asset = Asset::create([
            'nama' => $validated['nama'],
            'jenis' => $validated['jenis'] ?? null,
            'tanggal_beli' => $validated['tanggal_beli'] ?? null,
            'harga_beli' => $validated['harga_beli'] ?? null,
            'kondisi' => $validated['kondisi'],
        ]);

        // Bagi rata porsi_persen kalau dipakai lebih dari 1 lahan
        $porsi = round(100 / count($validated['lahan_ids']), 2);
        foreach ($validated['lahan_ids'] as $lahanId) {
            $asset->allocations()->create([
                'lahan_id' => $lahanId,
                'porsi_persen' => $porsi,
            ]);
        }

        // Kalau ada harga beli, otomatis catat sebagai transaksi pengeluaran di lahan pertama
        if (!empty($validated['harga_beli']) && $validated['harga_beli'] > 0) {
            $asset->transactions()->create([
                'lahan_id' => $validated['lahan_ids'][0],
                'user_id' => auth()->id(),
                'jenis' => 'pengeluaran',
                'kategori' => 'Aset',
                'jumlah' => $validated['harga_beli'],
                'is_cash' => true,
                'tanggal' => $validated['tanggal_beli'] ?? now(),
                'keterangan' => 'Pembelian aset: ' . $validated['nama'],
            ]);
        }

        return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function show(Asset $asset)
    {
        $asset->load('allocations.lahan', 'transactions');

        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:255',
            'kondisi' => 'required|in:baik,rusak,perlu_servis',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus.');
    }
}
