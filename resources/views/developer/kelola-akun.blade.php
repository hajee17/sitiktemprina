@extends('layouts.developer')

@section('content')
<div class="w-full min-h-screen bg-[#F5F6FA] p-6">

    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Kelola Akun</h1>

    {{-- Pesan Sukses/Error Global --}}
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Total Developer</p>
            <p class="text-3xl font-bold text-black">{{ $userCounts['Developer'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Total User</p>
            <p class="text-3xl font-bold text-black">{{ $userCounts['User'] }}</p>
        </div>
        {{-- Anda bisa menambahkan statistik lain di sini jika ada --}}
    </div>

    <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center mb-4 gap-4">
        <form action="{{ route('developer.akun.index') }}" method="GET" class="flex flex-grow">
            <input type="text" name="search" placeholder="Cari nama/email..." value="{{ request('search') }}"
                class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">Cari</button>
        </form>
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')"
            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 w-full md:w-auto">
            + Tambah User
        </button>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-left">
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
            <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                @forelse($accounts as $index => $user)
                <tr class="hover:bg-gray-100" id="user-row-{{ $user->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $index + $accounts->firstItem() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-500">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                        <span class="view-mode">{{ $user->name }}</span>
                        <input type="text" name="name" value="{{ $user->name }}" class="edit-mode hidden w-full px-2 py-1 border rounded">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="view-mode">{{ $user->email }}</span>
                        <input type="email" name="email" value="{{ $user->email }}" class="edit-mode hidden w-full px-2 py-1 border rounded">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="view-mode">{{ $user->phone ?? '-' }}</span>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="edit-mode hidden w-full px-2 py-1 border rounded">
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        {{-- Tampilkan nama role dari relasi Account->Role --}}
                        <span class="view-mode">{{ optional($user->role)->name ?? 'N/A' }}</span>
                        
                        {{-- Untuk mode edit, tetap gunakan ID role --}}
                        <select name="ID_Role" class="edit-mode hidden w-full px-2 py-1 border rounded">
                            {{-- Asumsi Role Developer memiliki ID 1 dan User memiliki ID 2 --}}
                            <option value="1" {{ optional($user->role)->id == 1 ? 'selected' : '' }}>Developer</option>
                            <option value="2" {{ optional($user->role)->id == 2 ? 'selected' : '' }}>User</option>
                            {{-- Jika Anda memiliki lebih banyak peran, lebih baik loop semua peran dari database: --}}
                            {{-- @foreach(\App\Models\Role::all() as $roleOption)
                                <option value="{{ $roleOption->id }}" {{ optional($user->role)->id == $roleOption->id ? 'selected' : '' }}>{{ $roleOption->name }}</option>
                            @endforeach --}}
                        </select>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($user->last_login)
                        {{ \Carbon\Carbon::parse($user->last_login)->format('d M Y, H:i') }}
                        @else
                        Belum pernah login
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap flex flex-col sm:flex-row gap-2">
                        <button onclick="toggleEditMode({{ $user->id }}, true)"
                            class="view-mode bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm w-full sm:w-auto">
                            Edit
                        </button>
                        <div class="edit-mode hidden flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <button onclick="saveChanges({{ $user->id }})"
                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm w-full sm:w-auto">Simpan</button>
                            <button onclick="toggleEditMode({{ $user->id }}, false)"
                                class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 text-sm w-full sm:w-auto">Batal</button>
                        </div>
                        <form action="{{ route('developer.akun.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus akun ini?')" class="w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm w-full">
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

    <div class="mt-6 flex justify-center sm:justify-end">
        {{ $accounts->withQueryString()->links() }}
    </div>
</div>

<div id="modal-tambah" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center @if(!$errors->any()) hidden @endif">
    <div class="bg-white p-6 rounded-lg w-11/12 max-w-md shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Tambah User Baru</h2>
            <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="text-gray-500 hover:text-gray-800 text-2xl font-bold">&times;</button>
        </div>
        <form action="{{ route('developer.akun.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="Name" class="block mb-1 font-medium">Nama</label>
                <input type="text" id="Name" name="Name" value="{{ old('Name') }}" required class="w-full border px-3 py-2 rounded @error('Name') border-red-500 @enderror">
                @error('Name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="Email" class="block mb-1 font-medium">Email</label>
                <input type="email" id="Email" name="Email" value="{{ old('Email') }}" required class="w-full border px-3 py-2 rounded @error('Email') border-red-500 @enderror">
                @error('Email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="Telp_Num" class="block mb-1 font-medium">No. Telp</label>
                <input type="text" id="Telp_Num" name="Telp_Num" value="{{ old('Telp_Num') }}" class="w-full border px-3 py-2 rounded @error('Telp_Num') border-red-500 @enderror">
                @error('Telp_Num')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- <<<--- FIELD PASSWORD BARU DITAMBAHKAN DI SINI ---}}
            <div class="mb-3">
                <label for="password" class="block mb-1 font-medium">Kata Sandi</label>
                <input type="password" id="password" name="password" class="w-full border px-3 py-2 rounded @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="block mb-1 font-medium">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full border px-3 py-2 rounded">
            </div>
            {{-- <<<--- AKHIR FIELD PASSWORD BARU ---}}
            <div class="mb-3">
                <label for="ID_Role" class="block mb-1 font-medium">Role</label>
                <select id="ID_Role" name="ID_Role" class="w-full border px-3 py-2 rounded @error('ID_Role') border-red-500 @enderror">
                    <option value="1" {{ old('ID_Role') == 1 ? 'selected' : '' }}>Developer</option>
                    <option value="2" {{ old('ID_Role') == 2 ? 'selected' : '' }}>User</option>
                </select>
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

        formData.append('Name', row.querySelector('input[name="name"]').value);
        formData.append('Email', row.querySelector('input[name="email"]').value);
        formData.append('Telp_Num', row.querySelector('input[name="phone"]').value); // Pastikan nama input sesuai dengan yang dikirim ke backend
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
                // Pastikan indeks viewSpans sesuai dengan urutan span di HTML
                viewSpans[0].textContent = formData.get('Name'); // Nama
                viewSpans[1].textContent = formData.get('Email'); // Email
                viewSpans[2].textContent = formData.get('Telp_Num') || '-'; // No. Telp
                viewSpans[3].textContent = formData.get('ID_Role') == 1 ? 'Developer' : 'User'; // Role
                alert(data.message || 'Perubahan berhasil disimpan');
            } else {
                alert(data.message || 'Gagal menyimpan perubahan');
                // Opsional: tampilkan error validasi lebih detail
                if (data.errors) {
                    let errorMessage = '';
                    for (const key in data.errors) {
                        errorMessage += `${key}: ${data.errors[key].join(', ')}\n`;
                    }
                    alert('Validasi Gagal:\n' + errorMessage);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan perubahan');
        });
    }

    // Pastikan modal muncul jika ada error validasi saat reload
    @if($errors->any())
        document.getElementById('modal-tambah').classList.remove('hidden');
    @endif
</script>
@endpush