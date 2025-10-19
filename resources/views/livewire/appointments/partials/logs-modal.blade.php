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
