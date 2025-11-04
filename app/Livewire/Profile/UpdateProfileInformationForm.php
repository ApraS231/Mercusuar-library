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
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        
        // --- DITAMBAHKAN ---
        $this->alamat = Auth::user()->alamat;
        $this->no_telepon = Auth::user()->no_telepon;
        // --- AKHIR TAMBAHAN ---
    }

    /**
     * Update the profile information.
     */
    public function update(): void
    {
        $user = Auth::user();

        $validated = $this->validate();

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::user())],
            
            // --- DITAMBAHKAN ---
            'alamat' => ['nullable', 'string', 'max:1000'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            // --- AKHIR TAMBAHAN ---
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
