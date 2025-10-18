<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Edit extends Component
{
    public $user;
    public $name, $email, $role, $password;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email,{id}',
        'role' => 'required|in:admin,senior,user',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'role' => 'required|in:admin,senior,user',
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => $this->password ? Hash::make($this->password) : $this->user->password,
        ]);

        $this->dispatch('UserUpdated');
        session()->flash('success', 'Usuário atualizado com sucesso!');
    }

    public function render()
    {
        return view('livewire.users.edit');
    }
}
