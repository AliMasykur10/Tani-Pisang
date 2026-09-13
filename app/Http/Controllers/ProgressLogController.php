<?php

namespace App\Http\Controllers;

use App\Models\ProgressLog;
use App\Models\Lahan;
use App\Support\ActiveLahan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class ProgressLogController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', ProgressLog::class);

        $query = ProgressLog::with('lahan', 'user')->latest('tanggal');

        if (!ActiveLahan::isAllSelected()) {
            $query->where('lahan_id', ActiveLahan::id());
        }

        $progressLogs = $query->paginate(20);

        $totalLogBulanIni = (clone $query)->where('tanggal', '>=', now()->startOfMonth())->count();
        $logTerakhir = $progressLogs->first();

        return view('progress-logs.index', compact('progressLogs', 'totalLogBulanIni', 'logTerakhir'));
    }

    public function create()
    {
        $this->authorize('create', ProgressLog::class);

        $activeLahan = ActiveLahan::get();
        $lahans = $activeLahan ? collect([$activeLahan]) : Lahan::all();

        return view('progress-logs.create', compact('lahans'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', ProgressLog::class);

        $validated = $request->validate([
            'lahan_id' => 'required|exists:lahans,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'foto.*' => 'nullable|image|max:10240',
        ]);

        $fotoUrls = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $fotoUrls[] = $foto->store('progress-logs', 'public');
            }
        }

        ProgressLog::create([
            'lahan_id' => $validated['lahan_id'],
            'user_id' => auth()->id(),
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'] ?? null,
            'foto_urls' => $fotoUrls,
        ]);

        return redirect()->route('progress-logs.index')->with('success', 'Log perkembangan berhasil dicatat.');
    }

    public function show(ProgressLog $progressLog)
    {
        $this->authorize('view', $progressLog);

        $progressLog->load('lahan', 'user');

        return view('progress-logs.show', compact('progressLog'));
    }

    public function edit(ProgressLog $progressLog)
    {
        $this->authorize('update', $progressLog);

        $lahans = Lahan::all();

        return view('progress-logs.edit', compact('progressLog', 'lahans'));
    }

    public function update(Request $request, ProgressLog $progressLog)
    {
        $this->authorize('update', $progressLog);

        $validated = $request->validate([
            'lahan_id' => 'required|exists:lahans,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'foto.*' => 'nullable|image|max:10240',
        ]);

        $fotoUrls = $progressLog->foto_urls ?? [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $fotoUrls[] = $foto->store('progress-logs', 'public');
            }
        }

        $progressLog->update([
            'lahan_id' => $validated['lahan_id'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'] ?? null,
            'foto_urls' => $fotoUrls,
        ]);

        return redirect()->route('progress-logs.index')->with('success', 'Log perkembangan berhasil diperbarui.');
    }

    public function destroy(ProgressLog $progressLog)
    {
        $this->authorize('delete', $progressLog);

        $progressLog->delete();

        return redirect()->route('progress-logs.index')->with('success', 'Log perkembangan berhasil dihapus.');
    }
}
