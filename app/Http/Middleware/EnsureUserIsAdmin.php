<?php

namespace App\Http\Middleware;

use App\Enums\Role; // <-- 1. Import Enum Role
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 2. Cek jika user terautentikasi DAN memiliki role admin
        //    Ini sesuai dengan rencana Anda [cite: 64]
        if ($request->user() && $request->user()->role === Role::Admin) {
            
            // 3. Jika admin, izinkan request
            return $next($request);
        }

        // 4. Jika bukan admin, redirect ke dashboard user
        //    Anda juga bisa menggunakan abort(403, 'UNAUTHORIZED');
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses admin.');
    }
}