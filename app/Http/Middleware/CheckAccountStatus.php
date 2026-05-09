<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\StatusAkun;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user login DAN statusnya Dibatasi/Nonaktif
        if (Auth::check() && Auth::user()->status_akun !== StatusAkun::Aktif) {
            
            // Opsional: Logout paksa
            // Auth::logout();
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();
            
            // Tampilkan halaman error 403
            abort(403, 'Akun Anda sedang DIBATASI karena keterlambatan pengembalian buku. Hubungi Admin.');
        }

        return $next($request);
    }
}