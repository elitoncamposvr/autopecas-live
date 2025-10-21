<div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <button wire:click="$set('showModal', false)"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold">
            ✕
        </button>

        <h2 class="text-lg font-semibold text-gray-700 mb-4">
            {{ $isEdit ? 'Edit Supplier' : 'Add Supplier' }}
        </h2>

        <div class="space-y-4">
            <div>
                <label class="text-sm text-gray-600">Name</label>
                <input type="text" wire:model="name" class="w-full border-gray-300 rounded-md text-sm px-3 py-1">
                @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <input type="email" wire:model="email" class="w-full border-gray-300 rounded-md text-sm px-3 py-1">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Phone</label>
                    <input type="text" wire:model="phone" class="w-full border-gray-300 rounded-md text-sm px-3 py-1">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm text-gray-600">CNPJ</label>
                    <input type="text" wire:model="cnpj" class="w-full border-gray-300 rounded-md text-sm px-3 py-1">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Address</label>
                    <input type="text" wire:model="address" class="w-full border-gray-300 rounded-md text-sm px-3 py-1">
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="$set('showModal', false)"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm">
                Cancel
            </button>

            <button wire:click="save"
                    wire:loading.attr="disabled"
                    class="ml-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm flex items-center justify-center gap-2">

                {{-- Texto normal --}}
                <span wire:loading.remove wire:target="save">Save</span>

                {{-- Loading com spinner --}}
                <span wire:loading wire:target="save" class="flex items-center gap-1">
        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>
        Saving...
    </span>
            </button>


        </div>
    </div>
</div>
