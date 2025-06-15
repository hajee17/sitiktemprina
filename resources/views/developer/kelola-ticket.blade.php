{{-- Path: resources/views/developer/kelola-ticket.blade.php --}}

@extends('layouts.developer')

@section('content')
<div class="w-full min-h-screen bg-[#F5F6FA] p-6">

    <h1 class="text-2xl font-bold mb-6 text-gray-800">Kelola Semua Tiket</h1>

    {{-- <<<--- TAMBAHKAN BAGIAN INI UNTUK MENAMPILKAN PESAN SUKSES/ERROR ---}}
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
    {{-- <<<--- AKHIR DARI BAGIAN YANG DITAMBAHKAN ---}}

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach ($stats as $label => $value)
        <div class="bg-white shadow rounded-lg p-4 text-left">
            <div class="text-xs sm:text-sm font-semibold uppercase text-gray-500">{{ ucwords(str_replace('_', ' ', $label)) }}</div>
            <div class="text-2xl sm:text-3xl font-bold">{{ number_format($value) }}</div>
        </div>
        @endforeach
    </div>

    {{-- Tambahkan bagian Search & Filter di sini, mirip dengan kelola akun --}}
    <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center mb-4 gap-4">
        <form action="{{ route('developer.kelola-ticket') }}" method="GET" class="flex flex-grow"> {{-- Arahkan ke rute kelola-ticket --}}
            <input type="text" name="search" placeholder="Cari judul tiket..." value="{{ request('search') }}"
                class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">Cari</button>
        </form>
        {{-- Jika Anda ingin menambahkan tombol lain di sini, seperti "Tambah Tiket", bisa ditaruh di sini --}}
        {{-- Contoh:
        <a href="{{ route('developer.tickets.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 w-full md:w-auto">
            + Tambah Tiket
        </a>
        --}}
    </div>


    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-100 text-left text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="p-3 font-semibold">ID</th>
                        <th class="p-3 font-semibold">Judul</th>
                        <th class="p-3 font-semibold">Prioritas</th>
                        <th class="p-3 font-semibold">Pelapor</th>
                        <th class="p-3 font-semibold">Developer</th>
                        <th class="p-3 font-semibold">Status</th>
                        <th class="p-3 font-semibold">Dibuat</th>
                        <th class="p-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse ($tickets as $ticket)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3 whitespace-nowrap">#{{ $ticket->id }}</td>
                        <td class="p-3">{{ Str::limit($ticket->title, 25) }}</td>
                        <td class="p-3 whitespace-nowrap">
                            <span class="font-bold {{ $ticket->priority->name === 'Tinggi' ? 'text-red-600' : ($ticket->priority->name === 'Sedang' ? 'text-yellow-600' : 'text-blue-600') }}">
                                {{ $ticket->priority->name }}
                            </span>
                        </td>
                        <td class="p-3 whitespace-nowrap">{{ $ticket->author->name ?? 'N/A' }}</td>
                        <td class="p-3 whitespace-nowrap">{{ $ticket->assignee->name ?? '-' }}</td>
                        <td class="p-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold {{
                                match($ticket->status->name) {
                                    'Open' => 'bg-blue-100 text-blue-800',
                                    'In Progress' => 'bg-yellow-100 text-yellow-800',
                                    'Closed' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                }
                            }}">
                                {{ $ticket->status->name }}
                            </span>
                        </td>
                        <td class="p-3 whitespace-nowrap">{{ $ticket->created_at->format('d M Y') }}</td>
                        <td class="p-3 flex flex-col sm:flex-row gap-2">
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

        {{-- Modal Detail --}}
        <div id="modalDetail" class="hidden space-y-2">
            <h2 class="text-xl font-bold mb-4">Detail Tiket <span id="detailId" class="font-mono"></span></h2>
            <div><strong>Judul:</strong> <span id="detailJudul"></span></div>
            <div><strong>Status:</strong> <span id="detailStatus"></span></div>
            <div><strong>Prioritas:</strong> <span id="detailPrioritas"></span></div>
            <div><strong>Kategori:</strong> <span id="detailKategori"></span></div>
            <div><strong>Pelapor:</strong> <span id="detailPelapor"></span></div>
            <div class="mt-4"><strong>Deskripsi:</strong> <div id="detailDescription" class="mt-1 p-2 bg-gray-50 rounded text-sm text-gray-700 max-h-40 overflow-y-auto"></div></div>
        </div>

        {{-- Modal Edit --}}
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
                        {{-- Opsi status akan di-populate oleh JS --}}
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
            document.getElementById('detailStatus').innerText = ticket.status.name;
            document.getElementById('detailPrioritas').innerText = ticket.priority.name;
            document.getElementById('detailKategori').innerText = ticket.category.name;
            document.getElementById('detailPelapor').innerText = ticket.author.name;
            document.getElementById('detailDescription').innerText = ticket.description;
        } else if (type === 'edit') {
            document.getElementById('modalEdit').classList.remove('hidden');
            document.getElementById('editForm').action = `{{ url('developer/tickets') }}/${ticket.id}`;
            document.getElementById('editIdLabel').innerText = `#${ticket.id}`;
            document.getElementById('editJudul').value = ticket.title; // Pastikan nilai judul diisi saat modal dibuka
                
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