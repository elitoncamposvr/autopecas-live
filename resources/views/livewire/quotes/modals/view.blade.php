<div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50">
    {{-- Container do modal com rolagem e botão de fechar --}}
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6 max-h-[90vh] overflow-y-auto relative">
        {{-- Botão fechar canto superior direito --}}
        <button wire:click="$set('showViewModal', false)"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold">
            ✕
        </button>

        <h2 class="text-lg font-semibold mb-4 text-gray-700">Item Details & Quotes</h2>

        {{-- Abas de navegação --}}
        <div class="flex border-b mb-4">
            <button wire:click="setActiveTab('details')"
                    class="px-4 py-2 text-sm font-medium border-b-2 {{ $activeTab === 'details' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Details
            </button>
            <button wire:click="setActiveTab('quotes')"
                    class="px-4 py-2 text-sm font-medium border-b-2 {{ $activeTab === 'quotes' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Quotes
            </button>
            <button wire:click="setActiveTab('history')"
                    class="px-4 py-2 text-sm font-medium border-b-2 {{ $activeTab === 'history' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                History
            </button>
        </div>

        {{-- Conteúdo dinâmico das abas --}}
        @if($selectedItem)
            {{-- TAB: DETAILS --}}
            @if($activeTab === 'details')
                <div class="space-y-4">
                    {{-- Summary --}}
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                            <p class="text-sm text-gray-500">Total Quotes</p>
                            <p class="text-xl font-semibold text-blue-700">{{ $quoteSummary['total_quotes'] }}</p>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                            <p class="text-sm text-gray-500">Lowest Value</p>
                            <p class="text-xl font-semibold text-green-700">
                                @if($quoteSummary['lowest_value'])
                                    R$ {{ number_format($quoteSummary['lowest_value'], 2, ',', '.') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                            <p class="text-sm text-gray-500">Best Supplier</p>
                            <p class="text-base font-semibold text-red-700">
                                {{ $quoteSummary['lowest_supplier'] ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><strong>Description:</strong> {{ $selectedItem->description }}</div>
                        <div><strong>Brand Desired:</strong> {{ $selectedItem->brand_desired ?? '-' }}</div>
                        <div><strong>Item Code:</strong> {{ $selectedItem->item_code ?? '-' }}</div>
                        <div><strong>Quantity:</strong> {{ $selectedItem->required_quantity }}</div>
                        <div>
                            <strong>Status:</strong>
                            @php
                                $statusColors = [
                                    'quoting' => 'bg-gray-100 text-gray-700 border-gray-300',
                                    'negotiating' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                    'purchased' => 'bg-green-100 text-green-700 border-green-300',
                                    'finalized' => 'bg-blue-100 text-blue-700 border-blue-300',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full border {{ $statusColors[$selectedItem->status] ?? 'bg-gray-100 text-gray-600' }}">
        {{ ucfirst($selectedItem->status) }}
    </span>
                        </div>

                    </div>

                    {{-- Botões de status --}}
                    @if(in_array($selectedItem->status, ['negotiating', 'purchased']))
                        <div class="mt-4 flex flex-wrap justify-end gap-3">
                            @if($selectedItem->status === 'negotiating')
                                <button wire:click="markAsPurchased({{ $selectedItem->id }})"
                                        wire:loading.attr="disabled"
                                        class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm rounded-md">
                            <span wire:loading.remove wire:target="markAsPurchased({{ $selectedItem->id }})">
                                Mark as Purchased
                            </span>
                                    <span wire:loading wire:target="markAsPurchased({{ $selectedItem->id }})">
                                Processing...
                            </span>
                                </button>
                            @elseif($selectedItem->status === 'purchased')
                                <button wire:click="markAsFinalized({{ $selectedItem->id }})"
                                        wire:loading.attr="disabled"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md">
                            <span wire:loading.remove wire:target="markAsFinalized({{ $selectedItem->id }})">
                                Mark as Finalized
                            </span>
                                    <span wire:loading wire:target="markAsFinalized({{ $selectedItem->id }})">
                                Processing...
                            </span>
                                </button>
                            @endif

                            <button wire:click="reopenQuoting({{ $selectedItem->id }})"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-md">
                        <span wire:loading.remove wire:target="reopenQuoting({{ $selectedItem->id }})">
                            Reopen Quoting
                        </span>
                                <span wire:loading wire:target="reopenQuoting({{ $selectedItem->id }})">
                            Processing...
                        </span>
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            {{-- TAB: QUOTES --}}
            @if($activeTab === 'quotes')
                <div>
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Quotes</h3>
                    <div class="overflow-x-auto mb-4">
                        <div class="flex justify-end mb-3">
                            <button wire:click="openAddQuoteModal"
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded">
                                + Add Quote
                            </button>
                        </div>

                        <table class="min-w-full text-sm border rounded">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-3 py-2">Supplier</th>
                                <th class="px-3 py-2">Brand</th>
                                <th class="px-3 py-2">Qty</th>
                                <th class="px-3 py-2">Unit Price</th>
                                <th class="px-3 py-2">Total</th>
                                <th class="px-3 py-2">Included</th>
                                <th class="px-3 py-2 text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($quotes as $quote)
                                <tr class="border-b">
                                    <td class="px-3 py-2">{{ $quote->supplier->name ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $quote->brand ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $quote->quantity }}</td>
                                    <td class="px-3 py-2">R$ {{ number_format($quote->unit_price, 2, ',', '.') }}</td>
                                    <td class="px-3 py-2">R$ {{ number_format($quote->total_value, 2, ',', '.') }}</td>
                                    <td class="px-3 py-2">
                                        @if($quote->included_in_purchase)
                                            <span class="text-green-600 font-semibold">Yes</span>
                                        @else
                                            <span class="text-gray-400">No</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-right">
                                        @if(!$quote->included_in_purchase)
                                            <button wire:click="includeInPurchase({{ $quote->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="text-blue-600 hover:text-blue-800 text-xs">
                                            <span wire:loading.remove wire:target="includeInPurchase({{ $quote->id }})">
                                                Include in Purchase
                                            </span>
                                                <span wire:loading wire:target="includeInPurchase({{ $quote->id }})">
                                                Processing...
                                            </span>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3 text-gray-500">No quotes yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- TAB: HISTORY --}}
            @if($activeTab === 'history')
                <div>
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Activity History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border rounded">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-3 py-2">Date</th>
                                <th class="px-3 py-2">User</th>
                                <th class="px-3 py-2">Action</th>
                                <th class="px-3 py-2">Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $log)
                                <tr class="border-b">
                                    <td class="px-3 py-2">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-3 py-2">{{ $log->user->name ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</td>
                                    <td class="px-3 py-2">
                                        {{ $log->old_value ? "From: $log->old_value → To: $log->new_value" : $log->new_value }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-gray-500">No activity recorded.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            @if($showAddQuoteModal)
                @include('livewire.quotes.modals.add-quote')
            @endif
        @endif

        {{-- Botão fechar inferior --}}
        <div class="mt-6 flex justify-end">
            <button wire:click="$set('showViewModal', false)"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm">
                Close
            </button>
        </div>

    </div>
</div>
