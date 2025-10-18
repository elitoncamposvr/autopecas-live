<div class="p-6">

    @if($alertMessage)
        <div id="alert-1" class="flex items-center p-4 mb-4 text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Alerta!</span>
            <div class="ms-3 text-sm font-medium">
                <div>{{ $alertMessage }}</div>
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-1" aria-label="Close" wire:click="$set('alertMessage','')">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Usuários</h2>
        @if(auth()->user()->role === 'admin')
            <button wire:click="openCreateModal"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Novo Usuário
            </button>
        @endif
    </div>

    <div class="mb-4">
        <input type="text" wire:model.debounce.300ms="search" placeholder="Buscar por nome ou email..."
               class="w-full border-gray-300 rounded-lg shadow-sm p-2">
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Nome</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Papel</th>
                <th class="px-4 py-3 text-right">Ações</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse ($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <button wire:click="openShowModal({{ $user->id }})"
                                class="text-blue-600 hover:text-blue-800">Ver</button>

                        @if(auth()->user()->role === 'admin')
                            <button wire:click="openEditModal({{ $user->id }})"
                                    class="text-yellow-600 hover:text-yellow-800">Editar</button>

                            <button wire:click="confirmDelete({{ $user->id }})"
                                    class="text-red-600 hover:text-red-800">Excluir</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">Nenhum usuário encontrado.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    {{-- ================= CREATE MODAL ================= --}}
    <div x-data="{ open: @entangle('showCreateModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div @click.away="open=false" class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Novo Usuário</h3>
            <form wire:submit.prevent="saveUser" class="space-y-4">
                <input type="text" wire:model.defer="name" placeholder="Nome" class="w-full border rounded p-2">
                <input type="email" wire:model.defer="email" placeholder="Email" class="w-full border rounded p-2">
                <input type="password" wire:model.defer="password" placeholder="Senha" class="w-full border rounded p-2">
                <select wire:model.defer="role" class="w-full border rounded p-2">
                    <option value="user">Comum</option>
                    <option value="senior">Sênior</option>
                    <option value="admin">Admin</option>
                </select>
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" @click="open=false" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= EDIT MODAL ================= --}}
    <div x-data="{ open: @entangle('showEditModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div @click.away="open=false" class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Editar Usuário</h3>
            <form wire:submit.prevent="saveEdit" class="space-y-4">
                <input type="text" wire:model.defer="editName" placeholder="Nome" class="w-full border rounded p-2">
                <input type="email" wire:model.defer="editEmail" placeholder="Email" class="w-full border rounded p-2">
                <input type="password" wire:model.defer="editPassword" placeholder="Senha (deixe vazio para manter)" class="w-full border rounded p-2">
                <select wire:model.defer="editRole" class="w-full border rounded p-2">
                    <option value="user">Comum</option>
                    <option value="senior">Sênior</option>
                    <option value="admin">Admin</option>
                </select>
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" @click="open=false" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= SHOW MODAL ================= --}}
    <div x-data="{ open: @entangle('showShowModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div @click.away="open=false" class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Detalhes do Usuário</h3>
            @if($showUser)
                <p><strong>Nome:</strong> {{ $showUser->name }}</p>
                <p><strong>Email:</strong> {{ $showUser->email }}</p>
                <p><strong>Papel:</strong> {{ ucfirst($showUser->role) }}</p>
                <p><strong>Criado em:</strong> {{ $showUser->created_at->format('d/m/Y H:i') }}</p>
            @endif
            <div class="flex justify-end pt-4">
                <button type="button" @click="open=false" class="bg-gray-200 px-4 py-2 rounded">Fechar</button>
            </div>
        </div>
    </div>

    {{-- ================= DELETE CONFIRM ================= --}}
    @if($confirmingDelete && $userToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-semibold mb-2">Confirmar Exclusão</h3>
                <p class="mb-4">Deseja realmente excluir <strong>{{ $userToDelete->name }}</strong>?</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('confirmingDelete', false)" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
                    <button wire:click="delete" class="bg-red-600 text-white px-4 py-2 rounded">Excluir</button>
                </div>
            </div>
        </div>
    @endif

</div>
