@extends('layouts.developer')

@section('content')
<div class="px-8 py-6 bg-[#F5F6FA] min-h-screen">
    <h1 class="text-2xl font-semibold mb-6">Ambil Tiket</h1>

    {{-- Search dan Filter --}}
    <form action="{{ route('developer.tickets.index') }}" method="GET" class="flex items-center gap-4 mb-6">
        <input 
            type="text" 
            name="search"
            placeholder="Cari ID Tiket, Judul, atau Nama Pelapor"
            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-black"
            value="{{ request('search') }}"
        >
        <button type="submit" class="px-5 py-2 border border-black rounded-md font-medium">Cari</button>
    </form>

    {{-- Filter Kategori --}}
    <div class="flex flex-wrap gap-3 mb-8">
        @foreach (['Semua', 'Bug', 'Request', 'Hardware', 'Network', 'Software'] as $kategori)
            <button 
                class="px-4 py-2 rounded-full border border-black text-sm hover:bg-black hover:text-white transition
                    {{ request('category') == $kategori ? 'bg-black text-white' : '' }}"
                onclick="filterByCategory('{{ $kategori }}')"
            >
                {{ $kategori }}
            </button>
        @endforeach
    </div>

    {{-- List Tiket --}}
    @if($tickets->isEmpty())
        <div class="bg-white p-8 rounded-lg text-center">
            <p class="text-gray-500">Tidak ada tiket yang tersedia</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tickets as $ticket)
                <div class="bg-white border-2 border-[#E4E4E4] rounded-xl p-6 flex flex-col justify-between h-[279px]">
                    {{-- Badge Prioritas --}}
                    <span class="text-sm font-semibold px-3 py-1 rounded-full w-fit
                        @if($ticket->priority == 'Tinggi') bg-[#FF3B30] text-white
                        @elseif($ticket->priority == 'Sedang') bg-[#FF9500] text-white
                        @elseif($ticket->priority == 'Rendah') bg-[#34C759] text-white
                        @endif">
                        {{ $ticket->priority }}
                    </span>

                    {{-- ID dan Judul Tiket --}}
                    <div>
                        <p class="text-xs text-gray-500 mt-1">{{ $ticket->ID_Ticket }}</p>
                        <h3 class="mt-1 font-bold text-lg leading-tight">{{ $ticket->Judul_Tiket }}</h3>
                    </div>

                    {{-- Detail --}}
                    <div class="text-sm text-[#4A4A4A] mt-2 space-y-1">
                        <p><strong>Kategori:</strong> {{ $ticket->Category }}</p>
                        <p><strong>Pelapor:</strong> {{ $ticket->account->Name ?? 'N/A' }} ({{ $ticket->Position }})</p>
                        <p><strong>Lokasi:</strong> {{ $ticket->Location }}</p>
                        <p><strong>Dibuat:</strong> 
                            @if($ticket->created_at)
                                {{ $ticket->created_at->format('d M Y H:i') }}
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>Status:</strong> {{ $ticket->status->Status ?? 'Baru' }}</p>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex gap-2 mt-4">
                        <form action="{{ route('tickets.take', $ticket->ID_Ticket) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-black text-white px-4 py-2 rounded-md text-sm hover:opacity-90
                                           @if($ticket->currentStatus !== 'Baru') opacity-50 cursor-not-allowed @endif"
                                    @if($ticket->currentStatus !== 'Baru') disabled @endif>
                                @if($ticket->currentStatus === 'Diproses')
                                    Sudah Diambil
                                @else
                                    Ambil Tiket
                                @endif
                            </button>
                        </form>
                        <a href="{{ route('tickets.show', $ticket->ID_Ticket) }}" 
                           class="border border-black text-black px-4 py-2 rounded-md text-sm hover:bg-gray-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function filterByCategory(category) {
        const url = new URL(window.location.href);
        url.searchParams.set('category', category === 'Semua' ? '' : category);
        window.location.href = url.toString();
    }
</script>
@endsection