@extends('layouts.developer')

@section('content')
<div class="w-full min-h-screen bg-[#F5F6FA] p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Knowledge Base</h1>
        <div class="flex gap-3 w-full md:w-auto justify-end">
            <a href="{{ route('developer.knowledgebase.create') }}" class="w-full md:w-auto text-center bg-black text-white px-4 py-2 rounded-full font-semibold hover:bg-gray-800">
                + Baru
            </a>
        </div>
    </div>

    <form action="{{ route('developer.knowledgebase.index') }}" method="GET">
        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Cari judul artikel...">
            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800">Cari</button>
        </div>
    </form>

    {{-- Kategori/Tags --}}
    <div class="flex gap-3 mb-6 overflow-x-auto py-2 w-full flex-nowrap md:flex-wrap md:justify-start">
        @foreach(['Semua', 'Jaringan', 'Login', 'Printer', 'Software', 'Akun', 'Server', 'Masalah Teknis', 'Lainnya'] as $kategori)
            <a href="{{ route('developer.knowledgebase.index', ['tag' => $kategori == 'Semua' ? '' : $kategori]) }}" class="px-4 py-1 rounded-full border border-gray-400 text-sm font-semibold hover:bg-black hover:text-white {{ request('tag') == $kategori || (request('tag') == '' && $kategori == 'Semua') ? 'bg-black text-white' : '' }} flex-shrink-0 whitespace-nowrap">
                {{ $kategori }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($knowledgeBases as $kb)
            <div class="bg-white p-4 rounded-xl shadow-sm border flex flex-col sm:flex-row items-start justify-between relative"> 
                {{-- Kontainer utama untuk ikon dan teks --}}
                <div class="flex gap-4 w-full sm:w-auto pr-10"> {{-- Tambahkan pr-10 di sini --}}
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    {{-- flex-grow untuk area teks --}}
                    <div class="flex-grow">
                        {{-- Judul Artikel - Perubahan di sini --}}
                        {{-- Hapus overflow-hidden, whitespace-nowrap, text-overflow-ellipsis, dan pr-10 dari sini --}}
                        <p class="font-semibold text-sm text-[#1F1F1F]">
                            {{ $kb->title }}
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            @forelse($kb->tags as $tag)
                                <span class="font-medium mr-1">{{ $tag->name }}</span>
                            @empty
                                <span class="font-medium">Tanpa Kategori</span>
                            @endforelse
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ optional($kb->author)->name ?? 'Anonim' }} · {{ $kb->created_at->format('d M Y') }}
                        </p>
                        <p class="text-xs mt-2 text-gray-700 line-clamp-2">
                            {{ Str::limit($kb->content, 150) }}
                        </p>
                    </div>
                </div>

                {{-- Tombol titik tiga - Tidak ada perubahan besar, tetap absolute --}}
                <div class="absolute top-4 right-4" x-data="{ open: false }"> 
                    <button @click="open = !open" class="text-gray-500 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 py-1">
                        <a href="{{ route('developer.knowledgebase.edit', $kb->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                        <form action="{{ route('developer.knowledgebase.destroy', $kb->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-1 md:col-span-2 text-center text-gray-500 mt-8">Belum ada artikel di knowledge base.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $knowledgeBases->links() }}
    </div>
</div>
@endsection