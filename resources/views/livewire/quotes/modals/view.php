<div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6 my-10">
        <h2 class="text-lg font-semibold mb-4 text-gray-700">Item Details & Quotes</h2>

        {{-- Dados do Item --}}
        @php $item = \App\Models\Item::find($itemId); @endphp

        @if($item)
        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
            <div><strong>Description:</strong> {{ $item->description }}</div>
            <div><strong>Brand Desired:</strong> {{ $item->brand_desired ?? '-' }}</div>
            <div><strong>Item Code:</strong> {{ $item->item_code ?? '-' }}</div>
            <div><strong>Quantity:</strong> {{ $item->required_quantity }}</div>
            <div><strong>Status:</strong>
                <span class="font-semibold capitalize">{{ $item->status }}</span>
            </div>
        </div>

        {{-- Cotações Existentes --}}
        <h3 class="text-md font-semibold text-gray-700 mb-2">Quotes</h3>
        @php $quotes = \App\Models\Quote::where('item_id', $item->id)->with('supplier')->get(); @endphp

        <div class="overflow-x-auto mb-4">
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
                                class="text-blue-600 hover:text-blue-800 text-xs">
                            Include in Purchase
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

        {{-- Adicionar nova cotação --}}
        <h3 class="text-md font-semibold text-gray-700 mb-2">Add Quote</h3>
        <form wire:submit.prevent="addQuote" class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <div>
                <label class="block text-gray-700 text-xs font-semibold">Supplier</label>
                <select wire:model="supplier_id" class="w-full border-gray-300 rounded-md text-sm">
                    <option value="">Select</option>
                    @foreach(\App\Models\Supplier::orderBy('name')->get() as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-xs font-semibold">Brand</label>
                <input type="text" wire:model="brand" class="w-full border-gray-300 rounded-md text-sm" />
            </div>
            <div>
                <label class="block text-gray-700 text-xs font-semibold">Unit Price</label>
                <input type="number" step="0.01" wire:model="unit_price" class="w-full border-gray-300 rounded-md text-sm" />
            </div>
            <div>
                <label class="block text-gray-700 text-xs font-semibold">Quantity</label>
                <input type="number" min="1" wire:model="quantity" class="w-full border-gray-300 rounded-md text-sm" />
            </div>
            <div>
                <label class="block text-gray-700 text-xs font-semibold">Valid Until</label>
                <input type="date" wire:model="valid_until" class="w-full border-gray-300 rounded-md text-sm" />
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-700 text-xs font-semibold">Notes</label>
                <textarea wire:model="quote_notes" rows="2" class="w-full border-gray-300 rounded-md text-sm"></textarea>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                    Add Quote
                </button>
            </div>
        </form>
        @endif

        <div class="mt-6 flex justify-end">
            <button wire:click="$set('showViewModal', false)"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm">
                Close
            </button>
        </div>
    </div>
</div>
