@extends('layouts.developer')

@section('content')
{{-- Menghilangkan div terluar yang memiliki bg-gray-50 dan min-h-screen --}}
<div class="p-6 space-y-6"> {{-- Tambahkan p-6 dan space-y-6 untuk jarak antar bagian --}}

    <h1 class="text-2xl font-bold text-gray-800">Tiket Saya</h1>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded-lg shadow-sm"> {{-- Hapus mb-6 karena space-y-6 akan menangani jarak --}}
        <form action="{{ route('developer.myticket') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID atau Judul..." class="md:col-span-2 w-full border-gray-300 rounded-md focus:ring-blue-500">
                <select name="priority_id" class="w-full border-gray-300 rounded-md focus:ring-blue-500">
                    <option value="">Semua Prioritas</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" {{ request('priority_id') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
                    @endforeach
                </select>
                <select name="status_id" class="w-full border-gray-300 rounded-md focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-4 flex flex-col sm:flex-row justify-end gap-2">
                <a href="{{ route('developer.myticket') }}" class="px-4 py-2 text-sm font-medium text-gray-700 text-center rounded-md hover:bg-gray-100 w-full sm:w-auto">Reset</a>
                <button type="submit" class="px-6 py-2 bg-black text-white rounded-md font-semibold text-sm hover:bg-gray-800 w-full sm:w-auto">Terapkan Filter</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden"> {{-- Hapus mb-6 karena space-y-6 akan menangani jarak --}}
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
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ optional($ticket->status)->name == 'In Progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ optional($ticket->status)->name ?? 'N/A' }}
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