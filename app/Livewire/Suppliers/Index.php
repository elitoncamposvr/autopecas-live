<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use withPagination;

    public $supplierId;
    public $name, $email, $phone, $cnpj, $address;
    public $search = '';
    public $showModal = false;
    public $isEdit = false;
    public $alertMessage = '';

    protected $pagiginationTheme = 'tailwind';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',
        'cnpj' => 'nullable|string|max:50',
        'address' => 'nullable|string|max:255',
    ];

    public function render()
    {
        $suppliers = Supplier::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('cnpj', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.suppliers.index', compact('suppliers'))
            ->layout('layouts.app');
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'phone', 'cnpj', 'address', 'supplierId']);

        if ($id){
            $supplier = Supplier::findOrFail($id);
            $this->supplierId = $id;
            $this->name = $supplier->name;
            $this->email = $supplier->email;
            $this->phone = $supplier->phone;
            $this->cnpj = $supplier->cnpj;
            $this->address = $supplier->address;
            $this->isEdit = true;
        } else {
            $this->isEdit = false;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        Supplier::updateOrCreate(
            ['id' => $this->supplierId],
            [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'cnpj' => $this->cnpj,
                'address' => $this->address,
            ]
        );

        $this->reset(['showModal', 'supplierId', 'name', 'email', 'phone', 'cnpj', 'address', 'isEdit']);
        $this->resetValidation();

        // 🔹 Atualiza a listagem sem precisar recarregar
        $this->dispatch('$refresh');
    }

    public function delete($id)
    {
        Supplier::find($id)->delete();
        $this->alertMessage = 'Fornecedor removido com sucesso!';
    }

    public function clearFilters()
    {
        $this->reset('search', 'name', 'cnpj');
    }
}
