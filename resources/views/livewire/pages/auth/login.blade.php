<?php

namespace App\Livewire\Auth;

// use App\Providers\RouteServiceProvider; // (Ini sudah kita hapus)
use Illuminate\Support\Facades\Auth;
use App\Enums\Role;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('livewire.layouts.guest')]
class Login extends Component
{
    #[Rule('required|string|email')]
    public string $email = '';

    #[Rule('required|string')]
    public string $password = '';

    #[Rule('boolean')]
    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function authenticate(): void
    {
        $this->validate();

        // Ensure the user is not rate limited
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            
            // FIX 1: Menggunakan -> bukan .
            RateLimiter::hit($this->throttleKey()); 

            // FIX 2: $this->only() dan $this->remember sudah benar, 
            //         ini adalah error palsu dari Intelephense
            if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
                
                // FIX 3: Menggunakan -> bukan .
                RateLimiter::clear($this->throttleKey()); 

                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey()); // Ini sudah benar

        session()->regenerate();

        // FIX 4: Menambahkan () pada $this->redirect()
        // FIX 5: navigate: true adalah error palsu Intelephense, ini valid
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