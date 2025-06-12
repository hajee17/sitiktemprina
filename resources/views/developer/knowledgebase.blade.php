@extends('layouts.developer')

@section('content')
<div class="w-full min-h-screen bg-[#F5F6FA] flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center px-10 py-3 bg-white w-[calc(100%-280px)] ml-[280px] fixed top-0 z-50">
        <h1 class="text-xl font-bold">Knowledge Base</h1>
        <div class="flex gap-3">
            {{-- Tombol mengarah ke halaman pembuatan artikel baru --}}
            <a href="{{ route('developer.knowledgebase.create') }}" class="bg-black text-white px-4 py-2 rounded-full font-semibold hover:bg-gray-800">+ Baru</a>
        </div>
    </div>

    <!-- Content -->
    <div class="mt-[80px] ml-[280px] px-10 py-6">
        <!-- Search & Filter -->
        <form action="{{ route('developer.knowledgebase.index') }}" method="GET">
            <div class="flex items-center gap-4 mb-6">
                <input type="text" name="search" value="{{ request('search') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Cari judul artikel...">
                <button type="submit" class="px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800">Cari</button>
            </div>
        </form>

        {{-- @todo: Implement filter logic in controller --}}
        <!-- Filter Button Group -->
        <div class="flex gap-3 mb-6">
            @foreach(['Semua', 'Jaringan', 'Printer', 'Software', 'Akun'] as $kategori)
                <a href="{{ route('developer.knowledgebase.index', ['tag' => $kategori == 'Semua' ? '' : $kategori]) }}" class="px-4 py-1 rounded-full border border-gray-400 text-sm font-semibold hover:bg-black hover:text-white {{ request('tag') == $kategori ? 'bg-black text-white' : '' }}">{{ $kategori }}</a>
            @endforeach
        </div>

        <!-- Knowledge Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($knowledgeBases as $kb)
                <div class="bg-white p-4 rounded-xl shadow-sm border flex items-start justify-between">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            {{-- PERBAIKAN: Menggunakan properti yang benar dari model --}}
                            <p class="font-semibold text-sm text-[#1F1F1F]">{{ $kb->title }}</p>
                            <p class="text-xs text-gray-600 mt-1">
                                {{-- Menampilkan tag pertama sebagai kategori --}}
                                <span class="font-medium">{{ optional($kb->tags->first())->name ?? 'Tanpa Kategori' }}</span>
                            </p>
                            <p class="text-xs text-gray-500">
                                {{-- Menggunakan relasi author dan kolom created_at --}}
                                {{ optional($kb->author)->name ?? 'Anonim' }} · {{ $kb->created_at->format('d M Y') }}
                            </p>
                            <p class="text-xs mt-2 text-gray-700 line-clamp-2">
                                {{-- Menampilkan ringkasan konten --}}
                                {{ Str::limit($kb->content, 150) }}
                            </p>
                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-500 hover:text-gray-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 py-1">
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
                <p class="col-span-2 text-center text-gray-500 mt-8">Belum ada artikel di knowledge base.</p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $knowledgeBases->links() }}
        </div>
    </div>
</div>
@endsection
