<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- ALERTA --}}
        @if($alertMessage)
            <div class="mb-4 flex items-center p-4 text-blue-800 rounded-lg bg-blue-50" role="alert">
                <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <div class="ms-3 text-sm font-medium">{{ $alertMessage }}</div>
                <button type="button" wire:click="$set('alertMessage', '')"
                        class="ms-auto text-blue-500 hover:bg-blue-200 rounded-lg p-1.5">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- CABEÇALHO --}}
        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-2 mb-4">
                <input type="text" wire:model.defer="filterSearch" placeholder="Buscar por cliente ou OS..."
                       class="border-gray-300 rounded-md shadow-sm px-2 py-1" />

                <select wire:model.defer="filterStatus" class="border-gray-300 rounded-md shadow-sm px-2 py-1">
                    <option value="">Todos</option>
                    <option value="pendente">Pendente</option>
                    <option value="andamento">Em andamento</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>

                <button wire:click="loadOrders" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Aplicar
                </button>

                <button wire:click="resetFilters" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Limpar
                </button>
            </div>


            <button wire:click="openModal" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Novo Pedido
            </button>
        </div>

        {{-- TABELA --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3">OS</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Ações</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4">{{ $order->client }}</td>
                        <td class="px-6 py-4">{{ $order->os_reference }}</td>
                        <td class="px-6 py-4">{{ $order->status }}</td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="openModal({{ $order->id }})"
                                    class="text-blue-600 hover:text-blue-800">Editar</button>
                            <button wire:click="confirmDelete({{ $order->id }})" class="text-red-600 hover:text-red-800 ml-3">Excluir</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhum pedido encontrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="orderModal" tabindex="-1" aria-hidden="true"
         class="{{ $showModal ? 'fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50' : 'hidden' }}">

    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-medium mb-4">{{ $orderId ? 'Editar Pedido' : 'Novo Pedido' }}</h3>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cliente</label>
                    <input type="text" wire:model="client" class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('client') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">OS</label>
                    <input type="text" wire:model="os_reference" class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('os_reference') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model="status" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="pendente">Pendente</option>
                        <option value="andamento">Em andamento</option>
                        <option value="concluido">Concluído</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-200 rounded-md">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    @if($confirmingDelete && $orderToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-semibold mb-2">Confirmar Exclusão</h3>
                <p class="mb-4">Deseja realmente excluir <strong>{{ $orderToDelete->client }}</strong>?</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('confirmingDelete', false)" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
                    <button wire:click="delete" class="bg-red-600 text-white px-4 py-2 rounded">Excluir</button>
                </div>
            </div>
        </div>
    @endif

</div>
