<?php

namespace App\Livewire\Quotes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Quote;
use App\Models\ActivityLog;
use App\Models\PurchaseSelection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;
    public $selectedItem;
    public $quotes = [];
    public $logs = [];

    public $activeTab = 'details';
    public $itemId;
    public $description;
    public $brand_desired;
    public $item_code;
    public $required_quantity;
    public $notes;
    public $status = 'quoting';
    public $supplier_id;
    public $brand;
    public $unit_price;
    public $quantity;
    public $valid_until;
    public $quote_notes;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showViewModal = false;
    public $alertMessage = '';
    public $alertType = 'success';
    public $search = '';
    public $filterStatus = '';
    public $filterSupplier = '';

    public $quoteSummary = [
        'total_quotes' => 0,
        'lowest_value' => null,
        'lowest_supplier' => null,
    ];


    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $query = Item::query();

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterSupplier) {
            $query->whereHas('quotes', function ($q) {
                $q->where('included_in_purchase', true)
                    ->where('supplier_id', $this->filterSupplier);
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', "%{$this->search}%")
                    ->orWhere('brand_desired', 'like', "%{$this->search}%")
                    ->orWhere('item_code', 'like', "%{$this->search}%");
            });
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(10);
        $suppliers = Supplier::orderBy('name')->get();

        return view('livewire.quotes.index', compact('items', 'suppliers'))
            ->layout('layouts.app');
    }


    // 🔹 CRUD de Itens
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $item = Item::findOrFail($id);
        $this->fill($item->toArray());
        $this->itemId = $id;
        $this->showEditModal = true;
    }

    public function saveItem()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'brand_desired' => 'nullable|string|max:255',
            'item_code' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'required_quantity' => 'required|integer|min:1',
        ]);

        $item = Item::create([
            'description' => $this->description,
            'brand_desired' => $this->brand_desired,
            'item_code' => $this->item_code,
            'notes' => $this->notes,
            'required_quantity' => $this->required_quantity,
            'status' => 'quoting',
            'created_by' => Auth::id(),
        ]);

        $this->logActivity($item->id, 'item_created', null, $item->description);

        $this->resetForm();
        $this->alert('success', 'Item created successfully.');
        $this->showCreateModal = false;
    }

    public function updateItem()
    {
        $item = Item::findOrFail($this->itemId);

        if ($item->status !== 'quoting') {
            return $this->alert('error', 'Only items in quoting status can be edited.');
        }

        $this->validate([
            'description' => 'required|string|max:255',
            'brand_desired' => 'nullable|string|max:255',
            'item_code' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'required_quantity' => 'required|integer|min:1',
        ]);

        $item->update([
            'description' => $this->description,
            'brand_desired' => $this->brand_desired,
            'item_code' => $this->item_code,
            'notes' => $this->notes,
            'required_quantity' => $this->required_quantity,
        ]);

        $this->logActivity($item->id, 'item_updated', null, 'Item details updated');

        $this->alert('success', 'Item updated successfully.');
        $this->showEditModal = false;
    }

    public function deleteItem($id)
    {
        $item = Item::findOrFail($id);

        if ($item->status !== 'quoting') {
            return $this->alert('error', 'Cannot delete item after negotiation started.');
        }

        $item->delete();

        $this->logActivity($id, 'item_deleted', null, 'Item deleted');
        $this->alert('success', 'Item deleted successfully.');
    }

    // 🔹 Cotações
    public function openViewModal($id)
    {
        $this->itemId = $id;
        $this->selectedItem = Item::with(['quotes.supplier', 'creator'])->find($id);
        $this->quotes = $this->selectedItem?->quotes ?? [];
        $this->logs = ActivityLog::where('item_id', $id)
            ->with('user')
            ->latest()
            ->get();

        $this->logs = \App\Models\ActivityLog::where('item_id', $id)
            ->with('user')
            ->latest()
            ->get();

// Calcular resumo das cotações
        $totalQuotes = $this->quotes->count();

        if ($totalQuotes > 0) {
            $lowestQuote = $this->quotes->sortBy('total_value')->first();
            $this->quoteSummary = [
                'total_quotes' => $totalQuotes,
                'lowest_value' => $lowestQuote->total_value,
                'lowest_supplier' => $lowestQuote->supplier->name ?? '-',
            ];
        } else {
            $this->quoteSummary = [
                'total_quotes' => 0,
                'lowest_value' => null,
                'lowest_supplier' => null,
            ];
        }

        $this->showViewModal = true;

    }

    protected function logActivity($itemId, $action, $oldValue = null, $newValue = null)
    {
        ActivityLog::create([
            'item_id' => $itemId,
            'action' => $action,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'user_id' => Auth::id(),
        ]);
    }



    public function addQuote()
    {
        $this->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'brand' => 'nullable|string|max:255',
            'unit_price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
            'valid_until' => 'nullable|date',
            'quote_notes' => 'nullable|string',
        ]);

        $item = Item::findOrFail($this->itemId);

        if ($item->status !== 'quoting') {
            return $this->alert('error', 'Cannot add quotes after negotiation started.');
        }

        Quote::create([
            'item_id' => $this->itemId,
            'supplier_id' => $this->supplier_id,
            'brand' => $this->brand,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'valid_until' => $this->valid_until,
            'notes' => $this->quote_notes,
            'created_by' => Auth::id(),
        ]);

        $this->resetQuoteForm();
        $this->logActivity($this->itemId, 'quote_added', null, "Quote created for supplier ID {$this->supplier_id}");
        $this->updateQuoteSummary();
        $this->alert('success', 'Quote added successfully.');
    }

    public function includeInPurchase($quoteId)
    {
        DB::transaction(function () use ($quoteId) {
            $quote = Quote::findOrFail($quoteId);
            $item = $quote->item;

            Quote::where('item_id', $item->id)->update(['included_in_purchase' => false]);
            $quote->update(['included_in_purchase' => true]);

            PurchaseSelection::updateOrCreate(
                ['item_id' => $item->id],
                [
                    'quote_id' => $quote->id,
                    'selected_by' => Auth::id(),
                    'selected_at' => now(),
                ]
            );

            $oldStatus = $item->status;
            $item->update(['status' => 'negotiating']);

            ActivityLog::create([
                'item_id' => $item->id,
                'action' => 'quote_selected',
                'old_value' => $oldStatus,
                'new_value' => 'negotiating',
                'user_id' => Auth::id(),
            ]);
        });

        $this->logActivity($item->id, 'quote_selected', $oldStatus, 'negotiating');
        $this->updateQuoteSummary();

    }

    public function updateQuoteSummary()
    {
        $this->quotes = $this->selectedItem->quotes()->with('supplier')->get();
        $totalQuotes = $this->quotes->count();

        if ($totalQuotes > 0) {
            $lowestQuote = $this->quotes->sortBy('total_value')->first();
            $this->quoteSummary = [
                'total_quotes' => $totalQuotes,
                'lowest_value' => $lowestQuote->total_value,
                'lowest_supplier' => $lowestQuote->supplier->name ?? '-',
            ];
        } else {
            $this->quoteSummary = [
                'total_quotes' => 0,
                'lowest_value' => null,
                'lowest_supplier' => null,
            ];
        }
    }


    public function markAsPurchased($id)
    {
        $item = Item::findOrFail($id);

        if ($item->status !== 'negotiating') {
            return $this->alert('error', 'Item must be in negotiation before marking as purchased.');
        }

        $old = $item->status;
        $item->update(['status' => 'purchased']);

        $this->logActivity($item->id, 'status_changed', $old, 'purchased');

        // Atualiza visualização em tempo real
        $this->openViewModal($item->id);
        $this->alert('success', 'Item marked as purchased.');
    }

    public function markAsFinalized($id)
    {
        $item = Item::findOrFail($id);

        if ($item->status !== 'purchased') {
            return $this->alert('error', 'Item must be purchased before marking as finalized.');
        }

        $old = $item->status;
        $item->update(['status' => 'finalized']);

        $this->logActivity($item->id, 'status_changed', $old, 'finalized');

        // Atualiza visualização em tempo real
        $this->openViewModal($item->id);
        $this->alert('success', 'Item finalized successfully.');
    }

    public function reopenQuoting($id)
    {
        $item = Item::findOrFail($id);

        if (!in_array($item->status, ['negotiating', 'purchased'])) {
            return $this->alert('error', 'Only negotiating or purchased items can be reopened.');
        }

        $old = $item->status;
        $item->update(['status' => 'quoting']);

        // Desmarcar todas as cotações selecionadas
        \App\Models\Quote::where('item_id', $id)->update(['included_in_purchase' => false]);

        $this->logActivity($item->id, 'status_changed', $old, 'quoting');
        $this->openViewModal($item->id);
        $this->alert('success', 'Item reopened for quoting.');
    }



    // 🔹 Utilitários
    public function alert($type, $message)
    {
        $this->alertType = $type;
        $this->alertMessage = $message;
    }

    public function resetForm()
    {
        $this->reset(['itemId', 'description', 'brand_desired', 'item_code', 'notes', 'required_quantity']);
    }

    public function resetQuoteForm()
    {
        $this->reset(['supplier_id', 'brand', 'unit_price', 'quantity', 'valid_until', 'quote_notes']);
    }

    public function clearFilters()
    {
        $this->reset(['filterStatus', 'filterSupplier', 'search']);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

}
