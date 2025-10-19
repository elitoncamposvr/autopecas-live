@if($showCancelModal && $appointmentId)
    <div class="fixed inset-0 bg-black bg-opacity-50 dark:bg-gray-900/70 flex items-center justify-center z-50 transition-all duration-300">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full shadow-lg border border-gray-200 dark:border-gray-700">

            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">
                Cancelar Agendamento
            </h3>

            <p class="mb-4 text-gray-600 dark:text-gray-300">
                Você confirma que deseja **cancelar** o agendamento do cliente
                <strong class="text-blue-700 dark:text-blue-400">{{ $client }}</strong>,
                agendado para o dia <strong>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</strong>
                às <strong>{{ $time }}</strong>?
            </p>

            <form wire:submit.prevent="cancel" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo do cancelamento</label>
                    <textarea wire:model.defer="cancel_reason" rows="3"
                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                     rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @error('cancel_reason') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" wire:click="$set('showCancelModal', false)"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        Voltar
                    </button>

                    <button type="submit"
                            class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                        Confirmar Cancelamento
                    </button>
                </div>
            </form>

        </div>
    </div>
@endif
