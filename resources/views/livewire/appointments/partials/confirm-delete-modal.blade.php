@if($confirmingDelete && $appointmentToDelete)
    <div class="fixed inset-0 bg-black bg-opacity-50 dark:bg-gray-900/70 flex items-center justify-center z-50 transition-all duration-300">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full shadow-lg border border-gray-200 dark:border-gray-700">

            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">
                Confirmar Exclusão
            </h3>

            <p class="mb-4 text-gray-600 dark:text-gray-300">
                Deseja realmente excluir o agendamento de
                <strong class="text-blue-700 dark:text-blue-400">{{ $appointmentToDelete->client }}</strong>
                agendado para
                <strong>{{ \Carbon\Carbon::parse($appointmentToDelete->date)->format('d/m/Y') }}</strong>
                às <strong>{{ $appointmentToDelete->time }}</strong>?
            </p>

            <div class="flex justify-end space-x-2 mt-4">
                <button
                    wire:click="$set('confirmingDelete', false)"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </button>

                <button
                    wire:click="deleteConfirmed"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Excluir
                </button>
            </div>
        </div>
    </div>
@endif
