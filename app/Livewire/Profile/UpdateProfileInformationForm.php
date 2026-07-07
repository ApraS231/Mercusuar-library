<?php

namespace App\Livewire\Profile;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdateProfileInformationForm extends Component
{
    public string $name = '';
    public string $email = '';

    // --- DITAMBAHKAN ---
    public ?string $alamat = '';
    public ?string $no_telepon = '';
    // --- AKHIR TAMBAHAN ---

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->Nama_Pengguna;
        $this->email = Auth::user()->Email_Pengguna;

        // --- DITAMBAHKAN ---
        $this->alamat = Auth::user()->Alamat_Pengguna;
        $this->no_telepon = Auth::user()->No_Telepon_Pengguna;
        // --- AKHIR TAMBAHAN ---
    }

    public function update(): void
    {
        $user = Auth::user();

        $validated = $this->validate();

        if ($user->Email_Pengguna !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill([
            'Nama_Pengguna' => $validated['name'],
            'Email_Pengguna' => $validated['email'],
            'Alamat_Pengguna' => $validated['alamat'],
            'No_Telepon_Pengguna' => $validated['no_telepon'],
        ]);
        $user->save();

        $this->dispatch('profile-updated', name: $user->Nama_Pengguna);
    }

    /**
     * Alias method for profile updates (called by test suite)
     */
    public function updateProfileInformation(): void
    {
        $this->update();
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:20', Rule::unique('users', 'Email_Pengguna')->ignore(Auth::user()->Id_pengguna, 'Id_pengguna')],
            'alamat' => ['nullable', 'string', 'max:1000'],

            // PERBAIKAN: Pastikan Numeric dan minimal digit
            'no_telepon' => ['nullable', 'numeric', 'digits_between:10,12'],
        ];
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.profile.update-profile-information-form');
    }
}
