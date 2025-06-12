@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold">Tiket Saya</h1>
        <p class="text-gray-600 mt-2">Berikut adalah tiket yang telah Anda buat. Kami akan segera menangani setiap permintaan Anda!</p>
    </div>

    @if($tickets->isNotEmpty())
        <div class="space-y-4">
            @foreach($tickets as $ticket)
                <div class="bg-white rounded-xl shadow-md border overflow-hidden">
                    <div class="p-4 flex flex-col md:flex-row gap-4">
                        {{-- Gambar bisa disesuaikan berdasarkan kategori tiket --}}
                        @php
                            $imagePath = 'images/';
                            switch(optional($ticket->category)->name) {
                                case 'Jaringan': $imagePath .= 'router.jpg'; break;
                                case 'Perangkat Keras': $imagePath .= 'hardware.jpg'; break;
                                case 'Perangkat Lunak': $imagePath .= 'software.jpg'; break;
                                default: $imagePath .= 'default.jpg';
                            }
                        @endphp
                        <img src="{{ asset($imagePath) }}" alt="Gambar Tiket" class="w-full md:w-32 h-32 object-cover rounded-lg flex-shrink-0">
                        
                        <div class="flex-grow">
                            {{-- PERBAIKAN: Menggunakan properti 'id' dan 'title' --}}
                            <h5 class="text-blue-600 font-bold text-lg">#{{ $ticket->id }} - {{ $ticket->title }}</h5>
                            <div class="text-sm text-gray-600 mt-2 grid grid-cols-2 gap-x-4 gap-y-1">
                                {{-- PERBAIKAN: Menggunakan created_at object dan relasi yang benar --}}
                                <p><strong>Tanggal Dibuat:</strong> {{ $ticket->created_at->format('d M Y') }}</p>
                                <p><strong>Kategori:</strong> {{ optional($ticket->category)->name ?? 'N/A' }}</p>
                                <p><strong>Lokasi:</strong> {{ optional($ticket->department)->name ?? 'N/A' }}</p>
                                <p><strong>Prioritas:</strong> {{ optional($ticket->priority)->name ?? 'N/A' }}</p>
                            </div>
                            {{-- PERBAIKAN: Menggunakan 'description' --}}
                            <p class="text-sm text-gray-500 mt-3">{{ Str::limit($ticket->description, 120, '...') }}</p>
                        </div>

                        <div class="flex-shrink-0 text-center">
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
                            {{-- PERBAIKAN: Menggunakan optional($ticket->status)->name sebagai kunci array --}}
                            <span class="inline-block font-semibold px-3 py-1 rounded-full text-xs {{ $statusColors[$statusName] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusName }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Tombol aksi -->
                    <div class="card-footer bg-gray-50 p-3 flex justify-end gap-2">
                        {{-- PERBAIKAN: Menggunakan rute yang benar --}}
                        <a href="{{ route('user.tickets.show', $ticket->id) }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50">
                            Detail
                        </a>
                        @if(!in_array(optional($ticket->status)->name, ['Closed', 'Cancelled']))
                            <form action="{{ route('user.tickets.cancel', $ticket->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan tiket ini?')">
                                @csrf
                                <input type="hidden" name="Desc" value="Dibatalkan oleh pengguna.">
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md text-sm hover:bg-red-600">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    @else
        <div class="text-center py-10">
            <img src="{{ asset('images/empty-ticket.png') }}" alt="No tickets" class="mx-auto mb-4" style="max-width: 200px;">
            <p class="text-gray-500 text-lg">Belum ada tiket yang Anda buat.</p>
            <a href="{{ route('user.tickets.create') }}" class="mt-4 inline-block bg-blue-600 text-white font-bold py-3 px-6 rounded-full hover:bg-blue-700">
                Buat Tiket Baru
            </a>
        </div>
    @endif
</div>
@endsection
