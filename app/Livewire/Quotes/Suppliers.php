<?php

namespace App\Livewire\Quotes;

use App\Models\Quote;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class Suppliers extends Component
{
    use withPagination;

    public $search = '';
    public $supplierId;
    public $selectedSupplier;
    public $quotes = [];
    public $showViewModal = false;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $query = Supplier::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $suppliers = $query->orderBy('name')->paginate(10);

        return view('livewire.quotes.suppliers', compact('suppliers'))
            ->layout('layouts.app');
    }

    public function openViewModal($supplierId)
    {
        $this->supplierId = $supplierId;
        $this->selectedSupplier = Supplier::find($supplierId);

        $this->quotes = Quote::with('item')
            ->where('supplier_id', $supplierId)
            ->orderBy('created_at')
            ->get();

        $this->showViewModal = true;
    }

    public function closeModal()
    {
        $this->reset('showViewModal', 'selectedSupplierId', 'quotes');
    }
}
