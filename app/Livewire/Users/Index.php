<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $alertMessage = '';
    public $alertType = 'success'; // 'success' ou 'error'


    // list / search
    public $search = '';
    public $perPage = 10;

    // create modal control + fields (já tinha)
    public $showCreateModal = false;
    public $name, $email, $password, $role = 'user';

    // edit modal control + fields
    public $showEditModal = false;
    public $editUserId = null;
    public $editName, $editEmail, $editPassword, $editRole = 'user';

    // show modal control
    public $showShowModal = false;
    public $showUser = null;

    // delete confirm
    public $confirmingDelete = false;
    public $userToDelete;

    protected $listeners = [
        'userCreated' => '$refresh',
        'userUpdated' => '$refresh',
        'userDeleted' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // ---------------- CREATE ----------------
    protected $createRules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required|in:admin,senior,user',
    ];

    public function openCreateModal()
    {
        $this->reset(['name', 'email', 'password', 'role']);
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
    }

    public function saveUser()
    {
        $this->validate($this->createRules);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

//        $this->showAlert('Usuário criado com sucesso!', 'success');

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Usuário criado com sucesso!'
        ]);

        $this->showCreateModal = false;
        $this->resetPage(); // opcional: garante listagem atualizada na página 1
    }

    // ---------------- EDIT ----------------
    public function openEditModal($id)
    {
        $user = User::findOrFail($id);
        $this->editUserId = $user->id;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
        $this->editRole = $user->role;
        $this->editPassword = null; // senha opcional
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editUserId = null;
    }

    public function saveEdit()
    {
        // validação dinâmica (email unique ignorando o próprio user)
        $this->validate([
            'editName' => 'required|string|max:255',
            'editEmail' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->editUserId),
            ],
            'editRole' => 'required|in:admin,senior,user',
            'editPassword' => 'nullable|min:6',
        ], [
            'editName.required' => 'O nome é obrigatório.',
            'editEmail.required' => 'O e-mail é obrigatório.',
            'editEmail.email' => 'Informe um e-mail válido.',
            'editRole.required' => 'Selecione o nível de acesso.',
        ]);

        $user = User::findOrFail($this->editUserId);

        $data = [
            'name' => $this->editName,
            'email' => $this->editEmail,
            'role' => $this->editRole,
        ];

        if ($this->editPassword) {
            $data['password'] = Hash::make($this->editPassword);
        }

        $user->update($data);

        $this->showAlert('Usuário alterado com sucesso!', 'success');
//        $this->dispatch('notify', [
//            'type' => 'success',
//            'message' => 'Usuário editado com sucesso!'
//        ]);

        $this->showEditModal = false;
        $this->resetPage();
    }

    // ---------------- SHOW ----------------
    public function openShowModal($id)
    {
        $this->showUser = User::findOrFail($id);
        $this->showShowModal = true;
    }

    public function closeShowModal()
    {
        $this->showShowModal = false;
        $this->showUser = null;
    }

    // ---------------- DELETE ----------------
    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->userToDelete = User::findOrFail($id);
    }

    public function delete()
    {
        if ($this->userToDelete) {
            $this->userToDelete->delete();

            $this->showAlert('Usuário excluído com sucesso!', 'success');

            $this->confirmingDelete = false;
            $this->userToDelete = null;
            $this->resetPage();
        }
    }


    protected function showAlert(string $message, string $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
    }

    // ---------------- RENDER ----------------
    public function render()
    {
        $query = User::query()
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest();

        // se não for admin, só mostra o próprio usuário (opcional)
        if (Auth::user()->role !== 'admin') {
            $query->where('id', Auth::id());
        }

        $users = $query->paginate($this->perPage);

        return view('livewire.users.index', compact('users'))
            ->layout('layouts.app');
    }
}
