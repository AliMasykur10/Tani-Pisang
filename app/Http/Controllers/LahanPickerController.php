<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use Illuminate\Http\Request;

class LahanPickerController extends Controller
{
    public function index()
    {
        $lahans = Lahan::withCount(['transactions', 'troubleReports' => function ($q) {
            $q->where('status', '!=', 'selesai');
        }])->get();

        return view('lahan-picker.index', compact('lahans'));
    }

    public function select(Request $request, Lahan $lahan)
    {
        session(['active_lahan_id' => $lahan->id]);

        return redirect($request->input('redirect_to', route('dashboard')));
    }

    public function selectAll(Request $request)
    {
        abort_unless($request->user()->role === 'admin', 403);

        session()->forget('active_lahan_id');

        return redirect($request->input('redirect_to', route('dashboard')));
    }
}
