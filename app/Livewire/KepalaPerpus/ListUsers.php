<?php

namespace App\Livewire\KepalaPerpus;

use App\Models\User;
use App\Enums\StatusAkun;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class ListUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::where('role', \App\Enums\Role::User)
            ->withCount([
                'peminjamans as active_loans_count' => function ($q) {
                    $q->whereIn('status', [
                        \App\Enums\StatusPeminjaman::Pinjam,
                        \App\Enums\StatusPeminjaman::Disetujui
                    ]);
                },
                'peminjamans as overdue_loans_count' => function ($q) {
                    $q->where('status', \App\Enums\StatusPeminjaman::Overdue);
                },
                'peminjamans as total_loans_count'
            ]);

        // Search name/email
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Filter status akun
        if ($this->filterStatus !== 'all') {
            $query->where('status_akun', $this->filterStatus);
        }

        $users = $query->latest('created_at')->paginate(15);

        return view('livewire.kepala-perpus.list-users', [
            'users' => $users,
            'statuses' => StatusAkun::cases()
        ]);
    }
}
