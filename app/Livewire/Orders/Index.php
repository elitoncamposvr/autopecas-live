<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use PDF;

class Index extends Component
{
    public $orders;

    // === Controle de Modais ===
    public $showModal = false;
    public $confirmingDelete = false;
    public $showExportModal = false;

    // === Dados do Pedido ===
    public $orderToDelete;
    public $orderId;
    public $client;
    public $os_reference;
    public $description;
    public $notes;
    public $price;
    public $expected_delivery;
    public $carrier;
    public $status = 'pendente';
    public $alertMessage = '';

    // === Filtros de Listagem ===
    public $filterStatus = '';
    public $filterSearch = '';

    // === Filtros de Exportação ===
    public $exportStatus = '';
    public $exportClient = '';
    public $exportStartDate;
    public $exportEndDate;
    public $exportType = 'pdf'; // Padrão

    protected $rules = [
        'client' => 'required|string|max:255',
        'os_reference' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'notes' => 'nullable|string',
        'price' => 'nullable|numeric|min:0',
        'expected_delivery' => 'nullable|date',
        'carrier' => 'nullable|string|max:255',
        'status' => 'required|string|max:255',
    ];

    // ==============================
    // ========   MOUNT   ===========
    // ==============================
    public function mount()
    {
        $this->loadOrders();
    }

    // ==============================
    // =======   LISTAGEM   =========
    // ==============================
    public function loadOrders()
    {
        $query = Order::query();

        if ($this->filterStatus && $this->filterStatus !== 'Todos') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterSearch) {
            $query->where(function ($q) {
                $q->where('client', 'like', '%' . $this->filterSearch . '%')
                    ->orWhere('os_reference', 'like', '%' . $this->filterSearch . '%');
            });
        }

        $this->orders = $query->latest()->get();
    }

    public function resetFilters()
    {
        $this->filterStatus = '';
        $this->filterSearch = '';
        $this->loadOrders();
    }

    // ==============================
    // =======   CRIAR / EDITAR   ===
    // ==============================
    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset([
            'orderId', 'client', 'os_reference', 'description', 'notes',
            'price', 'expected_delivery', 'carrier', 'status', 'alertMessage'
        ]);

        $this->orderId = $id;

        if ($id) {
            $order = Order::findOrFail($id);
            $this->client = $order->client;
            $this->os_reference = $order->os_reference;
            $this->description = $order->description;
            $this->notes = $order->notes;
            $this->price = $order->price;
            $this->expected_delivery = $order->expected_delivery;
            $this->carrier = $order->carrier;
            $this->status = $order->status;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->orderId) {
            $order = Order::findOrFail($this->orderId);
            $order->update([
                'client' => $this->client,
                'os_reference' => $this->os_reference,
                'description' => $this->description,
                'notes' => $this->notes,
                'price' => $this->price,
                'expected_delivery' => $this->expected_delivery,
                'carrier' => $this->carrier,
                'status' => $this->status,
                'updated_by' => Auth::id(),
            ]);
            $this->alertMessage = 'Pedido atualizado com sucesso!';
        } else {
            Order::create([
                'client' => $this->client,
                'os_reference' => $this->os_reference,
                'description' => $this->description,
                'notes' => $this->notes,
                'price' => $this->price,
                'expected_delivery' => $this->expected_delivery,
                'carrier' => $this->carrier,
                'status' => $this->status,
                'requester_id' => Auth::id(),
            ]);
            $this->alertMessage = 'Pedido criado com sucesso!';
        }

        $this->showModal = false;
        $this->loadOrders();
    }

    // ==============================
    // =======   EXCLUSÃO   =========
    // ==============================
    public function confirmDelete($id)
    {
        $this->orderToDelete = Order::find($id);
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        if ($this->orderToDelete) {
            $this->orderToDelete->delete();
            $this->alertMessage = 'Pedido excluído com sucesso!';
            $this->confirmingDelete = false;
            $this->orderToDelete = null;
            $this->loadOrders();
        }
    }

    // ==============================
    // =======   EXPORTAÇÃO   =======
    // ==============================
    public function openExportModal()
    {
        $this->reset([
            'exportStatus', 'exportClient',
            'exportStartDate', 'exportEndDate',
            'exportType'
        ]);
        $this->exportType = 'pdf';
        $this->showExportModal = true;
    }

    public function exportData()
    {
        $query = Order::query();

        if ($this->exportStatus && $this->exportStatus !== 'Todos') {
            $query->where('status', $this->exportStatus);
        }

        if ($this->exportClient) {
            $query->where('client', 'like', '%' . $this->exportClient . '%');
        }

        if ($this->exportStartDate) {
            $query->whereDate('created_at', '>=', $this->exportStartDate);
        }

        if ($this->exportEndDate) {
            $query->whereDate('created_at', '<=', $this->exportEndDate);
        }

        $orders = $query->orderByDesc('created_at')->get();

        if ($orders->isEmpty()) {
            $this->alertMessage = 'Nenhum pedido encontrado com os filtros aplicados.';
            return;
        }

        // ===== EXPORTAÇÃO PDF =====
        if ($this->exportType === 'pdf') {
            $pdf = PDF::loadView('exports.orders-pdf', ['orders' => $orders]);
            return response()->streamDownload(
                fn() => print($pdf->output()),
                'relatorio-pedidos.pdf'
            );
        }

        // ===== EXPORTAÇÃO EXCEL =====
        return response()->streamDownload(function () use ($orders) {
            (new FastExcel($orders->map(function ($o) {
                return [
                    'Cliente' => $o->client,
                    'OS' => $o->os_reference,
                    'Status' => ucfirst($o->status),
                    'Descrição' => $o->description,
                    'Preço' => $o->price,
                    'Entrega Prevista' => optional($o->expected_delivery)->format('d/m/Y'),
                    'Data Criação' => $o->created_at->format('d/m/Y H:i'),
                ];
            })))->export('php://output');
        }, 'relatorio-pedidos.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // ==============================
    // =========   RENDER   =========
    // ==============================
    public function render()
    {
        return view('livewire.orders.index')->layout('layouts.app');
    }
}
