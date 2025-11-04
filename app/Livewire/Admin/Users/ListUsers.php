<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Enums\Role;
use App\Enums\StatusAkun;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')] // Menggunakan layout admin
class ListUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = 'all';

    // Reset paginasi saat searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset paginasi saat filtering
    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    /**
     * Update Role User
     */
    public function updateRole($userId, $newRole)
    {
        // Validasi agar tidak bisa mengubah role diri sendiri
        if ($userId == auth()->id()) {
            session()->flash('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
            $this->dispatch('refresh-page'); // Refresh untuk reset select
            return;
        }

        // Validasi enum
        if (!in_array($newRole, array_map(fn($case) => $case->value, Role::cases()))) {
            session()->flash('error', 'Role tidak valid.');
            return;
        }

        $user = User::find($userId);
        if ($user) {
            $user->update(['role' => $newRole]);
            session()->flash('success', 'Role user berhasil diperbarui.');
        }
    }

    /**
     * Update Status Akun User
     */
    public function updateStatus($userId, $newStatus)
    {
        // Validasi agar tidak bisa mengubah status diri sendiri
        if ($userId == auth()->id()) {
            session()->flash('error', 'Anda tidak dapat mengubah status akun Anda sendiri.');
            $this->dispatch('refresh-page'); // Refresh untuk reset select
            return;
        }

        if (!in_array($newStatus, array_map(fn($case) => $case->value, StatusAkun::cases()))) {
            session()->flash('error', 'Status tidak valid.');
            return;
        }

        $user = User::find($userId);
        if ($user) {
            $user->update(['status_akun' => $newStatus]);
            session()->flash('success', 'Status akun berhasil diperbarui.');
        }
    }

    public function render()
    {
        $query = User::query()
            ->when($this->search, function ($query) {
                // Pastikan pencarian di dalam group ()
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterRole !== 'all', function ($query) {
                $query->where('role', $this->filterRole);
            });

        return view('livewire.admin.users.list-users', [
            'users' => $query->latest('created_at')->paginate(15),
            'roles' => Role::cases(), // Kirim enum roles ke view
            'statuses' => StatusAkun::cases() // Kirim enum statuses ke view
        ]);
    }
}
