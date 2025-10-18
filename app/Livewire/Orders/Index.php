<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $orders;
    public $showModal = false;
    public $orderId;
    public $client;
    public $os_reference;
    public $status = 'pendente';
    public $alertMessage = '';
    public $filterStatus = '';
    public $filterSearch = '';
    public $confirmingDelete = false;
    public $orderToDelete;

    protected $rules = [
        'client' => 'required|string|max:255',
        'os_reference' => 'required|string|max:255',
        'status' => 'required|string|max:255',
    ];

    public function updatingFilterSearch()
    {
        $this->loadOrders();
    }

    public function updatingFilterStatus()
    {
        $this->loadOrders();
    }

    public function mount()
    {
        $this->loadOrders();
    }

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

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['orderId', 'client', 'os_reference', 'status', 'alertMessage']);
        $this->orderId = $id;

        if ($id) {
            $order = Order::findOrFail($id);
            $this->client = $order->client;
            $this->os_reference = $order->os_reference;
            $this->status = $order->status;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->orderId) {
            $order = Order::find($this->orderId);
            $order->update([
                'client' => $this->client,
                'os_reference' => $this->os_reference,
                'status' => $this->status,
                'requester_id' => Auth::id(),
            ]);
            $this->alertMessage = 'Pedido atualizado com sucesso!';
        } else {
            Order::create([
                'client' => $this->client,
                'os_reference' => $this->os_reference,
                'status' => $this->status,
                'requester_id' => Auth::id(),
            ]);
            $this->alertMessage = 'Pedido criado com sucesso!';
        }

        $this->showModal = false;
        $this->loadOrders();
    }

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

            $currentSearch = $this->filterSearch;
            $currentStatus = $this->filterStatus;

            $this->filterSearch = '';
            $this->filterStatus = '';

            $this->confirmingDelete = false;
            $this->orderToDelete = null;

            $this->loadOrders(); // mantém filtros como estão

            // Restaura filtros para os inputs sem afetar o resultado já carregado
            $this->filterSearch = $currentSearch;
            $this->filterStatus = $currentStatus;
        }
    }

    public function render()
    {
        return view('livewire.orders.index')
            ->layout('layouts.app');
    }
}
