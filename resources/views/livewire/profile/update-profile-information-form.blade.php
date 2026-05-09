<section class="bg-white p-8 rounded-[28px] border border-[#E7E0EC] shadow-sm">
    <header>
        <h2 class="font-serif-display text-2xl text-[#1D1B20] mb-1">
            Informasi Profil
        </h2>
        <p class="text-sm text-[#49454F]">
            Perbarui detail akun dan alamat pengiriman Anda.
        </p>
    </header>

    <form wire:submit="update" class="mt-8 space-y-6">
        
        <div>
            <label for="name" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
            <input wire:model="name" id="name" type="text" required autofocus autocomplete="name"
                class="w-full bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full py-3 px-5 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all placeholder-[#49454F]/40"
                placeholder="Nama Anda">
            <x-input-error class="mt-2 ml-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">Email</label>
            <input wire:model="email" id="email" type="email" required autocomplete="username"
                class="w-full bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full py-3 px-5 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all placeholder-[#49454F]/40"
                placeholder="email@contoh.com">
            <x-input-error class="mt-2 ml-2" :messages="$errors->get('email')" />

            @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                <div class="mt-4 bg-[#FFF8E1] border border-[#FFE0B2] p-4 rounded-xl">
                    <p class="text-sm text-[#F57C00]">
                        Alamat email Anda belum terverifikasi.
                        <button wire:click.prevent="sendVerification" class="underline font-bold hover:text-[#E65100] ml-1">
                            Kirim ulang link verifikasi.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-sm text-[#146C2E]">
                            Link verifikasi baru telah dikirimkan.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="alamat" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">Alamat Pengantaran (Default)</label>
            <textarea wire:model="alamat" id="alamat" rows="3"
                class="w-full bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-2xl py-3 px-5 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all placeholder-[#49454F]/40"
                placeholder="Jalan, Nomor Rumah, Kelurahan, Kecamatan..."></textarea>
            <x-input-error class="mt-2 ml-2" :messages="$errors->get('alamat')" />
        </div>

        <div>
            <label for="no_telepon" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">WhatsApp / Telepon</label>
            <input wire:model="no_telepon" id="no_telepon" type="text"
                class="w-full bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full py-3 px-5 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all placeholder-[#49454F]/40"
                placeholder="08123xxxxxx">
            <x-input-error class="mt-2 ml-2" :messages="$errors->get('no_telepon')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" 
                class="bg-[#6750A4] hover:bg-[#5F4999] text-white font-bold py-3 px-8 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                Simpan Perubahan
            </button>

            <x-action-message class="text-sm text-[#146C2E] font-medium" on="profile-updated">
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Berhasil Disimpan
                </div>
            </x-action-message>
        </div>
    </form>
</section>