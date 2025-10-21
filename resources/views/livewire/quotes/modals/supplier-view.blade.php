<div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6 max-h-[90vh] overflow-y-auto relative">
        <button wire:click="closeModal"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold">
            ✕
        </button>

        @if($selectedSupplier)
            <h2 class="text-lg font-semibold mb-4 text-gray-700">
                Quotes for {{ $selectedSupplier->name }}
            </h2>

            <table class="min-w-full text-sm border rounded">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-3 py-2">Item</th>
                    <th class="px-3 py-2">Brand</th>
                    <th class="px-3 py-2">Qty</th>
                    <th class="px-3 py-2">Unit Price</th>
                    <th class="px-3 py-2">Total</th>
                    <th class="px-3 py-2">Status</th>
                </tr>
                </thead>
                <tbody>
                @forelse($quotes as $quote)
                    <tr class="border-b">
                        <td class="px-3 py-2">{{ $quote->item->description ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $quote->brand ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $quote->quantity }}</td>
                        <td class="px-3 py-2">R$ {{ number_format($quote->unit_price, 2, ',', '.') }}</td>
                        <td class="px-3 py-2">R$ {{ number_format($quote->total_value, 2, ',', '.') }}</td>
                        <td class="px-3 py-2">
                            @if($quote->included_in_purchase)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-300">
                                        Included
                                    </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-300">
                                        Pending
                                    </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-3 text-gray-500">
                            No quotes registered for this supplier.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        @endif

        <div class="mt-6 flex justify-end">
            <button wire:click="closeModal"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm">
                Close
            </button>
        </div>
    </div>
</div>
