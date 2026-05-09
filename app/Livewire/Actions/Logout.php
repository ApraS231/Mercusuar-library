<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        // 1. Proses Logout
        Auth::guard('web')->logout();

        // 2. Hapus Sesi
        Session::invalidate();
        Session::regenerateToken();

        // 3. PERBAIKAN UTAMA: Redirect ke halaman Login atau Home
        return redirect('/'); 
    }
}