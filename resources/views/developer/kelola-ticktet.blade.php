@extends('layouts.developer')

@section('content')
<div class="w-full h-screen bg-[#F5F6FA] flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center px-10 py-3 bg-white w-[calc(100%-280px)] ml-[280px] fixed top-0 z-50">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-[#1F1F1F] relative">
                <div class="absolute w-6 h-6 bg-black rounded-full top-2 left-2"></div>
            </div>
            <div>
                <p class="font-bold text-sm text-[#404040]">Hafidz Irham</p>
                <p class="text-xs font-medium text-[#565656]">Developer</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="mt-[80px] ml-[280px] px-10 py-6 w-[calc(100%-280px)] overflow-auto">
        <h1 class="text-2xl font-semibold text-[#1F1F1F] mb-6">Kelola Tiket</h1>

        <!-- Cards -->
        <div class="grid grid-cols-5 gap-4 mb-6">
            @php
                $cards = [
                    ['label' => 'Prioritas Tinggi (Baru)', 'icon' => '🔥', 'color' => 'text-red-500'],
                    ['label' => 'Tiket Baru', 'icon' => '📥', 'color' => 'text-blue-500'],
                    ['label' => 'Tiket Diproses', 'icon' => '⏳', 'color' => 'text-indigo-500'],
                    ['label' => 'Tiket Selesai', 'icon' => '🏁', 'color' => 'text-green-500'],
                    ['label' => 'Total Tiket', 'icon' => '📋', 'color' => 'text-black'],
                ];
            @endphp
            @foreach($cards as $card)
                <div class="bg-white rounded-xl p-4 shadow border border-gray-200 flex flex-col gap-2">
                    <p class="text-gray-500 text-sm">{{ $card['label'] }}</p>
                    <div class="text-2xl font-bold tracking-wide text-black">999.999.999</div>
                    <div class="{{ $card['color'] }} text-xl">{{ $card['icon'] }}</div>
                </div>
            @endforeach
        </div>

        <!-- Search bar -->
        <input type="text" placeholder="Cari data akun..." class="w-full mb-4 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white text-left border border-gray-300 rounded-lg">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="px-4 py-3">ID Tiket</th>
                        <th class="px-4 py-3">Prioritas</th>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Pelapor</th>
                        <th class="px-4 py-3">Tanggal dibuat</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Developer</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                    <tr class="border-b border-gray-200">
                        <td class="px-4 py-2">{{ $ticket->ticket_id }}</td>
                        <td class="px-4 py-2">
                            <span class="text-white text-xs px-2 py-1 bg-red-500 rounded-full">Tinggi</span>
                        </td>
                        <td class="px-4 py-2">{{ $ticket->title }}</td>
                        <td class="px-4 py-2">{{ $ticket->category }}</td>
                        <td class="px-4 py-2">{{ $ticket->reporter }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-2">
                            <span class="text-yellow-600 text-xs px-2 py-1 bg-yellow-100 border border-yellow-300 rounded-full">Diproses</span>
                        </td>
                        <td class="px-4 py-2">{{ $ticket->developer }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('ticket.show', $ticket->id) }}" class="text-blue-500 hover:text-blue-700">🔍</a>
                            <a href="{{ route('ticket.edit', $ticket->id) }}" class="text-gray-700 hover:text-gray-900">✏️</a>
                            <form action="{{ route('ticket.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tiket ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-end mt-4">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
