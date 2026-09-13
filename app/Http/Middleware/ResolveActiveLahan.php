<?php

namespace App\Http\Middleware;

use App\Models\Lahan;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveActiveLahan
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $activeLahanId = session('active_lahan_id');

        // Kalau ada lahan_id di URL (misal /lahans/3/transactions), sinkronkan ke session
        if ($request->route('lahan')) {
            $lahanParam = $request->route('lahan');
            $activeLahanId = $lahanParam instanceof Lahan ? $lahanParam->id : $lahanParam;
            session(['active_lahan_id' => $activeLahanId]);
        }

        // Staff wajib punya lahan aktif. Kalau belum ada, redirect ke halaman pilih lahan.
        if ($user->role === 'staff' && ! $activeLahanId) {
            if (! $request->routeIs('lahan-picker.*')) {
                return redirect()->route('lahan-picker.index');
            }
        }

        return $next($request);
    }
}
