<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Lahan;
use App\Support\ActiveLahan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Transaction::class);

        $query = Transaction::with('lahan', 'user')->latest('tanggal');

        if (!ActiveLahan::isAllSelected()) {
            $query->where('lahan_id', ActiveLahan::id());
        }

        $transactions = $query->paginate(20);

        // Ringkasan berdasarkan filter yang sama (tanpa pagination, untuk hitung total & chart)
        $summaryQuery = Transaction::query();
        if (!ActiveLahan::isAllSelected()) {
            $summaryQuery->where('lahan_id', ActiveLahan::id());
        }

        $totalPemasukan = (clone $summaryQuery)->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = (clone $summaryQuery)->where('jenis', 'pengeluaran')->sum('jumlah');

        $breakdownKategori = (clone $summaryQuery)
            ->where('jenis', 'pengeluaran')
            ->selectRaw('kategori, SUM(jumlah) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->pluck('total', 'kategori');

        return view('transactions.index', compact(
            'transactions',
            'totalPemasukan',
            'totalPengeluaran',
            'breakdownKategori'
        ));
    }

    public function create()
    {
        $this->authorize('create', Transaction::class);

        $activeLahan = ActiveLahan::get();
        $lahans = $activeLahan ? collect([$activeLahan]) : Lahan::all();

        return view('transactions.create', compact('lahans', 'activeLahan'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Transaction::class);

        $validated = $request->validate([
            'lahan_id' => 'required|exists:lahans,id',
            'jenis' => 'required|in:pengeluaran,pemasukan',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'is_cash' => 'boolean',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_cash'] = $request->boolean('is_cash', true);

        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load('lahan', 'user', 'panenCycle', 'asset');

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $lahans = Lahan::all();

        return view('transactions.edit', compact('transaction', 'lahans'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'lahan_id' => 'required|exists:lahans,id',
            'jenis' => 'required|in:pengeluaran,pemasukan',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'is_cash' => 'boolean',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $validated['is_cash'] = $request->boolean('is_cash', true);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus (soft delete).');
    }
}
