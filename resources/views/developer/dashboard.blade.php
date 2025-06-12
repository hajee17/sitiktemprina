@extends('layouts.developer')

@section('content')
<div class="p-6 space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Developer</h1>

    <!-- Statistic Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        @foreach([
            ['title' => 'Prioritas Tinggi (Baru)', 'value' => $highPriorityNew, 'color' => 'bg-red-50 text-red-800'],
            ['title' => 'Tiket Baru', 'value' => $newTickets, 'color' => 'bg-blue-50 text-blue-800'],
            ['title' => 'Tiket Diproses', 'value' => $processedTickets, 'color' => 'bg-yellow-50 text-yellow-800'],
            ['title' => 'Tiket Selesai', 'value' => $completedTickets, 'color' => 'bg-green-50 text-green-800'],
            ['title' => 'Total Tiket', 'value' => $totalTickets, 'color' => 'bg-gray-50 text-gray-800']
        ] as $card)
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="text-sm font-medium text-gray-500">{{ $card['title'] }}</div>
            <div class="text-2xl font-bold mt-1">{{ number_format($card['value']) }}</div>
        </div>
        @endforeach
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Weekly Ticket Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Grafik Tiket Mingguan</h2>
            <div class="h-64">
                <canvas id="weeklyTicketChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Status Tiket</h2>
            <div class="h-64">
                <canvas id="statusDistributionChart"></canvas>
            </div>
            <div class="mt-2 text-center text-sm text-gray-500">
                {{ $newTickets > 0 ? round(($highPriorityNew/$newTickets)*100, 0) : 0 }}% Prioritas Tinggi
            </div>
        </div>
    </div>

    <!-- Recent Tickets Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Tiket Masuk (Terbaru)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Tiket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($latestTickets as $ticket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $ticket->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span @class([
                                'px-2 py-1 text-xs font-semibold rounded-full',
                                'bg-red-100 text-red-800' => optional($ticket->priority)->name === 'Tinggi',
                                'bg-yellow-100 text-yellow-800' => optional($ticket->priority)->name === 'Sedang',
                                'bg-green-100 text-green-800' => optional($ticket->priority)->name === 'Rendah'
                            ])>
                                {{ optional($ticket->priority)->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $ticket->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusName = optional($ticket->status)->name ?? 'Unknown';
                                $statusColors = [
                                    'Open' => 'bg-blue-100 text-blue-800',
                                    'In Progress' => 'bg-yellow-100 text-yellow-800',
                                    'Closed' => 'bg-green-100 text-green-800',
                                    'On Hold' => 'bg-orange-100 text-orange-800'
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$statusName] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusName }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            {{-- PERBAIKAN DI SINI --}}
                            <a href="{{ route('developer.tickets.show', ['ticket' => $ticket->id]) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada tiket terbaru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ... (kode Javascript untuk chart tidak berubah)
    // Weekly Ticket Chart
    const weeklyCtx = document.getElementById('weeklyTicketChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: @json($weekDays),
            datasets: [{
                label: 'Tiket Masuk',
                data: @json($weeklyData),
                backgroundColor: 'rgba(79, 70, 229, 0.7)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 1,
                borderRadius: 6,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.parsed.y} tiket`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($statusDistribution)),
            datasets: [{
                data: @json(array_values($statusDistribution)),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(234, 179, 8, 0.7)',
                    'rgba(16, 185, 129, 0.7)'
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(234, 179, 8, 1)',
                    'rgba(16, 185, 129, 1)'
                ],
                borderWidth: 1,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.parsed;
                            const percentage = Math.round((value / total) * 100);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endpush
@endsection
