<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="bg-[#FFF8F6] p-8 rounded-[28px] border border-[#F2B8B5] shadow-sm">
    <header>
        <h2 class="font-serif-display text-2xl text-[#B3261E] mb-1">
            Hapus Akun
        </h2>
        <p class="text-sm text-[#601410]">
            Setelah akun dihapus, semua data akan hilang permanen. Harap pertimbangkan kembali.
        </p>
    </header>

    <div class="mt-8">
        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-[#B3261E] hover:bg-[#8C1D18] text-white font-bold py-3 px-8 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
            Hapus Akun Saya
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-8 bg-white rounded-[28px]">
            <h2 class="font-serif-display text-2xl font-bold text-[#1D1B20] mb-2">
                Anda yakin ingin menghapus akun?
            </h2>

            <p class="text-sm text-[#49454F] mb-6">
                Tindakan ini tidak dapat dibatalkan. Masukkan password Anda untuk mengonfirmasi penghapusan permanen.
            </p>

            <div class="mb-6">
                <label for="password" class="sr-only">Password</label>
                <input wire:model="password" id="password" type="password" placeholder="Masukkan Password Anda"
                    class="w-full bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full py-3 px-5 focus:ring-2 focus:ring-[#B3261E] focus:border-transparent transition-all">
                <x-input-error class="mt-2 ml-2" :messages="$errors->get('password')" />
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" 
                    class="px-6 py-2.5 border border-[#79747E] text-[#1D1B20] rounded-full font-medium hover:bg-[#F3EDF7] transition-colors">
                    Batal
                </button>

                <button type="submit" 
                    class="px-6 py-2.5 bg-[#B3261E] text-white rounded-full font-bold hover:bg-[#8C1D18] shadow-md transition-colors">
                    Hapus Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>