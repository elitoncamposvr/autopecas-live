<div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-700">Edit Item</h2>

        <form wire:submit.prevent="updateItem" class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <input type="text" wire:model="description" class="w-full border-gray-300 rounded-md text-sm" />
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Brand Desired</label>
                    <input type="text" wire:model="brand_desired" class="w-full border-gray-300 rounded-md text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Item Code</label>
                    <input type="text" wire:model="item_code" class="w-full border-gray-300 rounded-md text-sm" />
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Required Quantity</label>
                <input type="number" min="1" wire:model="required_quantity" class="w-full border-gray-300 rounded-md text-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea wire:model="notes" rows="3" class="w-full border-gray-300 rounded-md text-sm"></textarea>
            </div>

            <div class="flex justify-end mt-4 space-x-3">
                <button type="button" wire:click="$set('showEditModal', false)" class="px-3 py-2 bg-gray-200 text-gray-800 rounded-md text-sm hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
