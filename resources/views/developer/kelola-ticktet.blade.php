@extends('layouts.developer')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Kelola Semua Tiket</h1>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach ($stats as $label => $value)
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <div class="text-sm font-semibold uppercase text-gray-500">{{ ucwords(str_replace('_', ' ', $label)) }}</div>
            <div class="text-3xl font-bold">{{ number_format($value) }}</div>
        </div>
        @endforeach
    </div>

    <!-- Tabel Tiket -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto text-sm">
                <thead class="bg-gray-100 text-left text-gray-600">
                    <tr>
                        <th class="p-3 font-semibold">ID</th>
                        <th class="font-semibold">Judul</th>
                        <th class="font-semibold">Prioritas</th>
                        <th class="font-semibold">Pelapor</th>
                        <th class="font-semibold">Developer</th>
                        <th class="font-semibold">Status</th>
                        <th class="font-semibold">Dibuat</th>
                        <th class="font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                    <tr class="border-t hover:bg-gray-50">
                        {{-- PENYESUAIAN: Semua properti disesuaikan --}}
                        <td class="p-3">#{{ $ticket->id }}</td>
                        <td>{{ Str::limit($ticket->title, 25) }}</td>
                        <td>
                            <span class="font-bold {{ $ticket->priority->name === 'Tinggi' ? 'text-red-600' : ($ticket->priority->name === 'Sedang' ? 'text-yellow-600' : 'text-blue-600') }}">
                                {{ $ticket->priority->name }}
                            </span>
                        </td>
                        <td>{{ $ticket->author->name ?? 'N/A' }}</td>
                        <td>{{ $ticket->assignee->name ?? '-' }}</td>
                        <td>
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
                        <td>{{ $ticket->created_at->format('d M Y') }}</td>
                        <td class="p-3 flex gap-2">
                             {{-- PENYESUAIAN: Mengirim relasi ke modal --}}
                            <button onclick="openModal('detail', {{ json_encode($ticket->load(['priority', 'status', 'category', 'author'])) }})">🔍</button>
                            <button onclick="openModal('edit', {{ json_encode($ticket->load('status')) }})">✏️</button>
                            <form action="{{ route('developer.tickets.destroy', $ticket->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus tiket ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $tickets->links() }}</div>
    </div>
</div>

<!-- Modal -->
<div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-lg p-6 relative">
        <button onclick="closeModal()" class="absolute top-3 right-3 text-2xl text-gray-500 hover:text-gray-800">&times;</button>
        
        {{-- Modal Detail --}}
        <div id="modalDetail" class="hidden space-y-2">
            <h2 class="text-lg font-bold mb-2">Detail Tiket <span id="detailId" class="font-mono"></span></h2>
            <div><strong>Judul:</strong> <span id="detailJudul"></span></div>
            <div><strong>Status:</strong> <span id="detailStatus"></span></div>
            <div><strong>Prioritas:</strong> <span id="detailPrioritas"></span></div>
            <div><strong>Kategori:</strong> <span id="detailKategori"></span></div>
            <div><strong>Pelapor:</strong> <span id="detailPelapor"></span></div>
        </div>

        {{-- Modal Edit --}}
        <div id="modalEdit" class="hidden">
            <h2 class="text-lg font-bold mb-4">Edit Tiket <span id="editIdLabel" class="font-mono"></span></h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="font-semibold">Judul</label>
                    <input type="text" name="title" id="editJudul" class="w-full border rounded px-2 py-1 mt-1">
                </div>
                <div class="mb-4">
                    <label class="font-semibold">Status</label>
                    <select name="status_id" id="editStatus" class="w-full border rounded px-2 py-1 mt-1">
                        {{-- Opsi status akan di-populate oleh JS --}}
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg w-full">Simpan Perubahan</button>
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
        } else if (type === 'edit') {
            document.getElementById('modalEdit').classList.remove('hidden');
            document.getElementById('editForm').action = `{{ url('developer/tickets') }}/${ticket.id}`;
            document.getElementById('editIdLabel').innerText = `#${ticket.id}`;
            document.getElementById('editJudul').value = ticket.title;

            const statusSelect = document.getElementById('editStatus');
            statusSelect.innerHTML = ''; // Kosongkan opsi sebelumnya
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