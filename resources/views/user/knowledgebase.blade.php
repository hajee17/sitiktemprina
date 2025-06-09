@extends('layouts.master')

@section('title', 'Knowledge Base - Temprina SITIK')

@section('content')
<div class="min-h-screen bg-white flex flex-col">
    {{-- PENYESUAIAN: Navbar ini sebaiknya ada di layouts.master agar tidak berulang.
         Tautan navigasi telah diperbaiki agar berfungsi. --}}
    <nav class="w-full flex justify-between items-center px-10 py-5 bg-black text-white">
        <a href="{{ route('dashboard') }}" class="flex gap-1 items-center">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="h-8">
            <span class="text-xl font-bold">Temprina Sitik</span>
        </a>
        <ul class="flex gap-8 text-sm font-semibold">
            <li><a href="{{ route('dashboard') }}#kategori" class="hover:underline">Kategori</a></li>
            <li><a href="{{ route('user.tickets.index') }}" class="hover:underline">Tiket Saya</a></li>
            <li><a href="{{ route('faq') }}" class="hover:underline">FAQ</a></li>
            <li><a href="{{ route('kb.index') }}" class="hover:underline font-bold">Knowledge Base</a></li>
        </ul>
        <a href="{{ route('my.account') }}" class="flex items-center justify-center w-8 h-8 rounded-full bg-white text-black font-bold">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.761 0 5.304.839 7.379 2.271M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </a>
    </nav>

    <div class="text-center mt-12 px-6">
        <h1 class="text-3xl font-bold mb-2">Knowledge Base</h1>
        <p class="text-gray-600 max-w-3xl mx-auto">Dokumentasi lengkap seluruh penanganan kasus, permasalahan teknis, serta solusi yang telah diterapkan.</p>
    </div>

    {{-- PENYESUAIAN: Form berfungsi untuk search dan filter --}}
    <form action="{{ route('kb.index') }}" method="GET" class="flex flex-col items-center mt-8 gap-4">
        <div class="flex gap-2 w-full max-w-2xl">
            <input type="text" name="search" value="{{ request('search') }}" class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Cari pertanyaan atau kata kunci...">
            <button type="submit" class="px-6 py-2 bg-black text-white rounded-full font-semibold">Cari</button>
        </div>
        
        <div class="flex flex-wrap justify-center gap-3 max-w-3xl mx-auto">
            <a href="{{ route('kb.index') }}" class="px-4 py-1 rounded-full border text-sm font-semibold transition {{ !request('tag') ? 'bg-black text-white border-black' : 'border-gray-400 hover:bg-black hover:text-white' }}">Semua</a>
            @if(isset($tags))
                @foreach($tags as $tag)
                    <button type="submit" name="tag" value="{{ $tag->name }}" class="px-4 py-1 rounded-full border text-sm font-semibold transition {{ request('tag') == $tag->name ? 'bg-black text-white border-black' : 'border-gray-400 hover:bg-black hover:text-white' }}">{{ $tag->name }}</button>
                @endforeach
            @endif
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-10 px-10 mb-16">
        @forelse($knowledgeBases as $kb)
        {{-- PENYESUAIAN: Setiap kartu menjadi tautan ke halaman detail --}}
        <a href="{{ route('kb.show', $kb->id) }}" class="block bg-[#F8F8F8] p-5 rounded-xl border border-gray-200 flex gap-4 hover:border-blue-500 hover:shadow-md transition">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
            </div>
            <div>
                {{-- PENYESUAIAN: Semua properti disesuaikan dengan Model Eloquent --}}
                <p class="font-semibold text-sm text-black">{{ $kb->title }}</p>
                @if($kb->tags->isNotEmpty())
                    <p class="text-xs text-gray-600 mt-1">{{ $kb->tags->pluck('name')->implode(', ') }}</p>
                @endif
                <p class="text-xs text-gray-500">Oleh {{ $kb->author->name }} · {{ $kb->created_at->format('d M Y') }}</p>
            </div>
        </a>
        @empty
        <div class="col-span-1 md:col-span-2 text-center py-10">
            <p class="text-gray-500">Tidak ada artikel yang cocok dengan kriteria Anda.</p>
        </div>
        @endforelse
    </div>
    
    <div class="px-10 mb-16">
        {{ $knowledgeBases->appends(request()->query())->links() }}
    </div>

    {{-- Footer sebaiknya menjadi bagian dari layouts.master --}}
    <footer class="bg-black text-white text-center py-6 text-sm">
        ©{{ date('Y') }} All Rights Reserved by <a href="#" class="underline">PT. Temprina Media Grafika</a>
    </footer>
</div>
@endsection