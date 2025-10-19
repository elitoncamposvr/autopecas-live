<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;

class Report extends Component
{
    public $orders = [];
    public $filterStatus = '';
    public $filterClient = '';
    public $startDate;
    public $endDate;
    public $alertMessage = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $query = Order::query();

        if ($this->filterStatus && $this->filterStatus !== 'Todos') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterClient) {
            $query->where('client', 'like', '%' . $this->filterClient . '%');
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($this->startDate));
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($this->endDate));
        }

        $this->orders = $query->orderBy('created_at', 'desc')->get();
    }

    public function applyFilters()
    {
        $this->loadData();
    }

    public function resetFilters()
    {
        $this->reset(['filterStatus', 'filterClient', 'startDate', 'endDate']);
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.orders.report')->layout('layouts.app');
    }
}
