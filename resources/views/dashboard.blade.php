@php
    use App\Models\Appointment;
    use App\Models\Item;
    use App\Models\Order;
    use App\Models\Supplier;
@endphp
<x-app-layout>
    {{-- Título da página --}}
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Dashboard Gerencial</h1>

    {{-- Cards de resumo geral --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Orders --}}
        <div class="p-5 bg-blue-600 text-white rounded-xl shadow hover:scale-[1.02] transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm uppercase">Orders</h3>
                    <p class="text-3xl font-bold mt-1">{{ Order::count() }}</p>
                </div>
                <i class="fas fa-clipboard-list text-3xl opacity-80"></i>
            </div>
            <p class="text-xs mt-2 opacity-80">Total registered orders</p>
        </div>

        {{-- Appointments --}}
        <div class="p-5 bg-red-600 text-white rounded-xl shadow hover:scale-[1.02] transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm uppercase">Appointments</h3>
                    <p class="text-3xl font-bold mt-1">{{ Appointment::count() }}</p>
                </div>
                <i class="fas fa-calendar-check text-3xl opacity-80"></i>
            </div>
            <p class="text-xs mt-2 opacity-80">Scheduled appointments</p>
        </div>

        {{-- Open Quotes --}}
        <div class="p-5 bg-yellow-500 text-white rounded-xl shadow hover:scale-[1.02] transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm uppercase">Open Quotes</h3>
                    <p class="text-3xl font-bold mt-1">{{ Item::where('status', 'quoting')->count() }}</p>

                </div>
                <i class="fas fa-tags text-3xl opacity-80"></i>
            </div>
            <p class="text-xs mt-2 opacity-80">Quotes awaiting supplier return</p>
        </div>

        {{-- Suppliers --}}
        <div class="p-5 bg-green-600 text-white rounded-xl shadow hover:scale-[1.02] transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm uppercase">Suppliers</h3>
                    <p class="text-3xl font-bold mt-1">{{ Supplier::count() }}</p>
                </div>
                <i class="fas fa-truck text-3xl opacity-80"></i>
            </div>
            <p class="text-xs mt-2 opacity-80">Active registered suppliers</p>
        </div>
    </div>

    {{-- Gráfico de desempenho (últimos 6 meses) --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-blue-600"></i> Monthly Performance
        </h2>

        <div class="w-full h-64" wire:ignore>
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

    {{-- Últimos registros --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Últimas Cotações --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2">
                <i class="fas fa-tags text-yellow-500"></i> Latest Quotes
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-3 py-2 text-left">Item</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-left">Updated</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach(Item::latest()->take(5)->get() as $quote)
                        <tr>
                            <td class="px-3 py-2">{{ $quote->description }}</td>
                            <td class="px-3 py-2">
                                    <span class="px-2 py-1 text-xs rounded
                                        @if($quote->status === 'quoting') bg-yellow-200 text-yellow-800
                                        @elseif($quote->status === 'negotiating') bg-blue-200 text-blue-800
                                        @elseif($quote->status === 'purchased') bg-green-200 text-green-800
                                        @else bg-gray-200 text-gray-700 @endif">
                                        {{ ucfirst($quote->status) }}
                                    </span>
                            </td>
                            <td class="px-3 py-2">{{ $quote->updated_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Últimos Pedidos --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-blue-500"></i> Latest Orders
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-3 py-2 text-left">Client</th>
                        <th class="px-3 py-2 text-left">Total</th>
                        <th class="px-3 py-2 text-left">Created</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach(Order::latest()->take(5)->get() as $order)
                        <tr>
                            <td class="px-3 py-2">{{ $order->client }}</td>
                            <td class="px-3 py-2">R$ {{ number_format($order->total ?? 0, 2, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Atividades recentes --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2">
            <i class="fas fa-history text-gray-600"></i> Recent Activity
        </h2>
        <livewire:activity-log-table/>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const ctx = document.getElementById('performanceChart');
                if (!ctx) return;

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                        datasets: [
                            {
                                label: 'Orders',
                                data: [80, 95, 110, 130, 125, 140],
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.2)',
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Appointments',
                                data: [40, 50, 60, 75, 70, 85],
                                borderColor: '#dc2626',
                                backgroundColor: 'rgba(220, 38, 38, 0.2)',
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {labels: {color: '#cbd5e1'}}
                        },
                        scales: {
                            x: {ticks: {color: '#cbd5e1'}},
                            y: {ticks: {color: '#cbd5e1'}}
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
