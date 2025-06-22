@extends('layouts.developer')

@section('content')
<div class="w-full min-h-screen bg-gray-50 p-6"> {{-- Background disamakan dengan contoh --}}

    <h1 class="text-2xl font-bold mb-6 text-gray-800">Kelola Semua Tiket</h1>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistik Card --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach ($stats as $label => $value)
        <div class="bg-white shadow rounded-lg p-4 text-left">
            <div class="text-xs sm:text-sm font-semibold uppercase text-gray-500">{{ ucwords(str_replace('_', ' ', $label)) }}</div>
            <div class="text-2xl sm:text-3xl font-bold">{{ number_format($value) }}</div>
        </div>
        @endforeach
    </div>

    {{-- Form Pencarian dan Filter --}}
    <form action="{{ route('developer.kelola-ticket') }}" method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4"> {{-- PERBAIKAN DI SINI: md:grid-cols-5 --}}
            {{-- Input Pencarian --}}
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID, Judul, atau Pelapor..."
                   class="md:col-span-2 w-full px-4 py-2 border-gray-300 rounded-md focus:ring-blue-500">
            {{-- Dropdown Prioritas --}}
            <select name="priority_id" class="w-full px-4 py-2 border-gray-300 rounded-md focus:ring-blue-500">
                <option value="">Semua Prioritas</option>
                @foreach($priorities as $priority)
                    <option value="{{ $priority->id }}" {{ request('priority_id') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
                @endforeach
            </select>
            {{-- Dropdown Status --}}
            <select name="status_id" class="w-full px-4 py-2 border-gray-300 rounded-md focus:ring-blue-500">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                @endforeach
            </select>
            {{-- Tombol Cari & Filter (dipindahkan ke dalam grid) --}}
            <button type="submit" class="w-full bg-black text-white px-5 py-2 rounded-md font-medium hover:bg-gray-800">Cari & Filter</button>
        </div>
    </form>

    {{-- Tabel Data Tiket --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-black text-white uppercase text-sm">
                    <tr>
                        <th class="px-4 py-3 font-semibold">ID</th>
                        <th class="px-4 py-3 font-semibold">Judul</th>
                        <th class="px-4 py-3 font-semibold">Prioritas</th>
                        <th class="px-4 py-3 font-semibold">Pelapor</th>
                        <th class="px-4 py-3 font-semibold">Developer</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Dibuat</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse ($tickets as $ticket)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-3 whitespace-nowrap">#{{ $ticket->id }}</td>
                        <td class="px-4 py-3">{{ Str::limit($ticket->title, 35) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="font-bold {{ $ticket->priority->name === 'Tinggi' ? 'text-red-600' : ($ticket->priority->name === 'Sedang' ? 'text-yellow-600' : 'text-blue-600') }}">
                                {{ $ticket->priority->name }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $ticket->author->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $ticket->assignee->name ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $statusName = optional($ticket->status)->name;
                                $statusClass = 'bg-gray-100 text-gray-800';
                                switch ($statusName) {
                                    case 'Open':
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'In Progress':
                                        $statusClass = 'bg-orange-100 text-orange-800';
                                        break;
                                    case 'On Hold':
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'Closed':
                                        $statusClass = 'bg-green-100 text-green-800';
                                        break;
                                }
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $statusClass }}">
                                {{ $statusName ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $ticket->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 flex flex-col sm:flex-row gap-2">
                            <button onclick="openModal('detail', {{ json_encode($ticket->load(['priority', 'status', 'category', 'author'])) }})"
                                class="p-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-base">🔍</button>
                            <button onclick="openModal('edit', {{ json_encode($ticket->load('status')) }})"
                                class="p-2 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 text-base">✏️</button>
                            <form action="{{ route('developer.tickets.destroy', $ticket->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Yakin ingin menghapus tiket ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full p-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-base">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">Tidak ada tiket ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 flex justify-center sm:justify-end">
            {{ $tickets->links() }}
        </div>
    </div>
</div>

<div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-11/12 max-w-lg p-6 relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal()" class="absolute top-3 right-3 text-2xl text-gray-500 hover:text-gray-800">×</button>

        <div id="modalDetail" class="hidden space-y-2">
            <h2 class="text-xl font-bold mb-4">Detail Tiket <span id="detailId" class="font-mono"></span></h2>
            <div><strong>Judul:</strong> <span id="detailJudul"></span></div>
            <div><strong>Status:</strong> <span id="detailStatus"></span></div>
            <div><strong>Prioritas:</strong> <span id="detailPrioritas"></span></div>
            <div><strong>Kategori:</strong> <span id="detailKategori"></span></div>
            <div><strong>Pelapor:</strong> <span id="detailPelapor"></span></div>
            <div class="mt-4"><strong>Deskripsi:</strong> <div id="detailDescription" class="mt-1 p-2 bg-gray-50 rounded text-sm text-gray-700 max-h-40 overflow-y-auto"></div></div>
        </div>

        <div id="modalEdit" class="hidden">
            <h2 class="text-xl font-bold mb-4">Edit Tiket <span id="editIdLabel" class="font-mono"></span></h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_to" value="kelola_ticket">
                <div class="mb-3">
                    <label class="block font-semibold mb-1">Judul</label>
                    <input type="text" name="title" id="editJudul" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Status</label>
                    <select name="status_id" id="editStatus" class="w-full border rounded px-3 py-2 mt-1">
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg w-full hover:bg-blue-600">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let allStatuses = @json(App\Models\TicketStatus::all());

    function openModal(type, ticket) {
        const modal = document.getElementById('ticketModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('modalDetail').classList.add('hidden');
        document.getElementById('modalEdit').classList.add('hidden');

        if (type === 'detail') {
            document.getElementById('modalDetail').classList.remove('hidden');
            document.getElementById('detailId').innerText = `#${ticket.id}`;
            document.getElementById('detailJudul').innerText = ticket.title;
            const detailStatusSpan = document.getElementById('detailStatus');
            const statusName = ticket.status ? ticket.status.name : 'N/A';
            let statusClass = 'text-gray-800';
            switch (statusName) {
                case 'Open':
                    statusClass = 'text-blue-800';
                    break;
                case 'In Progress':
                    statusClass = 'text-orange-800';
                    break;
                case 'On Hold':
                    statusClass = 'text-yellow-800';
                    break;
                case 'Closed':
                    statusClass = 'text-green-800';
                    break;
            }
            detailStatusSpan.className = statusClass + ' font-semibold';
            detailStatusSpan.innerText = statusName;

            document.getElementById('detailPrioritas').innerText = ticket.priority.name;
            document.getElementById('detailKategori').innerText = ticket.category.name;
            document.getElementById('detailPelapor').innerText = ticket.author.name;
            document.getElementById('detailDescription').innerText = ticket.description;
        } else if (type === 'edit') {
            document.getElementById('modalEdit').classList.remove('hidden');
            document.getElementById('editForm').action = `{{ url('developer/tickets') }}/${ticket.id}`;
            document.getElementById('editIdLabel').innerText = `#${ticket.id}`;
            document.getElementById('editJudul').value = ticket.title;

            const statusSelect = document.getElementById('editStatus');
            statusSelect.innerHTML = '';
            allStatuses.forEach(status => {
                const option = document.createElement('option');
                option.value = status.id;
                option.innerText = status.name;
                if (ticket.status.id === status.id) {
                    option.selected = true;
                }
                statusSelect.appendChild(option);
            });
        }
    }

    function closeModal() {
        document.getElementById('ticketModal').classList.add('hidden');
        document.getElementById('ticketModal').classList.remove('flex');
    }
</script>
@endpush
@endsection