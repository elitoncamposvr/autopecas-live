<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;

class ActivityLogTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.activity-log-table', compact('logs'));
    }
}
