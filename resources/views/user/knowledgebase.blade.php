@extends('layouts.master')

@section('content')
<div class="min-h-screen bg-white flex flex-col">
    <!-- Navbar -->
    <nav class="w-full flex justify-between items-center px-10 py-5 bg-black text-white">
        <div class="flex gap-1 items-center">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-6"> 
            <span class="text-xl font-bold">Temprina Sitik</span>
        </div>
        <ul class="flex gap-8 text-sm font-semibold">
            <li><a href="#" class="hover:underline">Kategori</a></li>
            <li><a href="#" class="hover:underline">Tiket Saya</a></li>
            <li><a href="#" class="hover:underline">FAQ</a></li>
            <li><a href="#" class="hover:underline">Knowledge Base</a></li>
        </ul>
        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white text-black font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.761 0 5.304.839 7.379 2.271M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </nav>

    <!-- Header -->
    <div class="text-center mt-12 px-6">
        <h1 class="text-3xl font-bold mb-2">Knowledge Base</h1>
        <p class="text-gray-600 max-w-3xl mx-auto">Dokumentasi lengkap seluruh penanganan kasus, permasalahan teknis, serta solusi yang telah diterapkan. Dirancang sebagai referensi bersama agar penanganan ke depan lebih cepat, efisien, dan konsisten.</p>
    </div>

    <!-- Search + Filter -->
    <div class="flex flex-col items-center mt-8 gap-4">
        <div class="flex gap-2 w-full max-w-2xl">
            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none" placeholder="Cari pertanyaan atau kata kunci...">
            <button class="px-6 py-2 bg-black text-white rounded-full">Cari</button>
            <button class="px-6 py-2 border border-black rounded-full">Filter</button>
        </div>
        
        <!-- Kategori -->
        <div class="flex flex-wrap justify-center gap-3">
            @foreach(['Semua', 'Mesin', 'Jaringan', 'Perangkat Lunak', 'Perangkat Keras', 'Data', 'Support Teknis', 'Lainnya'] as $kategori)
                <button class="px-4 py-1 rounded-full border border-gray-400 text-sm font-semibold hover:bg-black hover:text-white">{{ $kategori }}</button>
            @endforeach
        </div>
    </div>

    <!-- Artikel -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-10 px-10 mb-16">
        @foreach($knowledgeBases as $kb)
        <div class="bg-[#F8F8F8] p-5 rounded-xl border border-gray-200 flex gap-3">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-sm text-black">{{ $kb->judul }}</p>
                <p class="text-xs text-gray-600 mt-1">{{ $kb->kategori }} · {{ $kb->kode_tiket }}</p>
                <p class="text-xs text-gray-500">{{ $kb->author }} · {{ \Carbon\Carbon::parse($kb->tanggal)->format('d M Y') }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Footer -->
    <footer class="bg-black text-white text-center py-6 text-sm">
        ©2025 All Rights Reserved by <a href="#" class="underline">PT. Temprina Media Grafika</a>
    </footer>
</div>
@endsection
