<?php

namespace App\Http\Controllers;

use App\Models\PartnerAgreement;
use App\Models\Partner;
use App\Models\Lahan;
use Illuminate\Http\Request;

class PartnerAgreementController extends Controller
{
    public function create()
    {
        $partners = Partner::all();
        $lahans = Lahan::all();

        return view('partner-agreements.create', compact('partners', 'lahans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'lahan_id' => 'required|exists:lahans,id',
            'skema' => 'required|in:sewa,bagi_hasil',
            'nominal_sewa' => 'nullable|numeric|min:0',
            'persentase_bagi_hasil' => 'nullable|numeric|min:0|max:100',
            'tanggal_mulai' => 'required|date',
        ]);

        PartnerAgreement::where('lahan_id', $validated['lahan_id'])
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'tanggal_berakhir' => now(),
            ]);

        $validated['is_active'] = true;

        $agreement = PartnerAgreement::create($validated);

        return redirect()->route('partners.show', $agreement->partner_id)
            ->with('success', 'Kesepakatan berhasil disimpan.');
    }

    public function edit(PartnerAgreement $partnerAgreement)
    {
        $partners = Partner::all();
        $lahans = Lahan::all();

        return view('partner-agreements.edit', compact('partnerAgreement', 'partners', 'lahans'));
    }

    public function update(Request $request, PartnerAgreement $partnerAgreement)
    {
        $validated = $request->validate([
            'nominal_sewa' => 'nullable|numeric|min:0',
            'persentase_bagi_hasil' => 'nullable|numeric|min:0|max:100',
        ]);

        $partnerAgreement->update($validated);

        return redirect()->route('partners.show', $partnerAgreement->partner)
            ->with('success', 'Kesepakatan berhasil diperbarui.');
    }

    public function destroy(PartnerAgreement $partnerAgreement)
    {
        $partner = $partnerAgreement->partner;
        $partnerAgreement->delete();

        return redirect()->route('partners.show', $partner)
            ->with('success', 'Kesepakatan berhasil dihapus.');
    }
}
