@if($confirmingDelete && $orderToDelete)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Confirmar Exclusão</h3>
            <p class="mb-4 text-gray-700 dark:text-gray-300">
                Deseja realmente excluir <strong>{{ $orderToDelete->client }}</strong>?
            </p>
            <div class="flex justify-end space-x-2">
                <button wire:click="$set('confirmingDelete', false)"
                        class="bg-gray-200 dark:bg-gray-700 dark:text-gray-200 px-4 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </button>
                <button wire:click="delete"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                    Excluir
                </button>
            </div>
        </div>
    </div>
@endif
