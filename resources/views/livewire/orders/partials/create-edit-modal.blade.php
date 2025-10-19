<div
    x-data="{ open: @entangle('showModal') }"
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
>
    <div @click.away="open=false"
         class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-lg p-6 transition">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
            {{ $orderId ? 'Editar Pedido' : 'Novo Pedido' }}
        </h3>

        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Cliente</label>
                <input type="text" wire:model.defer="client"
                       class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm">
                @error('client') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">OS</label>
                    <input type="text" wire:model.defer="os_reference"
                           class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium">Transportadora</label>
                    <input type="text" wire:model.defer="carrier"
                           class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium">Descrição</label>
                <textarea wire:model.defer="description" rows="2"
                          class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium">Observações</label>
                <textarea wire:model.defer="notes" rows="2"
                          class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Preço</label>
                    <input type="number" step="0.01" wire:model.defer="price"
                           class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium">Entrega prevista</label>
                    <input type="date" wire:model.defer="expected_delivery"
                           class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium">Status</label>
                <select wire:model.defer="status"
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm">
                    <option value="pendente">Pendente</option>
                    <option value="andamento">Em andamento</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="open=false"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>
