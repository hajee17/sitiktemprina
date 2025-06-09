@extends('layouts.developer')

@section('content')
<div class="px-8 py-6 bg-[#F5F6FA] min-h-screen">
    <h1 class="text-2xl font-semibold mb-6">Ambil Tiket</h1>

    {{-- Form Pencarian dan Filter --}}
    <form action="{{ route('developer.allTickets') }}" method="GET" class="flex items-center gap-4 mb-6">
        <input 
            type="text" 
            name="search"
            placeholder="Cari ID Tiket, Judul, atau Nama Pelapor"
            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-black"
            value="{{ request('search') }}"
        >
        <button type="submit" class="px-5 py-2 border border-black bg-black text-white rounded-md font-medium hover:opacity-90">Cari</button>
    </form>

    {{-- Tombol Filter Kategori (Dinamis) --}}
    <div class="flex flex-wrap gap-3 mb-8">
        <button 
            class="px-4 py-2 rounded-full border border-black text-sm hover:bg-black hover:text-white transition
                   {{ !request('category') || request('category') == 'Semua' ? 'bg-black text-white' : '' }}"
            onclick="filterByCategory('Semua')"
        >
            Semua
        </button>
        {{-- Tombol filter ini akan dibuat secara dinamis jika $categories ada --}}
        @if(isset($categories))
            @foreach ($categories as $category)
                <button 
                    class="px-4 py-2 rounded-full border border-black text-sm hover:bg-black hover:text-white transition
                           {{ request('category') == $category->name ? 'bg-black text-white' : '' }}"
                    onclick="filterByCategory('{{ $category->name }}')"
                >
                    {{ $category->name }}
                </button>
            @endforeach
        @endif
    </div>

    {{-- Daftar Kartu Tiket --}}
    @if(isset($tickets) && $tickets->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tickets as $ticket)
                <div class="bg-white border-2 border-[#E4E4E4] rounded-xl p-6 flex flex-col justify-between min-h-[280px] shadow-sm">
                    <div>
                        {{-- Badge Prioritas --}}
                        <span class="text-sm font-semibold px-3 py-1 rounded-full w-fit
                            @if($ticket->priority->name == 'Tinggi') bg-red-500 text-white
                            @elseif($ticket->priority->name == 'Sedang') bg-yellow-500 text-white
                            @else bg-green-500 text-white
                            @endif">
                            {{ $ticket->priority->name }}
                        </span>

                        {{-- Judul dan ID Tiket --}}
                        <div class="mt-3">
                            <p class="text-xs text-gray-500">#{{ $ticket->id }}</p>
                            <h3 class="mt-1 font-bold text-lg leading-tight">{{ $ticket->title }}</h3>
                        </div>

                        {{-- Detail Informasi Tiket --}}
                        <div class="text-sm text-[#4A4A4A] mt-4 space-y-1">
                            <p><strong>Kategori:</strong> {{ $ticket->category->name ?? 'N/A' }}</p>
                            <p><strong>Pelapor:</strong> {{ $ticket->author->name ?? 'N/A' }} ({{ $ticket->author->position->name ?? 'N/A' }})</p>
                            <p><strong>Lokasi:</strong> {{ $ticket->sbu->name ?? 'N/A' }}</p>
                            <p><strong>Dibuat:</strong> {{ $ticket->created_at->format('d M Y, H:i') }}</p>
                            <p><strong>Status:</strong> {{ $ticket->status->name ?? 'Baru' }}</p>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-2 mt-4">
                        <form action="{{ route('developer.tickets.assign', $ticket->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-black text-white px-4 py-2 rounded-md text-sm font-medium hover:opacity-90">
                                Ambil Tiket
                            </button>
                        </form>
                        <a href="{{ route('developer.tickets.show', $ticket->id) }}" 
                           class="border border-black text-black px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Link Pagination --}}
        <div class="mt-8">
            {{-- Menggunakan appends() untuk Laravel 7 ke bawah agar filter tetap ada saat ganti halaman --}}
            {{ $tickets->appends(request()->query())->links() }}
        </div>
    @else
        {{-- Pesan jika tidak ada tiket --}}
        <div class="bg-white p-8 rounded-lg text-center shadow">
            <p class="text-gray-500">Tidak ada tiket baru yang tersedia sesuai kriteria Anda.</p>
        </div>
    @endif
</div>

{{-- Script untuk fungsi filter, tidak perlu diubah --}}
<script>
    function filterByCategory(category) {
        const url = new URL(window.location.href);
        // Hapus parameter 'page' agar kembali ke halaman 1 saat filter diubah
        url.searchParams.delete('page'); 
        
        if (category === 'Semua') {
            url.searchParams.delete('category');
        } else {
            url.searchParams.set('category', category);
        }
        window.location.href = url.toString();
    }
</script>
@endsection