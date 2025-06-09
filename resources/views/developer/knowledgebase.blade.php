@extends('layouts.developer')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Knowledge Base</h1>
        <div class="flex gap-3">
            {{-- PENYESUAIAN: Tombol diubah menjadi link ke halaman create --}}
            <a href="{{ route('knowledgebase.create') }}" class="bg-black text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-800 text-sm">+ Artikel Baru</a>
        </div>
    </div>

    <form action="{{ route('knowledgebase.index') }}" method="GET">
        <div class="flex items-center gap-4 mb-4">
            <input type="text" name="search" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Cari berdasarkan judul atau konten..." value="{{ request('search') }}">
            <button type="submit" class="px-5 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Cari</button>
        </div>

        {{-- PENYESUAIAN: Filter tag dinamis dari controller --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('knowledgebase.index') }}" class="px-4 py-1 rounded-full border text-sm font-semibold transition {{ !request('tag') ? 'bg-black text-white border-black' : 'border-gray-400 hover:bg-black hover:text-white' }}">
                Semua
            </a>
            @if(isset($tags))
                @foreach($tags as $tag)
                    <a href="{{ route('knowledgebase.index', ['tag' => $tag->name]) }}" class="px-4 py-1 rounded-full border text-sm font-semibold transition {{ request('tag') == $tag->name ? 'bg-black text-white border-black' : 'border-gray-400 hover:bg-black hover:text-white' }}">
                        {{ $tag->name }}
                    </a>
                @endforeach
            @endif
        </div>
    </form>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
        @forelse($knowledgeBases as $kb)
        <div class="bg-white p-4 rounded-xl shadow-sm border flex flex-col justify-between" x-data="{ open: false }">
            <div class="flex gap-4 items-start">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="flex-grow">
                    {{-- PENYESUAIAN: Menggunakan properti dan relasi yang benar dari Model --}}
                    <p class="font-semibold text-sm text-[#1F1F1F]">{{ $kb->title }}</p>
                    <div class="text-xs text-gray-600 mt-1 space-x-2">
                        @if($kb->tags->isNotEmpty())
                            <span>Tags: {{ $kb->tags->pluck('name')->implode(', ') }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Oleh {{ $kb->author->name }} · {{ $kb->created_at->format('d M Y') }}</p>
                    <p class="text-xs mt-2 text-gray-700 line-clamp-2">{!! Str::limit(strip_tags($kb->content), 120) !!}</p>
                </div>
                <div class="relative">
                    <button @click="open = !open" @click.away="open = false" class="text-gray-500 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    </button>
                    {{-- PENYESUAIAN: Menambahkan dropdown aksi --}}
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-10 border">
                        <a href="{{ route('knowledgebase.show', $kb->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lihat Detail</a>
                        <a href="{{ route('knowledgebase.edit', $kb->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                        <form action="{{ route('knowledgebase.destroy', $kb->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-1 md:col-span-2 text-center py-10">
            <p class="text-gray-500">Tidak ada artikel yang ditemukan.</p>
        </div>
        @endforelse
    </div>
    
    {{-- Pagination --}}
    <div class="mt-6">
        {{ $knowledgeBases->appends(request()->query())->links() }}
    </div>
</div>
@endsection