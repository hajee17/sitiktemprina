{{--  @extends('layouts.developer')

@section('content')
<div class="px-8 py-6 bg-[#F5F6FA] min-h-screen">
    <h1 class="text-2xl font-semibold mb-6">Ambil Tiket</h1>

    {{-- Search dan Filter -}}
    <div class="flex items-center gap-4 mb-6">
        <input type="text" placeholder="Cari ID Tiket, Judul, atau Nama Pelapor"
            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-black">
        <button class="px-5 py-2 border border-black rounded-md font-medium">Filter</button>
    </div>

    {{-- Filter Kategori -}}
    <div class="flex flex-wrap gap-3 mb-8">
        @foreach (['Semua', 'Mesin', 'Jaringan', 'Perangkat Lunak', 'Perangkat Keras', 'Data', 'Support Teknis', 'Lainnya'] as $kategori)
            <button class="px-4 py-2 rounded-full border border-black text-sm hover:bg-black hover:text-white transition">
                {{ $kategori }}
            </button>
        @endforeach
    </div>

    {{-- List Tiket -}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($tickets as $ticket)
            <div class="bg-white border-2 border-[#E4E4E4] rounded-xl p-6 flex flex-col justify-between h-[279px]">
                {{-- Badge Prioritas -}}
                <span class="text-sm font-semibold px-3 py-1 rounded-full w-fit
                    @if($ticket->priority == 'Tinggi') bg-[#FF3B30] text-white
                    @elseif($ticket->priority == 'Sedang') bg-[#FF9500] text-white
                    @elseif($ticket->priority == 'Rendah') bg-[#34C759] text-white
                    @endif">
                    {{ $ticket->priority }}
                </span>

                {{-- Judul -}}
                <h3 class="mt-2 font-bold text-lg leading-tight">{{ $ticket->title }}</h3>

                {{-- Detail -}}
                <div class="text-sm text-[#4A4A4A] mt-2 space-y-1">
                    <p>{{ $ticket->category }}</p>
                    <p>{{ $ticket->reporter }} – {{ $ticket->position }}</p>
                    <p>{{ \Carbon\Carbon::parse($ticket->reported_at)->format('j M Y – H:i') }}</p>
                    <p>{{ $ticket->location }}</p>
                </div>

                {{-- Tombol -}}
                <div class="flex gap-2 mt-4">
                    <a href="{{ route('tickets.take', $ticket->id) }}" class="bg-black text-white px-4 py-2 rounded-md text-sm hover:opacity-90">Ambil Tiket</a>
                    <a href="{{ route('tickets.show', $ticket->id) }}" class="border border-black text-black px-4 py-2 rounded-md text-sm hover:bg-gray-100">Lihat Detail</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
--}}
@extends('layouts.developer')

@section('content')
<div class="bg-[#F5F6FA] min-h-screen p-10">
    <h1 class="text-2xl font-semibold mb-6">Ambil Tiket</h1>

    {{-- Search dan Filter --}}
    <div class="flex items-center gap-4 mb-6">
        <input type="text" placeholder="Cari ID Tiket, Judul, atau Nama Pelapor"
            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-black">
        <button class="px-5 py-2 border border-black rounded-md font-medium">Filter</button>
    </div>

    {{-- Filter Kategori --}}
    <div class="flex flex-wrap gap-3 mb-8">
        @foreach (['Semua', 'Mesin', 'Jaringan', 'Perangkat Lunak', 'Perangkat Keras', 'Data', 'Support Teknis', 'Lainnya'] as $kategori)
            <button class="px-4 py-2 rounded-full border border-black text-sm hover:bg-black hover:text-white transition">
                {{ $kategori }}
            </button>
        @endforeach
    </div>

    {{-- Grid Tiket --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Tiket 1 --}}
        <div class="bg-white border-2 border-[#E4E4E4] rounded-xl p-6">
            <span class="text-sm font-semibold px-3 py-1 rounded-full bg-[#FF3B30] text-white">Tinggi</span>
            <h3 class="mt-3 text-lg font-bold">Mesin cetak tidak merespons perintah</h3>
            <div class="text-sm text-gray-600 mt-2 space-y-1">
                <p>Mesin</p>
                <p>Andi Saputra - Operator Mesin</p>
                <p>6 Apr 2025 - 08:15</p>
                <p>Lantai Produksi - Mesin Heidelberg 01</p>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="bg-black text-white px-4 py-2 rounded-md text-sm">Ambil Tiket</button>
                <button class="border border-black text-black px-4 py-2 rounded-md text-sm">Lihat Detail</button>
            </div>
        </div>

        {{-- Tiket 2 --}}
        <div class="bg-white border-2 border-[#E4E4E4] rounded-xl p-6">
            <span class="text-sm font-semibold px-3 py-1 rounded-full bg-[#FF3B30] text-white">Tinggi</span>
            <h3 class="mt-3 text-lg font-bold">Data laporan produksi hilang</h3>
            <div class="text-sm text-gray-600 mt-2 space-y-1">
                <p>Data</p>
                <p>Ardi Firmansyah - Supervisor Produksi</p>
                <p>3 Apr 2025 - 16:50</p>
                <p>Ruang Supervisor</p>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="bg-black text-white px-4 py-2 rounded-md text-sm">Ambil Tiket</button>
                <button class="border border-black text-black px-4 py-2 rounded-md text-sm">Lihat Detail</button>
            </div>
        </div>

        {{-- Tiket 3 --}}
        <div class="bg-white border-2 border-[#E4E4E4] rounded-xl p-6">
            <span class="text-sm font-semibold px-3 py-1 rounded-full bg-[#FF3B30] text-white">Tinggi</span>
            <h3 class="mt-3 text-lg font-bold">Tidak bisa akses internet</h3>
            <div class="text-sm text-gray-600 mt-2 space-y-1">
                <p>Jaringan</p>
                <p>Dinda Ayu – Staf Administrasi</p>
                <p>2 Apr 2025 – 11:12</p>
                <p>Lantai 2 – Ruang Keuangan</p>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="bg-black text-white px-4 py-2 rounded-md text-sm">Ambil Tiket</button>
                <button class="border border-black text-black px-4 py-2 rounded-md text-sm">Lihat Detail</button>
            </div>
        </div>

        {{-- Tiket 4 --}}
        <div class="bg-white border-2 border-[#E4E4E4] rounded-xl p-6">
            <span class="text-sm font-semibold px-3 py-1 rounded-full bg-[#FF3B30] text-white">Tinggi</span>
            <h3 class="mt-3 text-lg font-bold">Printer error dan kertas nyangkut</h3>
            <div class="text-sm text-gray-600 mt-2 space-y-1">
                <p>Perangkat Keras</p>
                <p>Yulianto - Kepala TU</p>
                <p>29 Mar 2025 - 09:33</p>
                <p>Lantai 1 - TU</p>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="bg-black text-white px-4 py-2 rounded-md text-sm">Ambil Tiket</button>
                <button class="border border-black text-black px-4 py-2 rounded-md text-sm">Lihat Detail</button>
            </div>
        </div>

        {{-- Tiket 5 --}}
        <div class="bg-white border-2 border-[#E4E4E4] rounded-xl p-6">
            <span class="text-sm font-semibold px-3 py-1 rounded-full bg-[#FF9500] text-white">Sedang</span>
            <h3 class="mt-3 text-lg font-bold">Monitor mesin mengalami blank screen</h3>
            <div class="text-sm text-gray-600 mt-2 space-y-1">
                <p>Perangkat Keras</p>
                <p>Lina Marlina - Quality Control</p>
                <p>5 Apr 2025 - 15:20</p>
                <p>Area QC - Mesin Offset</p>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="bg-black text-white px-4 py-2 rounded-md text-sm">Ambil Tiket</button>
                <button class="border border-black text-black px-4 py-2 rounded-md text-sm">Lihat Detail</button>
            </div>
        </div>

        {{-- Tiket 6 --}}
        <div class="bg-white border-2 border-[#E4E4E4] rounded-xl p-6">
            <span class="text-sm font-semibold px-3 py-1 rounded-full bg-[#34C759] text-white">Rendah</span>
            <h3 class="mt-3 text-lg font-bold">Mouse tidak berfungsi dengan baik</h3>
            <div class="text-sm text-gray-600 mt-2 space-y-1">
                <p>Perangkat Keras</p>
                <p>Sari Melati - Admin Produksi</p>
                <p>3 Apr 2025 - 09:30</p>
                <p>Ruang Produksi - PC A07</p>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="bg-black text-white px-4 py-2 rounded-md text-sm">Ambil Tiket</button>
                <button class="border border-black text-black px-4 py-2 rounded-md text-sm">Lihat Detail</button>
            </div>
        </div>
    </div>
</div>
@endsection
