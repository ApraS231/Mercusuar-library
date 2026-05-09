<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use App\Enums\Role;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.guest')] 
class Login extends Component
{
    #[Rule('required|string|email')]
    public string $email = '';

    #[Rule('required|string')]
    public string $password = '';

    #[Rule('boolean')]
    public bool $remember = false;

    public function authenticate(): void
    {
        $this->validate();

        // PERBAIKAN 2: Logika Rate Limiter yang Benar
        // Cek apakah user sudah terlalu banyak mencoba login (Limit 5x)
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());
            
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // Coba Login
        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            
            // JIKA GAGAL: Tambah hitungan gagal (+1)
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // JIKA BERHASIL: Baru bersihkan limit
        RateLimiter::clear($this->throttleKey());

        session()->regenerate();
        
        $this->redirect( 
            session('url.intended', $this->redirectPath()),
            navigate: true
        );
    }
    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.login');
    }

    /**
     * Menyediakan path redirect berdasarkan role user.
     */
    protected function redirectPath(): string
    {
        $user = Auth::user();

        if ($user->role === Role::Admin) {
            return '/admin/dashboard';
        }

        return '/dashboard'; 
    }
}
