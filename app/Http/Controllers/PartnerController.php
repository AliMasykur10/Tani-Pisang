<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::with('agreements.lahan')->get();

        $totalPenyediaPembeli = $partners->where('tipe', 'penyedia_pembeli')->count();
        $totalPemilikLahan = $partners->where('tipe', 'pemilik_lahan')->count();
        $totalKesepakatanAktif = $partners->sum(fn($p) => $p->agreements->where('is_active', true)->count());

        return view('partners.index', compact('partners', 'totalPenyediaPembeli', 'totalPemilikLahan', 'totalKesepakatanAktif'));
    }

    public function create()
    {
        return view('partners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:penyedia_pembeli,pemilik_lahan',
            'nama' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        Partner::create($validated);

        return redirect()->route('partners.index')->with('success', 'Partner berhasil ditambahkan.');
    }

    public function show(Partner $partner)
    {
        $partner->load('agreements.lahan');

        return view('partners.show', compact('partner'));
    }

    public function edit(Partner $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:penyedia_pembeli,pemilik_lahan',
            'nama' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $partner->update($validated);

        return redirect()->route('partners.index')->with('success', 'Partner berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('partners.index')->with('success', 'Partner berhasil dihapus.');
    }
}
