<div>
    {{-- Alertas padronizados --}}
    @if ($alertMessage)
        <div class="mb-4">
            <div class="p-4 rounded-lg text-white {{ $alertType === 'success' ? 'bg-green-600' : 'bg-red-600' }}">
                {{ $alertMessage }}
            </div>
        </div>
    @endif

    {{-- Cabeçalho e filtros --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-4 gap-3">
        <div class="flex flex-wrap items-center gap-3">
            <div>
                <label for="filterStatus" class="text-sm font-medium text-gray-700">Status</label>
                <select wire:model.live="filterStatus" id="filterStatus" class="border-gray-300 rounded-md text-sm">
                    <option value="">All</option>
                    <option value="quoting">Quoting</option>
                    <option value="negotiating">Negotiating</option>
                    <option value="purchased">Purchased</option>
                    <option value="finalized">Finalized</option>
                </select>
            </div>

            <div>
                <label for="search" class="text-sm font-medium text-gray-700">Search</label>
                <input wire:model.live="search" id="search" type="text" placeholder="Description, brand or code"
                       class="border-gray-300 rounded-md text-sm" />
            </div>

            <div>
                <label for="filterSupplier" class="text-sm font-medium text-gray-700">Supplier</label>
                <select wire:model.live="filterSupplier" id="filterSupplier" class="border-gray-300 rounded-md text-sm">
                    <option value="">All</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <button wire:click="clearFilters"
                    class="px-3 py-1.5 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300">
                Limpar Filtros
            </button>

        </div>

        <button wire:click="openCreateModal"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
            + New Item
        </button>
    </div>

    {{-- Tabela de itens --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-100 uppercase text-xs">
            <tr>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Brand Desired</th>
                <th class="px-4 py-2">Item Code</th>
                <th class="px-4 py-2">Quantity</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2 text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($items as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $item->description }}</td>
                    <td class="px-4 py-2">{{ $item->brand_desired ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $item->item_code ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $item->required_quantity }}</td>
                    <td class="px-4 py-2">
                            <span @class([
                                'px-2 py-1 text-xs font-semibold rounded-full',
                                'bg-blue-100 text-blue-700' => $item->status === 'quoting',
                                'bg-yellow-100 text-yellow-700' => $item->status === 'negotiating',
                                'bg-green-100 text-green-700' => $item->status === 'purchased',
                                'bg-gray-100 text-gray-700' => $item->status === 'finalized',
                            ])>
                                {{ ucfirst($item->status) }}
                            </span>
                    </td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <button wire:click="openViewModal({{ $item->id }})"
                                class="text-blue-600 hover:text-blue-800 text-sm">View</button>

                        @if ($item->status === 'quoting')
                            <button wire:click="openEditModal({{ $item->id }})"
                                    class="text-yellow-600 hover:text-yellow-800 text-sm">Edit</button>
                            <button wire:click="deleteItem({{ $item->id }})"
                                    onclick="return confirm('Delete this item?')"
                                    class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">No items found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>

    {{-- Modais --}}
    @if ($showCreateModal)
        @include('livewire.quotes.modals.create')
    @endif

    @if ($showEditModal)
        @include('livewire.quotes.modals.edit')
    @endif

    @if ($showViewModal)
        @include('livewire.quotes.modals.view')
    @endif
</div>
