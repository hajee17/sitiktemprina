@extends('layouts.master')

@section('title', 'Knowledge Base')

@section('content')
<div class="min-h-screen bg-white flex flex-col">
    <!-- Header -->
    <div class="text-center mt-12 px-6">
        <h1 class="text-3xl font-bold mb-2">Knowledge Base</h1>
        <p class="text-gray-600 max-w-3xl mx-auto">Dokumentasi lengkap seluruh penanganan kasus, permasalahan teknis, serta solusi yang telah diterapkan. Dirancang sebagai referensi bersama agar penanganan ke depan lebih cepat, efisien, dan konsisten.</p>
    </div>

    <!-- Search + Filter -->
    <div class="flex flex-col items-center mt-8 gap-4 px-6">
        <form action="{{ route('user.knowledgebase.index') }}" method="GET" class="flex gap-2 w-full max-w-2xl">
            <input type="text" name="search" value="{{ request('search') }}" class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none" placeholder="Cari berdasarkan judul...">
            <button type="submit" class="px-6 py-2 bg-black text-white rounded-full font-semibold">Cari</button>
        </form>
        
        <!-- Kategori/Tags -->
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('user.knowledgebase.index') }}" class="px-4 py-1 rounded-full border {{ !request('tag') ? 'bg-black text-white border-black' : 'border-gray-400' }} text-sm font-semibold hover:bg-black hover:text-white">Semua</a>
            @foreach($tags as $tag)
                <a href="{{ route('user.knowledgebase.index', ['tag' => $tag->name]) }}" class="px-4 py-1 rounded-full border {{ request('tag') == $tag->name ? 'bg-black text-white border-black' : 'border-gray-400' }} text-sm font-semibold hover:bg-black hover:text-white">{{ $tag->name }}</a>
            @endforeach
        </div>
    </div>

    <!-- Artikel -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10 px-10 mb-16">
        @forelse($knowledgeBases as $kb)
        <a href="{{ route('user.knowledgebase.show', $kb->id) }}" class="block bg-[#F8F8F8] p-5 rounded-xl border border-gray-200 hover:shadow-lg hover:border-blue-500 transition-all">
            <div class="flex gap-4 items-start">
                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 flex-shrink-0">
                    {{-- Icon dinamis berdasarkan tipe konten --}}
                    @if($kb->type == 'pdf')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    @elseif($kb->type == 'video')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    @endif
                </div>
                <div>
                    <p class="font-semibold text-sm text-black">{{ $kb->title }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ optional($kb->tags->first())->name ?? 'Umum' }}</p>
                    <p class="text-xs text-gray-500">{{ optional($kb->author)->name ?? 'Admin' }} · {{ $kb->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">Tidak ada artikel yang cocok dengan pencarian Anda.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="px-10 mb-16">
        {{ $knowledgeBases->withQueryString()->links() }}
    </div>

</div>
@endsection
