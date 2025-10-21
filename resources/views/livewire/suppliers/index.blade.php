<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold text-gray-700">Suppliers</h1>

        <div class="flex items-center gap-2">
            <input type="text" wire:model.live="search" placeholder="Search by name or CNPJ"
                   class="border-gray-300 rounded-md text-sm px-3 py-1" />

            <button wire:click="clearFilters"
                    class="text-xs text-gray-600 hover:text-gray-800 border px-2 py-1 rounded">
                Clear
            </button>

            <button wire:click="openModal"
                    class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded">
                Add Supplier
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border rounded">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Email</th>
                <th class="px-3 py-2 text-left">Phone</th>
                <th class="px-3 py-2 text-left">CNPJ</th>
                <th class="px-3 py-2 text-left">Address</th>
                <th class="px-3 py-2 text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($suppliers as $supplier)
                <tr class="border-b">
                    <td class="px-3 py-2">{{ $supplier->name }}</td>
                    <td class="px-3 py-2">{{ $supplier->email ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $supplier->phone ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $supplier->cnpj ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $supplier->address ?? '-' }}</td>
                    <td class="px-3 py-2 text-right space-x-2">
                        <button wire:click="openModal({{ $supplier->id }})"
                                class="text-blue-600 hover:text-blue-800 text-xs">Edit</button>
                        <button wire:click="delete({{ $supplier->id }})"
                                class="text-red-600 hover:text-red-800 text-xs">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-3 text-gray-500">No suppliers found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $suppliers->links() }}
    </div>

    @if($showModal)
        @include('livewire.suppliers.modal')
    @endif
</div>
