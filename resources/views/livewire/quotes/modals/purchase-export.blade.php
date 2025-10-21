<div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 relative">
        <button wire:click="closeExportModal"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold">
            ✕
        </button>

        <h2 class="text-lg font-semibold text-gray-700 mb-4">Export Purchase Summary</h2>

        <p class="text-sm text-gray-600 mb-4">Choose the desired export format:</p>

        <div class="flex flex-col gap-3">
            <button wire:click="exportPDF"
                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                Export as PDF
            </button>

            <button wire:click="exportExcel"
                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                Export as Excel
            </button>
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="closeExportModal"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm">
                Close
            </button>
        </div>
    </div>
</div>
