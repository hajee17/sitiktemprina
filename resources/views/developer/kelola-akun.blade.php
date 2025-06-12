@extends('layouts.developer')

@section('content')
<div class="w-full min-h-screen bg-[#F5F6FA] flex flex-col">
    <div class="mt-[80px] ml-[280px] px-10 py-6 w-[calc(100%-280px)]">

        <!-- Header -->
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Kelola Akun</h1>

        <!-- Statistik Role -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600">Total Developer</p>
                <p class="text-3xl font-bold text-black">{{ $userCounts['Developer'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600">Total User</p>
                <p class="text-3xl font-bold text-black">{{ $userCounts['User'] }}</p>
            </div>
        </div>

       <!-- Search & Tambah User -->
        <div class="flex justify-between items-center mb-4">
            <form action="{{ route('developer.akun.index') }}" method="GET" class="flex">
                <input type="text" name="search" placeholder="Cari nama/email..." value="{{ request('search') }}"
                    class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">Cari</button>
            </form>
            <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                + Tambah User
            </button>
        </div>

        <!-- Tabel Akun -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden shadow text-left">
                <thead class="bg-black text-white text-sm uppercase">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">ID Akun</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">No. Telp</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Terakhir Login</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($accounts as $index => $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-100" id="user-row-{{ $user->ID_Account }}">
                        <td class="px-6 py-4">{{ $index + $accounts->firstItem() }}</td>
                        <td class="px-6 py-4 font-mono text-gray-500">{{ $user->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            <span class="view-mode">{{ $user->name }}</span>
                            <input type="text" name="name" value="{{ $user->name }}" class="edit-mode hidden w-full px-2 py-1 border rounded">
                        </td>
                        <td class="px-6 py-4">
                            <span class="view-mode">{{ $user->email }}</span>
                            <input type="email" name="email" value="{{ $user->email }}" class="edit-mode hidden w-full px-2 py-1 border rounded">
                        </td>
                        <td class="px-6 py-4">
                            <span class="view-mode">{{ $user->phone ?? '-' }}</span>
                            <input type="text" name="phone" value="{{ $user->phone }}" class="edit-mode hidden w-full px-2 py-1 border rounded">
                        </td>
                        <td class="px-4 py-3">
                            <span class="view-mode">{{ $user->ID_Role == 1 ? 'Developer' : 'User' }}</span>
                            <select name="ID_Role" class="edit-mode hidden w-full px-2 py-1 border rounded">
                                <option value="1" {{ $user->ID_Role == 1 ? 'selected' : '' }}>Developer</option>
                                <option value="2" {{ $user->ID_Role == 2 ? 'selected' : '' }}>User</option>
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->last_login)
                            {{ \Carbon\Carbon::parse($user->last_login)->format('d M Y, H:i') }}
                            @else
                            Belum pernah login
                            @endif
                        </td>
                        <td class="px-4 py-3 flex gap-2 flex-wrap">
                            <button onclick="toggleEditMode({{ $user->ID_Account }}, true)"
                                class="view-mode bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                Edit
                            </button>

                            <div class="edit-mode hidden flex gap-2">
                                <button onclick="saveChanges({{ $user->ID_Account }})"
                                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">Simpan</button>
                                <button onclick="toggleEditMode({{ $user->ID_Account }}, false)"
                                    class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 text-sm">Batal</button>
                            </div>

                            <form action="{{ route('developer.akun.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada akun ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-end">
            {{ $accounts->withQueryString()->links() }}
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div id="modal-tambah" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center @if(!$errors->any()) hidden @endif">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Tambah User Baru</h2>
                <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>
            <form action="{{ route('developer.akun.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="Name" class="block mb-1 font-medium">Nama</label>
                    <input type="text" id="Name" name="Name" value="{{ old('Name') }}" required class="w-full border px-3 py-2 rounded @error('Name') border-red-500 @enderror">
                    {{-- PERBAIKAN: Menampilkan error untuk Nama --}}
                    @error('Name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="Email" class="block mb-1 font-medium">Email</label>
                    <input type="email" id="Email" name="Email" value="{{ old('Email') }}" required class="w-full border px-3 py-2 rounded @error('Email') border-red-500 @enderror">
                     {{-- PERBAIKAN: Menampilkan error untuk Email --}}
                    @error('Email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="Telp_Num" class="block mb-1 font-medium">No. Telp</label>
                    <input type="text" id="Telp_Num" name="Telp_Num" value="{{ old('Telp_Num') }}" class="w-full border px-3 py-2 rounded @error('Telp_Num') border-red-500 @enderror">
                     {{-- PERBAIKAN: Menampilkan error untuk No. Telp --}}
                    @error('Telp_Num')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="ID_Role" class="block mb-1 font-medium">Role</label>
                    <select id="ID_Role" name="ID_Role" class="w-full border px-3 py-2 rounded @error('ID_Role') border-red-500 @enderror">
                        <option value="1" {{ old('ID_Role') == 1 ? 'selected' : '' }}>Developer</option>
                        <option value="2" {{ old('ID_Role') == 2 ? 'selected' : '' }}>User</option>
                    </select>
                     {{-- PERBAIKAN: Menampilkan error untuk Role --}}
                    @error('ID_Role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

        formData.append('Name', row.querySelector('input[name="Name"]').value);
        formData.append('Email', row.querySelector('input[name="Email"]').value);
        formData.append('Telp_Num', row.querySelector('input[name="Telp_Num"]').value);
        formData.append('ID_Role', row.querySelector('select[name="ID_Role"]').value);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        fetch(`/developer/akun/${userId}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toggleEditMode(userId, false);
                const viewSpans = row.querySelectorAll('.view-mode');
                viewSpans[0].textContent = formData.get('Name');
                viewSpans[1].textContent = formData.get('Email');
                viewSpans[2].textContent = formData.get('Telp_Num') || '-';
                viewSpans[3].textContent = formData.get('ID_Role') == 1 ? 'Developer' : 'User';
                alert(data.message || 'Perubahan berhasil disimpan');
            } else {
                alert(data.message || 'Gagal menyimpan perubahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan perubahan');
        });
    }
</script>
@endpush
@endsection
