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
                <p class="font-bold text-sm text-[#404040]">Jone Aly</p>
                <p class="text-xs font-medium text-[#565656]">Admin</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="mt-[80px] ml-[280px] px-10 py-6 w-[calc(100%-280px)] overflow-auto">
        <h1 class="text-2xl font-semibold text-[#1F1F1F] mb-6">Kelola Akun</h1>

        <!-- Cards for Akun User / Developer -->
        <div class="flex gap-6 mb-6">
            <div class="bg-white rounded-xl p-6 shadow border border-gray-200">
                <p class="text-gray-500 text-sm">Akun User</p>
                <p class="font-bold text-2xl tracking-wide">999.999.999</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow border border-gray-200">
                <p class="text-gray-500 text-sm">Akun Developer</p>
                <p class="font-bold text-2xl tracking-wide">999.999.999</p>
            </div>
        </div>

        <!-- Search bar -->
        <input type="text" placeholder="Cari data akun..." class="w-full mb-4 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white text-left border border-gray-300 rounded-lg">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">ID Akun</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Terakhir Login</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr class="border-b border-gray-200">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $user->account_id }}</td>
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->role }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($user->last_login)->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('account.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700">
                                ✏️
                            </a>
                            <form action="{{ route('account.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-end mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
