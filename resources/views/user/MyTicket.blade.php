@extends('layouts.master')

@section('title', 'Tiket Saya')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Pesan Error --}}
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Pesan Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <strong class="font-bold">Terjadi Kesalahan Validasi!</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="text-center mb-10">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Tiket Saya</h1>
        <p class="text-gray-600 mt-2 max-w-2xl mx-auto">Berikut adalah tiket yang telah Anda buat. Kami akan segera menangani setiap permintaan Anda!</p>
    </div>

    @if($tickets->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            @foreach($tickets as $ticket)
                <div class="bg-white rounded-xl shadow-md border overflow-hidden flex items-start p-4 gap-4 transition-shadow duration-300 hover:shadow-lg">
                    @if($ticket->image)
                        {{-- JIKA USER MELAMPIRKAN GAMBAR, maka gambar tersebut yang menjadi thumbnail --}}
                        <img src="{{ Storage::url($ticket->image) }}" alt="Thumbnail Tiket" class="w-24 h-24 object-cover rounded-md flex-shrink-0">
                    @else
                        {{-- JIKA TIDAK ADA GAMBAR, maka gunakan gambar default --}}
                        <img src="{{ asset('images/gambar-tiket.png') }}" alt="Gambar Tiket" class="w-24 h-24 object-cover rounded-md flex-shrink-0">
                    @endif
                    <div class="flex-grow flex flex-col h-full">
                        <div class="flex justify-between items-start">
                             <span class="inline-block bg-blue-100 text-blue-800 font-semibold px-2 py-0.5 rounded-md text-xs">Diverifikasi</span>
                            @php
                                $statusName = optional($ticket->status)->name ?? 'Unknown';
                                $statusColors = [
                                    'Open' => 'bg-blue-100 text-blue-800',
                                    'In Progress' => 'bg-yellow-100 text-yellow-800',
                                    'Closed' => 'bg-green-100 text-green-800',
                                    'On Hold' => 'bg-orange-100 text-orange-800',
                                    'Cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="font-semibold px-2.5 py-1 rounded-full text-xs {{ $statusColors[$statusName] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusName }}
                            </span>
                        </div>
                        
                        <div>
                            <h5 class="text-gray-800 font-bold text-md sm:text-lg mt-1">{{ $ticket->title }}</h5>
                            <p class="text-gray-500 text-xs sm:text-sm">#{{ $ticket->id }}</p>
                        </div>

                        <div class="mt-2 pt-2 border-t text-xs sm:text-sm text-gray-600 space-y-1">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                <span class="font-medium">{{ optional($ticket->category)->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span>{{ $ticket->created_at->format('d M Y') }}</span>
                            </div>
                             <div class="flex items-center">
                               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span>{{ optional($ticket->department)->name ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 flex justify-end gap-2">
                             <a href="{{ route('user.tickets.show', $ticket->id) }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-50">
                                Detail
                            </a>
                            @if(!in_array(optional($ticket->status)->name, ['Closed', 'Cancelled']))
                                <form action="{{ route('user.tickets.cancel', $ticket->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENGHAPUS tiket ini secara permanen? Aksi ini tidak bisa dibatalkan.')">
                                    @csrf
                                    {{-- Input 'Desc' tidak lagi diperlukan karena kita langsung menghapus --}}
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md text-xs font-semibold hover:bg-red-700">
                                        Hapus Tiket
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $tickets->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <img src="{{ asset('images/nothing.png') }}" alt="No tickets" class="mx-auto mb-6" style="max-width: 200px;">
            <p class="text-gray-500 text-lg">Anda belum memiliki tiket aktif saat ini.</p>
            <a href="{{ route('user.tickets.create') }}" class="mt-6 inline-block bg-blue-600 text-white font-bold py-3 px-6 rounded-full hover:bg-blue-700 transition duration-200 shadow-lg hover:shadow-xl">
                Buat Tiket Baru
            </a>
        </div>
    @endif
</div>
@endsection