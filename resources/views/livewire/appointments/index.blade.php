<div class="p-6">

    {{--Componente de Alerta --}}
    @include('livewire.appointments.partials.alert-modal')

    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-700">Agendamentos</h2>
        <div class="flex gap-2">
            <button wire:click="openExportModal"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
                Exportar
            </button>
            <button wire:click="openCreateModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                Novo Agendamento
            </button>
        </div>
    </div>


    {{-- Filtros --}}
    <div class="bg-white p-4 rounded-lg shadow mb-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Cliente</label>
                <input type="text" wire:model.defer="filterClient"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Mecânico</label>
                <input type="text" wire:model.defer="filterMechanic"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Data</label>
                <input type="date" wire:model.defer="filterDate"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select wire:model.defer="filterStatus"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="todos">Todos</option>
                    <option value="pendente">Pendente</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button wire:click="$refresh"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Aplicar
                </button>
                <button wire:click="clearFilters"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg">
                    Limpar
                </button>
            </div>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">Cliente</th>
                <th class="px-4 py-3 text-left">Serviço</th>
                <th class="px-4 py-3 text-left">Data</th>
                <th class="px-4 py-3 text-left">Hora</th>
                <th class="px-4 py-3 text-left">Mecânico</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            @forelse($appointments as $appointment)
                <tr class="border-b">
                    <td class="px-4 py-3">{{ $appointment->client }}</td>
                    <td class="px-4 py-3">{{ $appointment->service }}</td>
                    <td class="px-4 py-3">{{ $appointment->date }}</td>
                    <td class="px-4 py-3">{{ $appointment->time }}</td>
                    <td class="px-4 py-3">{{ $appointment->mechanic ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @switch($appointment->status)
                            @case('pendente')
                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full">Pendente</span>
                                @break

                            @case('concluido')
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">Concluído</span>
                                @break

                            @case('cancelado')
                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">Cancelado</span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-4 py-3 flex justify-center gap-2">
                        <button
                            wire:click="viewLogs({{ $appointment->id }})"
                            class="px-2 py-1 text-xs text-white bg-indigo-500 rounded hover:bg-indigo-600">
                            Ver Logs
                        </button>

                        <button wire:click="openEditModal({{ $appointment->id }})"
                                class="text-blue-600 hover:text-blue-800 font-medium">Editar</button>

                        @if ($appointment->status === 'pendente')
                            <button wire:click="openCancelModal({{ $appointment->id }})"
                                    class="text-orange-600 hover:text-orange-800 font-medium">Cancelar</button>
                        @endif

                        <button wire:click="confirmDelete({{ $appointment->id }})"
                                class="text-red-600 hover:text-red-800 font-medium">Excluir</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                        Nenhum agendamento encontrado.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="p-4">{{ $appointments->links() }}</div>
    </div>

    @include('livewire.appointments.partials.confirm-delete-modal')

    @include('livewire.appointments.partials.confirm-cancel-modal')

    @include('livewire.appointments.partials.create-edit-modal')

    @include('livewire.appointments.partials.logs-modal')

    @include('livewire.appointments.partials.export-modal')


</div>
