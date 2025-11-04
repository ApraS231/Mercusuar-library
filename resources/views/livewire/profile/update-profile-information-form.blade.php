<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Perbarui informasi profil akun Anda, termasuk alamat pengantaran default.
        </p>
    </header>

    <form wire:submit="update" class="mt-6 space-y-6">
        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Alamat email Anda belum terverifikasi.

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Link verifikasi baru telah dikirimkan ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- DITAMBAHKAN: Field Alamat -->
        <div>
            <x-input-label for="alamat" value="Alamat Lengkap (Untuk Pengantaran)" />
            <textarea 
                wire:model="alamat" id="alamat" name="alamat" 
                rows="3"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                placeholder="Contoh: Jl. Mercusuar No. 1, Kelurahan, Kecamatan, Kota, Kode Pos"
            ></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>

        <!-- DITAMBAHKAN: Field No. Telepon -->
        <div>
            <x-input-label for="no_telepon" value="No. Telepon (WhatsApp)" />
            <x-text-input wire:model="no_telepon" id="no_telepon" name="no_telepon" type="text" class="mt-1 block w-full" placeholder="Contoh: 08123456789" />
            <x-input-error class="mt-2" :messages="$errors->get('no_telepon')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                Tersimpan.
            </x-action-message>
        </div>
    </form>
</section>
