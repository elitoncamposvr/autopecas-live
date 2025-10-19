<div class="p-6">
    {{-- Alerta padrão --}}
    @if (session('success'))
        <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50" role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                 viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div class="ms-3 text-sm font-medium">{{ session('success') }}</div>
        </div>
    @endif

    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-700">Agendamentos</h2>
        <button wire:click="openCreateModal"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
            Novo Agendamento
        </button>
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

    {{-- Modal Criar/Editar --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
                <h2 class="text-lg font-bold mb-4">
                    {{ $isEdit ? 'Editar Agendamento' : 'Novo Agendamento' }}
                </h2>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <input type="text" wire:model="client"
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('client') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Celular</label>
                            <input type="text" wire:model="cellphone"
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('cellphone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Serviço</label>
                            <textarea wire:model="service"
                                      class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('service') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mecânico</label>
                            <input type="text" wire:model="mechanic"
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status"
                                    class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="pendente">Pendente</option>
                                <option value="concluido">Concluído</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data</label>
                            <input type="date" wire:model="date"
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hora</label>
                            <input type="time" wire:model="time"
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Observações</label>
                            <textarea wire:model="notes"
                                      class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="$set('showModal', false)"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Cancelamento --}}
    @if ($showCancelModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h2 class="text-lg font-bold mb-4 text-red-600">Cancelar Agendamento</h2>

                <form wire:submit.prevent="cancel">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivo do cancelamento</label>
                    <textarea wire:model="cancel_reason"
                              class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"></textarea>
                    @error('cancel_reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="$set('showCancelModal', false)"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg">
                            Fechar
                        </button>
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Confirmar Cancelamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ================= DELETE CONFIRM ================= --}}
    @if($confirmingDelete && $appointmentToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-semibold mb-2 text-red-600">Confirmar Exclusão</h3>
                <p class="mb-4">
                    Deseja realmente excluir o agendamento de
                    <strong>{{ $appointmentToDelete->client }}</strong>
                    no dia <strong>{{ \Carbon\Carbon::parse($appointmentToDelete->date)->format('d/m/Y') }}</strong>?
                </p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('confirmingDelete', false)"
                            class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button wire:click="deleteConfirmed"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showLogs)
        <div id="logsModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl">
                <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Histórico de Logs
                    </h3>
                    <button type="button" wire:click="$set('showLogs', false)" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-4 space-y-4 max-h-[60vh] overflow-y-auto">
                    @forelse($logs as $log)
                        <div class="border rounded-lg p-3 dark:border-gray-600">
                            <div class="flex justify-between">
                        <span class="text-sm text-gray-700 dark:text-gray-300 font-semibold">
                            {{ ucfirst($log->action) }}
                        </span>
                                <span class="text-xs text-gray-500">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $log->description }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                por: {{ $log->user->name ?? 'Usuário removido' }}
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Nenhum log encontrado.</p>
                    @endforelse
                </div>

                <div class="flex justify-end p-3 border-t dark:border-gray-700">
                    <button type="button" wire:click="$set('showLogs', false)"
                            class="px-4 py-2 text-sm text-white bg-gray-600 rounded-lg hover:bg-gray-700">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    @endif


</div>
