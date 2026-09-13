<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\TroubleReport;
use App\Support\ActiveLahan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TroubleReportController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', TroubleReport::class);

        $query = TroubleReport::with('lahan', 'user')->latest();

        if (!ActiveLahan::isAllSelected()) {
            $query->where('lahan_id', ActiveLahan::id());
        }

        $troubleReports = $query->paginate(20);

        $summaryQuery = TroubleReport::query();
        if (!ActiveLahan::isAllSelected()) {
            $summaryQuery->where('lahan_id', ActiveLahan::id());
        }

        $totalAktif = (clone $summaryQuery)->where('status', '!=', 'selesai')->count();
        $totalTinggi = (clone $summaryQuery)->where('urgensi', 'tinggi')->where('status', '!=', 'selesai')->count();
        $totalSelesaiBulanIni = (clone $summaryQuery)->where('status', 'selesai')
            ->where('updated_at', '>=', now()->startOfMonth())->count();

        return view('trouble-reports.index', compact('troubleReports', 'totalAktif', 'totalTinggi', 'totalSelesaiBulanIni'));
    }

    public function create()
    {
        $this->authorize('create', TroubleReport::class);

        $activeLahan = ActiveLahan::get();
        $lahans = $activeLahan ? collect([$activeLahan]) : Lahan::all();

        return view('trouble-reports.create', compact('lahans'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', TroubleReport::class);

        $validated = $request->validate([
            'lahan_id' => 'required|exists:lahans,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urgensi' => 'required|in:rendah,sedang,tinggi',
            'foto.*' => 'nullable|image|max:10240',
        ]);

        $fotoUrls = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $fotoUrls[] = $foto->store('trouble-reports', 'public');
            }
        }

        TroubleReport::create([
            'lahan_id' => $validated['lahan_id'],
            'user_id' => auth()->id(),
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'urgensi' => $validated['urgensi'],
            'status' => 'dilaporkan',
            'foto_urls' => $fotoUrls,
        ]);

        return redirect()->route('trouble-reports.index')->with('success', 'Laporan masalah berhasil dibuat.');
    }

    public function show(TroubleReport $troubleReport)
    {
        $this->authorize('view', $troubleReport);

        $troubleReport->load('lahan', 'user', 'updates.user');

        return view('trouble-reports.show', compact('troubleReport'));
    }

    public function edit(TroubleReport $troubleReport)
    {
        $this->authorize('update', $troubleReport);

        $lahans = Lahan::all();

        return view('trouble-reports.edit', compact('troubleReport', 'lahans'));
    }

    public function update(Request $request, TroubleReport $troubleReport)
    {
        $this->authorize('update', $troubleReport);

        $validated = $request->validate([
            'lahan_id' => 'required|exists:lahans,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urgensi' => 'required|in:rendah,sedang,tinggi',
        ]);

        $troubleReport->update($validated);

        return redirect()->route('trouble-reports.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(TroubleReport $troubleReport)
    {
        $this->authorize('delete', $troubleReport);

        $troubleReport->delete();

        return redirect()->route('trouble-reports.index')->with('success', 'Laporan berhasil dihapus.');
    }

    // Method tambahan: ubah status (state machine linear)
    public function advanceStatus(TroubleReport $troubleReport)
    {

        $this->authorize('update', $troubleReport);

        $next = match ($troubleReport->status) {
            'dilaporkan' => 'ditindaklanjuti',
            'ditindaklanjuti' => 'selesai',
            default => $troubleReport->status,
        };

        $updateData = ['status' => $next];
        if ($next === 'selesai') {
            $updateData['selesai_at'] = now();
        }

        $troubleReport->update($updateData);

        return redirect()->route('trouble-reports.show', $troubleReport)->with('success', "Status diubah menjadi: {$next}");
    }

    // Method tambahan: tambah update/tindak lanjut
    public function addUpdate(Request $request, TroubleReport $troubleReport)
    {
        $this->authorize('update', $troubleReport);

        if ($troubleReport->status === 'selesai') {
            return redirect()->route('trouble-reports.show', $troubleReport)
                ->with('error', 'Laporan sudah selesai, tidak bisa menambah tindak lanjut baru.');
        }

        $validated = $request->validate([
            'komentar' => 'required|string',
            'foto.*' => 'nullable|image|max:10240',
        ]);

        $fotoUrls = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $fotoUrls[] = $foto->store('trouble-updates', 'public');
            }
        }

        $troubleReport->updates()->create([
            'user_id' => auth()->id(),
            'komentar' => $validated['komentar'],
            'foto_urls' => $fotoUrls,
        ]);

        return redirect()->route('trouble-reports.show', $troubleReport)->with('success', 'Update tindak lanjut ditambahkan.');
    }
}
