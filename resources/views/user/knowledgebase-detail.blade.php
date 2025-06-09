@extends('layouts.master')

@section('title', ($knowledge->title ?? 'Detail Artikel') . ' - Temprina SITIK')

@section('content')
<div class="min-h-screen bg-[#f9f9f9] pb-16">
    {{-- PENYESUAIAN: Header ini sebaiknya ada di layouts.master, 
         namun saya biarkan di sini sesuai kode asli Anda.
         Tautan navigasi telah diperbaiki. --}}
    <header class="w-full flex justify-between items-center px-10 py-5 bg-black text-white">
        <a href="{{ route('dashboard') }}" class="flex gap-1 items-center">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="h-8">
            <span class="text-xl font-bold">Temprina Sitik</span>
        </a>
        <ul class="flex gap-8 text-sm font-semibold">
            <li><a href="{{ route('dashboard') }}#kategori" class="hover:underline">Kategori</a></li>
            <li><a href="{{ route('user.tickets.index') }}" class="hover:underline">Tiket Saya</a></li>
            <li><a href="{{ route('faq') }}" class="hover:underline">FAQ</a></li>
            <li><a href="{{ route('kb.index') }}" class="hover:underline">Knowledge Base</a></li>
        </ul>
        <a href="{{ route('my.account') }}" class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-black">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.761 0 5.304.839 7.379 2.271M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </a>
    </header>

    <div class="max-w-4xl mx-auto mt-12 px-4">
        {{-- PENYESUAIAN: Semua properti disesuaikan dengan Model Eloquent --}}
        <h1 class="text-2xl font-bold text-black leading-snug">{{ $knowledge->title }}</h1>
        <p class="text-sm text-gray-500 mt-1">
            Oleh {{ $knowledge->author->name }} • {{ $knowledge->created_at->translatedFormat('d F Y • H:i') }}
        </p>

        <div class="mt-6 bg-white shadow-md rounded-lg p-4">
            {{-- PENYESUAIAN: Logika tipe konten dan sumber data --}}
            @if($knowledge->type === 'pdf')
                {{-- Untuk PDF, 'content' berisi path ke file di storage --}}
                <iframe src="{{ Storage::url($knowledge->content) }}" class="w-full h-[600px]" frameborder="0"></iframe>
            @elseif($knowledge->type === 'video')
                {{-- Untuk video, 'content' berisi URL embed (misal: dari YouTube) --}}
                <div class="aspect-w-16 aspect-h-9">
                    <iframe src="{{ $knowledge->content }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full rounded-lg"></iframe>
                </div>
            @elseif($knowledge->type === 'blog')
                {{-- Untuk blog, 'content' berisi teks artikel --}}
                <div class="prose max-w-none mt-4">
                    {!! $knowledge->content !!} {{-- Menggunakan {!! !!} jika konten berisi HTML --}}
                </div>
            @else
                <p class="text-red-500">Tipe konten tidak didukung atau tidak tersedia.</p>
            @endif
        </div>
    </div>
</div>
{{-- Footer sebaiknya menjadi bagian dari layouts.master --}}
@endsection