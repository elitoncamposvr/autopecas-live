<div x-data="{ open: @entangle('showModal') }"
     x-show="open"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 dark:bg-gray-900/70">

    <div @click.away="open=false"
         class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl p-6 overflow-y-auto max-h-[90vh]">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
            {{ $isEdit ? 'Editar Agendamento' : 'Novo Agendamento' }}
        </h3>

        <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- CLIENTE --}}
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                <input type="text" wire:model.defer="client"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                              rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                @error('client') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- SERVIÇO --}}
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Serviço</label>
                <textarea wire:model.defer="service"
                          class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                 rounded-md shadow-sm px-3 py-2 h-20 focus:ring-blue-500 focus:border-blue-500"></textarea>
                @error('service') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- CELULAR --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Celular</label>
                <input type="text" wire:model.defer="cellphone"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                              rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                @error('cellphone') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- MECÂNICO --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mecânico</label>
                <input type="text" wire:model.defer="mechanic"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                              rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                @error('mechanic') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- DATA --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data</label>
                <input type="date" wire:model.defer="date"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                              rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                @error('date') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- HORA --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora</label>
                <input type="time" wire:model.defer="time"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                              rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                @error('time') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- STATUS --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select wire:model.defer="status"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                               rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="pendente">Pendente</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>
                @error('status') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- OBSERVAÇÕES --}}
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
                <textarea wire:model.defer="notes"
                          class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                 rounded-md shadow-sm px-3 py-2 h-20 focus:ring-blue-500 focus:border-blue-500"></textarea>
                @error('notes') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            {{-- BOTÕES --}}
            <div class="col-span-2 flex justify-end gap-2 mt-6">
                <button type="button" @click="open=false"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                               rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ $isEdit ? 'Atualizar' : 'Salvar' }}
                </button>
            </div>

        </form>
    </div>
</div>
