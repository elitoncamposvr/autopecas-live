<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold text-gray-700">Supplier Quotes</h1>
        <input type="text" wire:model.live="search" placeholder="Search supplier..."
               class="border-gray-300 rounded-md text-sm px-3 py-1" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border rounded">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-3 py-2 text-left">Supplier</th>
                <th class="px-3 py-2 text-left">Phone</th>
                <th class="px-3 py-2 text-left">CNPJ</th>
                <th class="px-3 py-2 text-center">Quotes Count</th>
                <th class="px-3 py-2 text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($suppliers as $supplier)
                @php
                    $quoteCount = \App\Models\Quote::where('supplier_id', $supplier->id)->count();
                @endphp
                <tr class="border-b">
                    <td class="px-3 py-2">{{ $supplier->name }}</td>
                    <td class="px-3 py-2">{{ $supplier->phone ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $supplier->cnpj ?? '-' }}</td>
                    <td class="px-3 py-2 text-center">{{ $quoteCount }}</td>
                    <td class="px-3 py-2 text-right">
                        <button wire:click="openViewModal({{ $supplier->id }})"
                                class="text-blue-600 hover:text-blue-800 text-xs">
                            View Quotes
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-3 text-gray-500">No suppliers found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $suppliers->links() }}
    </div>

    @if($showViewModal)
        @include('livewire.quotes.modals.supplier-view')
    @endif
</div>
