<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold text-gray-700">Purchase Summary</h1>

        <div class="flex items-center gap-2">
            <input type="text" wire:model.live="search" placeholder="Search item..."
                   class="border-gray-300 rounded-md text-sm px-3 py-1" />

            <select wire:model.live="filterSupplier" class="border-gray-300 rounded-md text-sm">
                <option value="">All Suppliers</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>

            <button wire:click="clearFilters"
                    class="text-xs text-gray-600 hover:text-gray-800 border px-2 py-1 rounded">
                Clear
            </button>

            {{-- Botão Export --}}
            <button wire:click="openExportModal"
                    class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded">
                Export
            </button>
        </div>
    </div>



@forelse($purchasesBySupplier as $supplierGroup)
        <div class="mb-6 bg-white shadow rounded-lg border border-gray-200">
            <div class="flex justify-between items-center px-4 py-3 bg-gray-50 border-b">
                <h2 class="text-lg font-semibold text-gray-700">
                    {{ $supplierGroup['supplier_name'] }}
                </h2>
                <span class="text-sm font-semibold text-green-700">
                    Total: R$ {{ number_format($supplierGroup['total'], 2, ',', '.') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border-t">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-2">Item</th>
                        <th class="px-3 py-2">Brand</th>
                        <th class="px-3 py-2">Qty</th>
                        <th class="px-3 py-2">Unit Price</th>
                        <th class="px-3 py-2">Total</th>
                        <th class="px-3 py-2 text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($supplierGroup['quotes'] as $quote)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $quote->item->description ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $quote->brand ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $quote->quantity }}</td>
                            <td class="px-3 py-2">R$ {{ number_format($quote->unit_price, 2, ',', '.') }}</td>
                            <td class="px-3 py-2">R$ {{ number_format($quote->total_value, 2, ',', '.') }}</td>
                            <td class="px-3 py-2 text-center">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-300">
                                        Purchased
                                    </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="text-center py-6 text-gray-500">
            No purchased items found.
        </div>
    @endforelse

    @if($showExportModal)
        @include('livewire.quotes.modals.purchase-export')
    @endif

</div>
