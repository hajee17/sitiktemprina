@extends('layouts.master')

@section('content')
<div class="bg-[#F5F6FA] min-h-screen px-8 py-6">

    {{-- Header --}}
    <div class="bg-white flex justify-end items-center px-10 py-3 rounded-md shadow mb-6">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-black rounded-full"></div>
            <div>
                <p class="font-bold text-sm text-gray-800">Hafidz Irham</p>
                <p class="text-xs text-gray-500">Developer</p>
            </div>
        </div>
    </div>

    {{-- Card Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        @php
            $stats = [
                ['label' => 'Prioritas Tinggi (Baru)', 'icon' => '🛑', 'value' => '999.999.999'],
                ['label' => 'Tiket Baru', 'icon' => '📩', 'value' => '999.999.999'],
                ['label' => 'Tiket Diproses', 'icon' => '⏳', 'value' => '999.999.999'],
                ['label' => 'Tiket Selesai', 'icon' => '🏁', 'value' => '999.999.999'],
                ['label' => 'Total Tiket', 'icon' => '📊', 'value' => '999.999.999'],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="bg-white border border-gray-300 rounded-xl p-4 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-3">
                    <p class="text-sm font-medium text-gray-600 leading-tight">{{ $stat['label'] }}</p>
                    <span class="text-xl">{{ $stat['icon'] }}</span>
                </div>
                <p class="text-2xl font-bold text-black">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Grafik Tiket --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg p-6 shadow col-span-2">
            <h4 class="text-md font-semibold text-gray-700 mb-3">Grafik Tiket Masuk</h4>
            <canvas id="barChart"></canvas>
        </div>
        <div class="bg-white rounded-lg p-6 shadow">
            <h4 class="text-md font-semibold text-gray-700 mb-3">Statistik Tiket</h4>
            <canvas id="donutChart"></canvas>
            <div class="mt-4 space-y-1 text-sm text-gray-600">
                <div><span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2"></span>Prioritas Tinggi</div>
                <div><span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-2"></span>Baru</div>
                <div><span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>Diproses</div>
                <div><span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>Selesai</div>
            </div>
        </div>
    </div>

    {{-- Tabel Tiket --}}
    <div class="bg-white rounded-xl p-6 shadow">
        <h4 class="text-md font-semibold text-gray-700 mb-4">Tiket Masuk (Terbaru)</h4>
        <div class="overflow-auto">
            <table class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                    <tr>
                        <th class="px-4 py-2">ID Tiket</th>
                        <th class="px-4 py-2">Prioritas</th>
                        <th class="px-4 py-2">Judul</th>
                        <th class="px-4 py-2">Kategori</th>
                        <th class="px-4 py-2">Nama Pelapor</th>
                        <th class="px-4 py-2">Tanggal Dibuat</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($tickets as $ticket)
                        <tr>
                            <td class="px-4 py-2">{{ $ticket->id_tiket }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-white text-xs font-medium
                                    {{ $ticket->prioritas == 'Tinggi' ? 'bg-red-500' : ($ticket->prioritas == 'Sedang' ? 'bg-yellow-500' : 'bg-green-500') }}">
                                    {{ $ticket->prioritas }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $ticket->judul }}</td>
                            <td class="px-4 py-2">{{ $ticket->kategori }}</td>
                            <td class="px-4 py-2">{{ $ticket->nama_pelapor }}</td>
                            <td class="px-4 py-2">{{ $ticket->created_at->format('d M Y - H:i') }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $ticket->status == 'Baru' ? 'bg-blue-200 text-blue-800' :
                                        ($ticket->status == 'Diproses' ? 'bg-yellow-200 text-yellow-800' :
                                        ($ticket->status == 'Selesai' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-700')) }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:underline text-sm">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Chart JS --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const barChart = new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            datasets: [{
                label: 'Jumlah Tiket',
                data: [15, 19, 11, 7, 17, 9, 5],
                backgroundColor: '#1F1F1F',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    const donutChart = new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Prioritas Tinggi', 'Baru', 'Diproses', 'Selesai'],
            datasets: [{
                label: 'Statistik',
                data: [10, 10, 5, 6],
                backgroundColor: ['#EF4444', '#3B82F6', '#F59E0B', '#10B981']
            }]
        },
        options: {
            responsive: true,
            cutout: '70%'
        }
    });
</script>
@endpush
@endsection
