<div x-data="{ open: @entangle('showExportModal') }" x-show="open"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div @click.away="open=false" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-lg p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
            Exportar Relatório de Agendamentos
        </h3>

        {{-- Campos de filtro --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                <input type="text" wire:model.defer="exportClient"
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2
                              bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select wire:model.defer="exportStatus"
                        class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2
                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Todos</option>
                    <option value="pendente">Pendente</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Inicial</label>
                <input type="date" wire:model.defer="exportStartDate"
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2
                              bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Final</label>
                <input type="date" wire:model.defer="exportEndDate"
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2
                              bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
        </div>

        {{-- Tipo de exportação --}}
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tipo de Exportação
            </label>
            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="radio" wire:model="exportType" value="pdf"
                           class="text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">PDF</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" wire:model="exportType" value="excel"
                           class="text-green-600 border-gray-300 focus:ring-green-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Excel</span>
                </label>
            </div>
        </div>

        {{-- Botões de ação --}}
        <div class="flex justify-end gap-2 mt-6">
            <button wire:click="closeExportModal"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                Fechar
            </button>

            <button wire:click="clearFilters"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                Limpar filtros
            </button>

            <button wire:click="exportData"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                Exportar
            </button>
        </div>
    </div>
</div>
