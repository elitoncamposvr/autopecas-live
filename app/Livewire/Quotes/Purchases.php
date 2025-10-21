<?php

namespace App\Livewire\Quotes;

use App\Models\Quote;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


class Purchases extends Component
{
    use withPagination;

    public $search = '';
    public $filterSupplier = '';
    public $suppliers;
    public $purchasesBySupplier = [];
    public $showExportModal = false;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->suppliers = Supplier::orderBy('name')->get();
    }

    public function render()
    {
        $query = Quote::with(['supplier','item'])
            ->where('included_in_purchase', true)
            ->whereHas('item', fn($q) => $q->where('status', 'purchased'));

        if ($this->filterSupplier) {
            $query->where('supplier_id', $this->filterSupplier);
        }

        if ($this->search) {
            $query->whereHas('item', function ($q){
               $q->where('description', 'like', '%' . $this->search . '%');
            });
        }

        $quotes = $query->orderBy('supplier_id')->get();

        $this->purchasesBySupplier = $quotes
            ->groupBy('supplier.name')
            ->map(function ($group) {
               return [
                   'supplier_id' => $group->first()->supplier_id,
                   'supplier_name' => $group->first()->supplier_name,
                   'quotes' => $group,
                   'total' => $group->sum('total_value'),
               ];
            });

        return view('livewire.quotes.purchases', [
            'purchasesBySupplier' => $this->purchasesBySupplier,
            'suppliers' => $this->suppliers,
        ])->layout('layouts.app');
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterSupplier']);
    }

    public function openExportModal()
    {
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
    }

    public function exportExcel()
    {
        $filename = 'purchase_summary_' . now()->format('Y_m_d_H_i') . '.xlsx';

        $data = [];
        foreach ($this->purchasesBySupplier as $group) {
            foreach ($group['quotes'] as $quote) {
                $data[] = [
                    'Supplier' => $group['supplier_name'],
                    'Item' => $quote->item->description ?? '-',
                    'Brand' => $quote->brand ?? '-',
                    'Quantity' => $quote->quantity,
                    'Unit Price' => $quote->unit_price,
                    'Total' => $quote->total_value,
                    'Status' => 'Purchased',
                ];
            }
        }

        return response()->streamDownload(function () use ($data) {
            (new FastExcel(collect($data)))->export('php://output');
        }, $filename);
    }

    public function exportPDF()
    {
        $pdf = Pdf::loadView('exports.purchases-pdf', [
            'purchasesBySupplier' => $this->purchasesBySupplier,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'purchase_summary_' . now()->format('Y_m_d_H_i') . '.pdf');
    }
}
