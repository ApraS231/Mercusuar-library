<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->dispatch('password-updated');
    }
}; ?>

<section class="bg-white p-8 rounded-[28px] border border-[#E7E0EC] shadow-sm">
    <header>
        <h2 class="font-serif-display text-2xl text-[#1D1B20] mb-1">
            Ubah Password
        </h2>
        <p class="text-sm text-[#49454F]">
            Pastikan akun Anda aman dengan password yang kuat.
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-8 space-y-6">
        
        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">Password Saat Ini</label>
            <input wire:model="current_password" id="update_password_current_password" type="password" autocomplete="current-password"
                class="w-full bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full py-3 px-5 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all">
            <x-input-error class="mt-2 ml-2" :messages="$errors->get('current_password')" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">Password Baru</label>
            <input wire:model="password" id="update_password_password" type="password" autocomplete="new-password"
                class="w-full bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full py-3 px-5 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all">
            <x-input-error class="mt-2 ml-2" :messages="$errors->get('password')" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">Konfirmasi Password</label>
            <input wire:model="password_confirmation" id="update_password_password_confirmation" type="password" autocomplete="new-password"
                class="w-full bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full py-3 px-5 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all">
            <x-input-error class="mt-2 ml-2" :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-[#1D1B20] hover:bg-black text-white font-bold py-3 px-8 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                Perbarui Password
            </button>

            <x-action-message class="text-sm text-[#146C2E] font-medium" on="password-updated">
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Berhasil
                </div>
            </x-action-message>
        </div>
    </form>
</section>