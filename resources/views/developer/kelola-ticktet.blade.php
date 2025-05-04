@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-xl font-bold mb-4">Kelola Tiket</h1>

    <!-- Statistik -->
    <div class="grid grid-cols-5 gap-4 mb-6">
        @foreach ($stats as $label => $value)
        <div class="bg-white shadow rounded p-4 text-center">
            <div class="text-sm font-semibold uppercase">{{ ucwords(str_replace('_', ' ', $label)) }}</div>
            <div class="text-2xl font-bold">{{ number_format($value) }}</div>
        </div>
        @endforeach
    </div>

    <!-- Tabel Tiket -->
    <div class="bg-white shadow rounded">
        <table class="w-full table-auto text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-3">ID Tiket</th>
                    <th>Prioritas</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Pelapor</th>
                    <th>Tanggal Dibuat</th>
                    <th>Status</th>
                    <th>Developer</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                <tr class="border-t">
                    <td class="p-3">{{ $ticket->id }}</td>
                    <td><span class="text-red-600 font-bold">{{ $ticket->prioritas }}</span></td>
                    <td>{{ $ticket->judul }}</td>
                    <td>{{ $ticket->kategori }}</td>
                    <td>{{ $ticket->pelapor }}</td>
                    <td>{{ $ticket->created_at->format('j M Y, H:i') }}</td>
                    <td><span class="bg-yellow-200 px-2 py-1 rounded">{{ $ticket->status }}</span></td>
                    <td>{{ $ticket->developer }}</td>
                    <td class="space-x-2">
                        <button onclick="openModal('detail', {{ $ticket }})">🔍</button>
                        <button onclick="openModal('edit', {{ $ticket }})">✏️</button>
                        <form action="{{ route('developer.tickets.destroy', $ticket->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus tiket ini?')">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $tickets->links() }}</div>
    </div>
</div>

<!-- Modal -->
<div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-[500px] p-6 relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-lg">✖️</button>

        <div id="modalDetail" class="hidden">
            <h2 class="text-lg font-bold mb-2">Detail Tiket</h2>
            <div><strong>ID:</strong> <span id="detailId"></span></div>
            <div><strong>Judul:</strong> <span id="detailJudul"></span></div>
            <div><strong>Status:</strong> <span id="detailStatus"></span></div>
            <!-- Tambahkan elemen lain sesuai kebutuhan -->
        </div>

        <div id="modalEdit" class="hidden">
            <h2 class="text-lg font-bold mb-2">Edit Tiket</h2>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="id" id="editId">
                <div class="mb-2">
                    <label>Judul</label>
                    <input type="text" name="judul" id="editJudul" class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-2">
                    <label>Status</label>
                    <select name="status" id="editStatus" class="w-full border rounded px-2 py-1">
                        <option>Baru</option>
                        <option>Diproses</option>
                        <option>Selesai</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(type, ticket) {
        const modal = document.getElementById('ticketModal');
        const modalDetail = document.getElementById('modalDetail');
        const modalEdit = document.getElementById('modalEdit');
        modal.classList.remove('hidden');
        modalDetail.classList.add('hidden');
        modalEdit.classList.add('hidden');

        if (type === 'detail') {
            modalDetail.classList.remove('hidden');
            document.getElementById('detailId').innerText = ticket.id;
            document.getElementById('detailJudul').innerText = ticket.judul;
            document.getElementById('detailStatus').innerText = ticket.status;
        } else if (type === 'edit') {
            modalEdit.classList.remove('hidden');
            document.getElementById('editForm').action = `/developer/kelola-tiket/${ticket.id}`;
            document.getElementById('editJudul').value = ticket.judul;
            document.getElementById('editStatus').value = ticket.status;
        }
    }

    function closeModal() {
        document.getElementById('ticketModal').classList.add('hidden');
    }
</script>
@endsection
