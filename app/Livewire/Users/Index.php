<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';
    public string $filterStatus = '';

    public bool $showModal = false;
    public bool $isEditing = false;

    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $username = '';
    public string $role = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $is_active = true;

    protected function rules(): array
    {
        $uniqueEmail    = $this->isEditing ? 'unique:users,email,'    . $this->userId : 'unique:users,email';
        $uniqueUsername = $this->isEditing ? 'unique:users,username,' . $this->userId : 'unique:users,username';

        return [
            'name'                  => 'required|string|max:255',
            'email'                 => "required|email|max:255|{$uniqueEmail}",
            'username'              => "required|string|max:255|{$uniqueUsername}",
            'role'                  => 'required|string|max:255',
            'password'              => $this->isEditing ? 'nullable|min:6|confirmed' : 'required|min:6|confirmed',
            'password_confirmation' => 'nullable',
            'is_active'             => 'boolean',
        ];
    }

    protected array $messages = [
        'name.required'      => 'Nama wajib diisi.',
        'email.required'     => 'Email wajib diisi.',
        'email.unique'       => 'Email sudah digunakan.',
        'username.required'  => 'Username wajib diisi.',
        'username.unique'    => 'Username sudah digunakan.',
        'role.required'      => 'Role wajib dipilih.',
        'password.required'  => 'Password wajib diisi.',
        'password.min'       => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function updatingSearch(): void   { $this->resetPage(); }
    public function updatingFilterRole(): void  { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->userId   = $user->id;
        $this->name     = $user->name;
        $this->email    = $user->email;
        $this->username = $user->username;
        $this->role     = $user->role ?? '';
        $this->is_active = (bool) $user->is_active;
        $this->password = '';
        $this->password_confirmation = '';
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'      => $this->name,
            'email'     => $this->email,
            'username'  => $this->username,
            'role'      => $this->role,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            User::findOrFail($this->userId)->update($data);
            $this->dispatch('swal', ['type' => 'success', 'message' => 'User berhasil diperbarui!']);
        } else {
            User::create($data);
            $this->dispatch('swal', ['type' => 'success', 'message' => 'User berhasil ditambahkan!']);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $this->dispatch('swal', ['type' => 'success', 'message' => "User berhasil {$status}!"]);
    }

    public function delete(int $id): void
    {
        User::findOrFail($id)->delete();
        $this->dispatch('swal', ['type' => 'success', 'message' => 'User berhasil dihapus!']);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->userId                = null;
        $this->name                  = '';
        $this->email                 = '';
        $this->username              = '';
        $this->role                  = '';
        $this->password              = '';
        $this->password_confirmation = '';
        $this->is_active             = true;
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('name', 'like', "%{$this->search}%")
                       ->orWhere('email', 'like', "%{$this->search}%")
                       ->orWhere('username', 'like', "%{$this->search}%")
                )
            )
            ->when($this->filterRole, fn($q) => $q->where('role', $this->filterRole))
            ->when($this->filterStatus !== '', fn($q) => $q->where('is_active', $this->filterStatus))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $roles = User::select('role')->distinct()->whereNotNull('role')->pluck('role');

        return view('livewire.users.index', compact('users', 'roles'))
            ->extends('layouts.app')
            ->section('content');
    }
}
