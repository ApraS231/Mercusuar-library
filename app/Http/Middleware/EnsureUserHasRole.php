<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Check if user is authenticated and matches one of the roles
        if ($user && in_array($user->Peran_Akses_Pengguna->value ?? $user->Peran_Akses_Pengguna, $roles)) {
            return $next($request);
        }

        // If unauthorized, redirect or abort
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}
