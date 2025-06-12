@php
function getYoutubeEmbedUrl($url) {
    if (strpos($url, 'youtu.be/') !== false) {
        preg_match('%youtu.be/([^"&?/ ]{11})%i', $url, $match);
        return $match[1] ?? null ? 'https://www.youtube.com/embed/' . $match[1] : null;
    } elseif (strpos($url, 'youtube.com/watch') !== false) {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=))([^"&?/ ]{11})%i', $url, $match);
        return $match[1] ?? null ? 'https://www.youtube.com/embed/' . $match[1] : null;
    }
    return null; // Return null jika bukan URL YouTube
}
@endphp

@extends('layouts.master')

@section('title', $knowledge->title)

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        
        <!-- Header Artikel -->
        <div class="text-center mb-8">
            <p class="text-sm font-semibold text-blue-600">{{ optional($knowledge->tags->first())->name ?? 'Artikel' }}</p>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 leading-tight">{{ $knowledge->title }}</h1>
            <p class="text-sm text-gray-500 mt-3">
                Ditulis oleh {{ optional($knowledge->author)->name ?? 'Admin' }} • {{ $knowledge->created_at->translatedFormat('d F Y') }}
            </p>
        </div>

        <!-- Konten Dinamis -->
        <div class="bg-white shadow-lg rounded-lg p-6 md:p-8">
            @if($knowledge->type === 'blog')
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {{-- Menggunakan e() untuk keamanan dan nl2br untuk baris baru --}}
                    {!! nl2br(e($knowledge->content)) !!} 
                </div>

            @elseif($knowledge->type === 'pdf' && $knowledge->file_path)
                <div class="bg-gray-100 p-4 rounded-lg text-center">
                    <p class="font-semibold mb-2">Dokumen PDF tidak dapat ditampilkan langsung.</p>
                    <a href="{{ asset('storage/' . $knowledge->file_path) }}" target="_blank" class="inline-block bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700">
                        Buka/Unduh PDF
                    </a>
                </div>
                {{-- Opsi untuk embed, namun link download lebih universal --}}
                {{-- <iframe src="{{ asset('storage/' . $knowledge->file_path) }}" class="w-full h-[800px] mt-4 border rounded-md" frameborder="0"></iframe> --}}

            @elseif($knowledge->type === 'video' && $knowledge->content)
                @php $embedUrl = getYoutubeEmbedUrl($knowledge->content); @endphp
                @if($embedUrl)
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full rounded-lg shadow-md"></iframe>
                    </div>
                @else
                    <p>URL Video tidak valid. <a href="{{ $knowledge->content }}" target="_blank" class="text-blue-600">Buka link asli</a>.</p>
                @endif
                
            @else
                <p class="text-red-500">Tipe konten tidak didukung atau konten tidak ditemukan.</p>
            @endif
        </div>
    </div>
</div>
@endsection
