<div class="overflow-x-auto">
    <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg">
        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
        <tr>
            <th class="px-3 py-2 text-left">Date</th>
            <th class="px-3 py-2 text-left">User</th>
            <th class="px-3 py-2 text-left">Module</th>
            <th class="px-3 py-2 text-left">Action</th>
            <th class="px-3 py-2 text-left">Details</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($logs as $log)
            <tr>
                <td class="px-3 py-2">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-3 py-2">{{ $log->user->name ?? '-' }}</td>

                {{-- Badge colorido por módulo --}}
                <td class="px-3 py-2">
                    @php
                        $colors = [
                            'orders' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                            'appointments' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                            'quotes' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100',
                            'suppliers' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                            'items' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                            'default' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                        ];
                        $color = $colors[$log->module] ?? $colors['default'];
                    @endphp

                    <span class="px-2 py-1 text-xs rounded font-medium {{ $color }}">
                            {{ ucfirst($log->module ?? 'System') }}
                        </span>
                </td>

                <td class="px-3 py-2 capitalize">{{ str_replace('_', ' ', $log->action) }}</td>

                <td class="px-3 py-2 text-gray-700 dark:text-gray-300">
                    @if($log->details)
                        {{ $log->details }}
                    @elseif($log->old_value || $log->new_value)
                        {{ $log->old_value ? "From: {$log->old_value} → To: {$log->new_value}" : $log->new_value }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-3 text-gray-500 dark:text-gray-400">
                    No activity recorded.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>
