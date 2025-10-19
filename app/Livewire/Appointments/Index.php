<?php

namespace App\Livewire\Appointments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;
use App\Models\AppointmentLog;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use PDF;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    // Campos principais
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

    // Filtros
    public $filterClient = '';
    public $filterMechanic = '';
    public $filterDate = '';
    public $filterStatus = 'todos';

    // Controle de modais
    public $showModal = false;
    public $showCancelModal = false;
    public $confirmingDelete = false;
    public $showLogs = false;
    public $showExportModal = false;
    public $isEdit = false;

    // Dados auxiliares
    public $appointmentToDelete;
    public $logs = [];
    public $alertMessage = '';

    // Filtros de exportação
    public $exportStatus = '';
    public $exportClient = '';
    public $exportStartDate;
    public $exportEndDate;
    public $exportType = 'pdf';

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

    // ================= RENDER ==================
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

    // ================= CRUD ==================

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
                $this->alertMessage = 'Só é possível editar agendamentos pendentes.';
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
                'description' => "Agendamento atualizado para {$appointment->client}",
            ]);

            $this->alertMessage = 'Agendamento atualizado com sucesso!';
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
                'description' => "Agendamento criado para {$appointment->client} em {$appointment->date} {$appointment->time}",
            ]);

            $this->alertMessage = 'Agendamento criado com sucesso!';
        }

        $this->resetForm();
        $this->showModal = false;
    }

    // ================= CANCELAMENTO ==================

    public function openCancelModal($id)
    {
        $appointment = Appointment::findOrFail($id);
        $this->appointmentId = $appointment->id;
        $this->client = $appointment->client;
        $this->date = $appointment->date;
        $this->time = $appointment->time;
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
            $this->alertMessage = 'Só é possível cancelar agendamentos pendentes.';
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
            'description' => "Agendamento cancelado. Motivo: {$this->cancel_reason}",
        ]);

        $this->alertMessage = 'Agendamento cancelado com sucesso!';
        $this->showCancelModal = false;
    }

    // ================= EXCLUSÃO ==================

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
                'description' => "Agendamento do cliente {$this->appointmentToDelete->client} em {$this->appointmentToDelete->date} foi excluído.",
            ]);

            $this->appointmentToDelete->delete();
            $this->appointmentToDelete = null;
            $this->confirmingDelete = false;
            $this->alertMessage = 'Agendamento excluído com sucesso!';
        }
    }

    // ================= AUXILIARES ==================

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

    public function loadAppointments()
    {
        $this->resetPage();
    }

    // ================= LOGS ==================
    public function viewLogs($appointmentId)
    {
        $this->logs = AppointmentLog::where('appointment_id', $appointmentId)
            ->with('user')
            ->latest()
            ->get();

        $this->showLogs = true;
    }

    // ================= EXPORTAÇÃO ==================
    public function exportData()
    {
        $query = Appointment::query();

        if ($this->exportStatus && $this->exportStatus !== 'todos') {
            $query->where('status', $this->exportStatus);
        }

        if ($this->exportClient) {
            $query->where('client', 'like', '%' . $this->exportClient . '%');
        }

        if ($this->exportStartDate) {
            $query->whereDate('date', '>=', $this->exportStartDate);
        }

        if ($this->exportEndDate) {
            $query->whereDate('date', '<=', $this->exportEndDate);
        }

        $appointments = $query->orderByDesc('date')->get();

        if ($this->exportType === 'pdf') {
            $pdf = PDF::loadView('exports.appointments-pdf', ['appointments' => $appointments]);
            return response()->streamDownload(fn() => print($pdf->output()), 'relatorio-agendamentos.pdf');
        }

        return response()->streamDownload(function () use ($appointments) {
            (new FastExcel($appointments->map(function ($a) {
                return [
                    'Cliente' => $a->client,
                    'Serviço' => $a->service,
                    'Celular' => $a->cellphone,
                    'Mecânico' => $a->mechanic ?? '-',
                    'Data' => Carbon::parse($a->date)->format('d/m/Y'),
                    'Hora' => $a->time,
                    'Status' => ucfirst($a->status),
                ];
            })))->export('php://output');
        }, 'relatorio-agendamentos.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // ================= EXPORTAÇÃO MODAL ==================

    public function openExportModal()
    {
        $this->reset([
            'exportStatus',
            'exportClient',
            'exportStartDate',
            'exportEndDate',
            'exportType'
        ]);

        $this->exportType = 'pdf';
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
    }

}
