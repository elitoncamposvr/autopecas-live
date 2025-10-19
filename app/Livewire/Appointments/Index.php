<?php

namespace App\Livewire\Appointments;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;
use App\Models\AppointmentLog;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $appointmentId;
    public $client;
    public $service;
    public $cellphone;
    public $mechanic;
    public $notes;
    public $date;
    public $time;
    public $status = 'pendente';
    public $cancel_reason;

    // filtros
    public $filterClient = '';
    public $filterMechanic = '';
    public $filterDate = '';
    public $filterStatus = 'todos';

    public $showModal = false;
    public $showCancelModal = false;
    public $isEdit = false;

    public $confirmingDelete = false;
    public $appointmentToDelete;
    public $showLogs = false;
    public $logs = [];

    public $showExportModal = false;
    public $exportStatus = '';
    public $exportClient = '';
    public $exportStartDate;
    public $exportEndDate;
    public $exportType = 'pdf'; // padrão

    protected $rules = [
        'client' => 'required|string|max:255',
        'service' => 'required|string',
        'cellphone' => 'required|string|max:20',
        'mechanic' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'date' => 'required|date',
        'time' => 'required',
        'status' => 'required|in:pendente,concluido,cancelado',
    ];

    public function render()
    {
        $query = Appointment::query();

        if ($this->filterClient) {
            $query->where('client', 'like', "%{$this->filterClient}%");
        }

        if ($this->filterMechanic) {
            $query->where('mechanic', 'like', "%{$this->filterMechanic}%");
        }

        if ($this->filterDate) {
            $query->where('date', $this->filterDate);
        }

        if ($this->filterStatus !== 'todos') {
            $query->where('status', $this->filterStatus);
        }

        $appointments = $query->latest()->paginate(10);

        return view('livewire.appointments.index', [
            'appointments' => $appointments,
        ])->layout('layouts.app');
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $appointment = Appointment::findOrFail($id);

        $this->appointmentId = $appointment->id;
        $this->client = $appointment->client;
        $this->service = $appointment->service;
        $this->cellphone = $appointment->cellphone;
        $this->mechanic = $appointment->mechanic;
        $this->notes = $appointment->notes;
        $this->date = $appointment->date;
        $this->time = $appointment->time;
        $this->status = $appointment->status;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $appointment = Appointment::findOrFail($this->appointmentId);

            if ($appointment->status !== 'pendente') {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Só é possível editar agendamentos pendentes.',
                ]);
                return;
            }

            $appointment->update([
                'client' => $this->client,
                'service' => $this->service,
                'cellphone' => $this->cellphone,
                'mechanic' => $this->mechanic,
                'notes' => $this->notes,
                'date' => $this->date,
                'time' => $this->time,
                'status' => $this->status,
            ]);

            AppointmentLog::create([
                'appointment_id' => $appointment->id,
                'user_id' => Auth::id(),
                'action' => 'atualizado',
                'description' => "Agendamento atualizado para {$appointment->client}"
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Agendamento atualizado com sucesso!',
            ]);
        } else {
            $appointment = Appointment::create([
                'client' => $this->client,
                'service' => $this->service,
                'cellphone' => $this->cellphone,
                'mechanic' => $this->mechanic,
                'notes' => $this->notes,
                'date' => $this->date,
                'time' => $this->time,
                'status' => 'pendente',
                'user_id' => Auth::id(),
            ]);

            AppointmentLog::create([
                'appointment_id' => $appointment->id,
                'user_id' => Auth::id(),
                'action' => 'criação',
                'description' => "Agendamento criado para {$appointment->client} em {$appointment->date} {$appointment->time}"
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Agendamento criado com sucesso!',
            ]);
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function openCancelModal($id)
    {
        $this->appointmentId = $id;
        $this->cancel_reason = '';
        $this->showCancelModal = true;
    }

    public function cancel()
    {
        $this->validate([
            'cancel_reason' => 'required|string|max:1000'
        ]);

        $appointment = Appointment::findOrFail($this->appointmentId);

        if ($appointment->status !== 'pendente') {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Só é possível cancelar agendamentos pendentes.',
            ]);
            return;
        }

        $appointment->update([
            'status' => 'cancelado',
            'cancel_reason' => $this->cancel_reason,
        ]);

        AppointmentLog::create([
            'appointment_id' => $appointment->id,
            'user_id' => Auth::id(),
            'action' => 'cancelamento',
            'description' => "Agendamento cancelado. Motivo: {$this->cancel_reason}"
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Agendamento cancelado com sucesso!',
        ]);

        $this->showCancelModal = false;
    }



    public function delete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Agendamento excluído com sucesso!',
        ]);
    }

    public function confirmDelete($id)
    {
        $this->appointmentToDelete = Appointment::find($id);
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        if ($this->appointmentToDelete) {
            AppointmentLog::create([
                'appointment_id' => $this->appointmentToDelete->id,
                'user_id' => auth()->id(),
                'action' => 'exclusão',
                'description' => "Agendamento do cliente {$this->appointmentToDelete->client} em {$this->appointmentToDelete->date} foi excluído."
            ]);

            $this->appointmentToDelete->delete();
            $this->appointmentToDelete = null;
            $this->confirmingDelete = false;
            session()->flash('success', 'Agendamento excluído com sucesso!');
        }
    }

    public function resetForm()
    {
        $this->reset([
            'appointmentId', 'client', 'service', 'cellphone',
            'mechanic', 'notes', 'date', 'time', 'status', 'cancel_reason'
        ]);
        $this->status = 'pendente';
    }

    public function clearFilters()
    {
        $this->reset(['filterClient', 'filterMechanic', 'filterDate', 'filterStatus']);
        $this->filterStatus = 'todos';
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

        if ($this->exportType === 'pdf') {
            $pdf = \PDF::loadView('exports.orders-pdf', ['orders' => $orders]);
            return response()->streamDownload(fn() => print($pdf->output()), 'relatorio-pedidos.pdf');
        }

        if ($this->exportType === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\OrdersExport($orders), 'relatorio-pedidos.xlsx');
        }
    }

    public function viewLogs($appointmentId)
    {
        $this->logs = AppointmentLog::where('appointment_id', $appointmentId)
            ->with('user')
            ->latest()
            ->get();

        $this->showLogs = true;
    }

}
