@extends('layouts.developer')

@section('content')
<div class="w-full h-screen bg-[#F5F6FA] flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center px-10 py-3 bg-white w-[calc(100%-280px)] ml-[280px] fixed top-0 z-50">
        <h1 class="text-xl font-bold">Knowledge Base</h1>
        <div class="flex gap-3">
            <button class="bg-black text-white px-4 py-2 rounded-full font-semibold hover:bg-gray-800">+ Baru</button>
        </div>
    </div>

    <!-- Content -->
    <div class="mt-[80px] ml-[280px] px-10 py-6">
        <!-- Search & Filter -->
        <div class="flex items-center gap-4 mb-6">
            <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Cari ID Tiket, Judul, atau Nama Pelapor">
            <button class="px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800">Filter</button>
        </div>

        <!-- Filter Button Group -->
        <div class="flex gap-3 mb-6">
            @foreach(['Semua', 'Mesin', 'Jaringan', 'Perangkat Lunak', 'Perangkat Keras', 'Data', 'Support Teknis', 'Lainnya'] as $kategori)
                <button class="px-4 py-1 rounded-full border border-gray-400 text-sm font-semibold hover:bg-black hover:text-white">{{ $kategori }}</button>
            @endforeach
        </div>

        <!-- Knowledge Cards -->
        <div class="grid grid-cols-2 gap-4">
            @foreach($knowledgeBases as $kb)
            <div class="bg-white p-4 rounded-xl shadow-sm flex gap-3 items-start justify-between">
                <div class="flex gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                        <!-- Placeholder Icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-[#1F1F1F]">{{ $kb->judul }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $kb->kategori }} · {{ $kb->kode_tiket }}</p>
                        <p class="text-xs text-gray-500">{{ $kb->author }} · {{ \Carbon\Carbon::parse($kb->tanggal)->format('d M Y') }}</p>
                    </div>
                </div>
                <button class="text-gray-500 hover:text-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12h.01M12 12h.01M18 12h.01" />
                    </svg>
                </button>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
