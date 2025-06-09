@extends('layouts.master')

@section('title', 'Tiket Saya - Temprina SITIK')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 my-10">
    
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Tiket Saya</h1>
            <p class="text-gray-500 mt-1">Lihat dan kelola semua tiket yang pernah Anda buat.</p>
        </div>
        <a href="{{ route('user.tickets.create') }}" class="mt-4 sm:mt-0 inline-block bg-black text-white font-semibold py-2 px-5 rounded-lg hover:bg-gray-800 transition">
            + Buat Tiket Baru
        </a>
    </div>

    <!-- Menampilkan pesan sukses -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Daftar Tiket -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID Tiket</th>
                        <th scope="col" class="px-6 py-3">Judul</th>
                        <th scope="col" class="px-6 py-3">Kategori</th>
                        <th scope="col" class="px-6 py-3">Prioritas</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Update Terakhir</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            #{{ $ticket->id }}
                        </th>
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ Str::limit($ticket->title, 40) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $ticket->category->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 font-semibold {{
                            match($ticket->priority->name) {
                                'Tinggi' => 'text-red-600',
                                'Sedang' => 'text-yellow-600',
                                default => 'text-blue-600'
                            }
                        }}">
                            {{ $ticket->priority->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{
                                match($ticket->status->name) {
                                    'Open' => 'bg-blue-100 text-blue-800',
                                    'In Progress' => 'bg-yellow-100 text-yellow-800',
                                    'Closed' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                }
                            }}">
                                {{ $ticket->status->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $ticket->updated_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('user.tickets.show', $ticket->id) }}" class="font-medium text-blue-600 hover:underline">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 px-6">
                            <p class="text-gray-500">Anda belum pernah membuat tiket.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginasi -->
        @if($tickets->hasPages())
        <div class="p-4 border-t">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection