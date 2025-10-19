<div class="py-6 transition-colors duration-300 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-gray-800 dark:text-gray-100">


        {{-- CABEÇALHO --}}
        <div class="flex justify-between items-center mb-6">
            <div class="flex gap-2">
                <input type="text" wire:model.defer="filterSearch" placeholder="Buscar por cliente ou OS..."
                       class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm px-2 py-1" />
                <select wire:model.defer="filterStatus"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm px-2 py-1">
                    <option value="">Todos</option>
                    <option value="pendente">Pendente</option>
                    <option value="andamento">Em andamento</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>
                <button wire:click="loadOrders"
                        class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Aplicar
                </button>
                <button wire:click="resetFilters"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 dark:text-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Limpar
                </button>
            </div>
            <div class="flex gap-2">
                <button wire:click="openExportModal"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    Exportar
                </button>
                <button wire:click="openModal"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Novo Pedido
                </button>
            </div>
        </div>

        {{-- TABELA --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3">OS</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Ações</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4">{{ $order->client }}</td>
                        <td class="px-6 py-4">{{ $order->os_reference }}</td>
                        <td class="px-6 py-4 capitalize">{{ $order->status }}</td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="openModal({{ $order->id }})"
                                    class="text-blue-600 dark:text-blue-400 hover:underline">Editar</button>
                            <button wire:click="confirmDelete({{ $order->id }})"
                                    class="text-red-600 dark:text-red-400 hover:underline ml-3">Excluir</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Nenhum pedido encontrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>


    @include('livewire.orders.partials.export-modal')

    @include('livewire.orders.partials.create-edit-modal')

    @include('livewire.orders.partials.confirm-delete-modal')

    @include('livewire.orders.partials.alert-modal')

</div>
