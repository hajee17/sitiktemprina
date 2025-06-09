@extends('layouts.developer')

@section('content')
<div class="p-6 space-y-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Akun</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Total Developer</p>
            {{-- PENYESUAIAN: Mengambil data dari $userCounts --}}
            <p class="text-3xl font-bold text-black">{{ $userCounts['developer'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Total User</p>
            <p class="text-3xl font-bold text-black">{{ $userCounts['user'] ?? 0 }}</p>
        </div>
    </div>

    <div class="flex justify-between items-center mb-4">
        <form action="{{ route('developer.kelolaAkun') }}" method="GET" class="flex">
            <input type="text" name="search" placeholder="Cari nama/email..." value="{{ request('search') }}" class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">Cari</button>
        </form>
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">+ Tambah Akun</button>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-left">
            <thead class="bg-black text-white text-sm uppercase">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">No. Telp</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Dibuat Pada</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($accounts as $index => $account)
                <tr class="border-b border-gray-200 hover:bg-gray-100" id="user-row-{{ $account->id }}">
                    {{-- PENYESUAIAN: Semua properti disesuaikan dengan Model Eloquent (id, name, email, dll) --}}
                    <td class="px-4 py-3">{{ $index + $accounts->firstItem() }}</td>
                    <td class="px-4 py-3">
                        <span class="view-mode">{{ $account->name }}</span>
                        <input type="text" name="name" value="{{ $account->name }}" class="edit-mode hidden w-full px-2 py-1 border rounded">
                    </td>
                    <td class="px-4 py-3">
                        <span class="view-mode">{{ $account->email }}</span>
                        <input type="email" name="email" value="{{ $account->email }}" class="edit-mode hidden w-full px-2 py-1 border rounded">
                    </td>
                    <td class="px-4 py-3">
                        <span class="view-mode">{{ $account->phone ?? '-' }}</span>
                        <input type="text" name="phone" value="{{ $account->phone }}" class="edit-mode hidden w-full px-2 py-1 border rounded">
                    </td>
                    <td class="px-4 py-3">
                        <span class="view-mode">{{ $account->role->name ?? 'N/A' }}</span>
                        <select name="role_id" class="edit-mode hidden w-full px-2 py-1 border rounded">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $account->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-3">{{ $account->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 flex gap-2 justify-center">
                        <button onclick="toggleEditMode({{ $account->id }}, true)" class="view-mode bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">Edit</button>
                        <div class="edit-mode hidden flex gap-2">
                            <button onclick="saveChanges({{ $account->id }})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">Simpan</button>
                            <button onclick="toggleEditMode({{ $account->id }}, false)" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 text-sm">Batal</button>
                        </div>
                        <form action="{{ route('developer.akun.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-gray-500">Tidak ada akun ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">{{ $accounts->appends(request()->query())->links() }}</div>
</div>

<div id="modal-tambah" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-xl">
        <h2 class="text-xl font-semibold mb-4">Tambah Akun Baru</h2>
        {{-- PENYESUAIAN: Route dan nama input disesuaikan agar standar --}}
        <form action="{{ route('developer.akun.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block mb-1 font-medium text-sm">Nama</label>
                <input type="text" name="name" required class="w-full border-gray-300 px-3 py-2 rounded-lg" value="{{ old('name') }}">
            </div>
            <div>
                <label class="block mb-1 font-medium text-sm">Username</label>
                <input type="text" name="username" required class="w-full border-gray-300 px-3 py-2 rounded-lg" value="{{ old('username') }}">
            </div>
            <div>
                <label class="block mb-1 font-medium text-sm">Email</label>
                <input type="email" name="email" required class="w-full border-gray-300 px-3 py-2 rounded-lg" value="{{ old('email') }}">
            </div>
             <div>
                <label class="block mb-1 font-medium text-sm">No. Telp (Opsional)</label>
                <input type="text" name="phone" class="w-full border-gray-300 px-3 py-2 rounded-lg" value="{{ old('phone') }}">
            </div>
            <div>
                <label class="block mb-1 font-medium text-sm">Role</label>
                <select name="role_id" class="w-full border-gray-300 px-3 py-2 rounded-lg">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium text-sm">Password</label>
                <input type="password" name="password" required class="w-full border-gray-300 px-3 py-2 rounded-lg">
            </div>
             <div>
                <label class="block mb-1 font-medium text-sm">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full border-gray-300 px-3 py-2 rounded-lg">
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Batal</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleEditMode(userId, isEdit) {
    const row = document.getElementById(`user-row-${userId}`);
    row.querySelectorAll('.view-mode').forEach(el => el.classList.toggle('hidden', isEdit));
    row.querySelectorAll('.edit-mode').forEach(el => el.classList.toggle('hidden', !isEdit));
}

function saveChanges(userId) {
    const row = document.getElementById(`user-row-${userId}`);
    const formData = new FormData();

    // PENYESUAIAN: Nama field disamakan dengan standar (lowercase)
    formData.append('name', row.querySelector('input[name="name"]').value);
    formData.append('email', row.querySelector('input[name="email"]').value);
    formData.append('phone', row.querySelector('input[name="phone"]').value);
    formData.append('role_id', row.querySelector('select[name="role_id"]').value);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT'); // Faking PUT method

    fetch(`/developer/accounts/${userId}`, { // PENYESUAIAN: URL API disesuaikan
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update tampilan di tabel setelah berhasil disimpan
            const viewSpans = row.querySelectorAll('.view-mode');
            viewSpans[0].textContent = formData.get('name');
            viewSpans[1].textContent = formData.get('email');
            viewSpans[2].textContent = formData.get('phone') || '-';
            const select = row.querySelector('select[name="role_id"]');
            viewSpans[3].textContent = select.options[select.selectedIndex].text;

            toggleEditMode(userId, false);
            alert(data.message || 'Perubahan berhasil disimpan');
        } else {
            // Handle validation errors or other failures
            let errorMsg = data.message || 'Gagal menyimpan perubahan.';
            if (data.errors) {
                errorMsg += '\n\n' + Object.values(data.errors).map(e => e[0]).join('\n');
            }
            alert(errorMsg);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan koneksi saat menyimpan perubahan.');
    });
}
</script>
@endpush