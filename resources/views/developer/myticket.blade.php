@extends('layouts.developer')

@section('content')

<div class="px-8 py-6 bg-gray-50 min-h-screen"> {{-- Sesuaikan background dan padding luar --}}

    <h1 class="text-2xl font-bold text-gray-800">Tiket Saya</h1>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Pencarian dan Filter dengan desain referensi "Ambil Tiket" --}}
    <form action="{{ route('developer.myticket') }}" method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4"> {{-- PERBAIKAN DI SINI: md:grid-cols-5 --}}
            {{-- Input Pencarian --}}
            <input
                type="text"
                name="search"
                placeholder="Cari ID atau Judul..."
                class="w-full px-4 py-2 rounded-md border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 md:col-span-2" {{-- md:col-span-2 agar tetap lebar --}}
                value="{{ request('search') }}"
            >
            {{-- Dropdown Filter Prioritas --}}
            <select name="priority_id" class="w-full px-4 py-2 rounded-md border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Prioritas</option>
                @foreach($priorities as $priority)
                    <option value="{{ $priority->id }}" {{ request('priority_id') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
                @endforeach
            </select>
            {{-- Dropdown Filter Status --}}
            <select name="status_id" class="w-full px-4 py-2 rounded-md border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                @endforeach
            </select>
            {{-- Tombol Cari & Filter --}}
            <button type="submit" class="w-full bg-black text-white px-5 py-2 rounded-md font-medium hover:bg-gray-800">Cari & Filter</button>
        </div>
    </form>


    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Pelapor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Prioritas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $ticket->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ Str::limit($ticket->title, 35) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ optional($ticket->author)->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ optional($ticket->priority)->name == 'Tinggi' ? 'text-red-600' : (optional($ticket->priority)->name == 'Sedang' ? 'text-yellow-600' : 'text-green-600') }}">{{ optional($ticket->priority)->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusName = optional($ticket->status)->name;
                                $statusClass = 'bg-gray-100 text-gray-800'; // Default
                                switch ($statusName) {
                                    case 'Open':
                                        $statusClass = 'bg-blue-100 text-blue-800'; // Matches Dashboard "Baru"
                                        break;
                                    case 'In Progress':
                                        $statusClass = 'bg-orange-100 text-orange-800'; // Matches Dashboard "Diproses"
                                        break;
                                    case 'On Hold':
                                        $statusClass = 'bg-yellow-100 text-yellow-800'; // Standard for On Hold
                                        break;
                                    case 'Closed':
                                        $statusClass = 'bg-green-100 text-green-800'; // Matches Dashboard "Selesai"
                                        break;
                                }
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusName ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <a href="{{ route('developer.tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-900 w-full inline-block sm:w-auto text-center py-1 rounded">Detail & Kerjakan</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada tiket yang cocok dengan filter Anda, atau Anda belum mengambil tiket.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-white border-t flex justify-center sm:justify-end">
            {{ $tickets->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection