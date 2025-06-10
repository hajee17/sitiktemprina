@extends('layouts.master')

@section('content')
<div class="min-h-screen bg-[#f9f9f9] pb-16">
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
        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-black">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.761 0 5.304.839 7.379 2.271M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </nav>

    <!-- Konten -->
    <div class="max-w-4xl mx-auto mt-12 px-4">
        <h1 class="text-2xl font-bold text-black leading-snug">{{ $knowledge->judul }}</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $knowledge->author }} • {{ \Carbon\Carbon::parse($knowledge->tanggal)->translatedFormat('d F Y • H:i') }}
        </p>

        <div class="mt-6 bg-white shadow-md rounded-lg p-4">
            @if($knowledge->tipe === 'pdf')
                <iframe src="{{ asset('storage/' . $knowledge->file_path) }}" class="w-full h-[600px]" frameborder="0"></iframe>
            @elseif($knowledge->tipe === 'video')
                <div class="aspect-w-16 aspect-h-9">
                    <iframe src="{{ $knowledge->video_url }}" frameborder="0" allowfullscreen class="w-full h-full rounded-lg"></iframe>
                </div>
            @elseif($knowledge->tipe === 'blog')
                <div class="prose max-w-none mt-4">
                    {!! $knowledge->isi !!}
                </div>
            @else
                <p class="text-red-500">Konten tidak tersedia.</p>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black text-white text-center py-6 mt-10 text-sm">
        ©2025 All Rights Reserved by <a href="#" class="underline">PT. Temprina Media Grafika</a>
    </footer>
</div>
@endsection
