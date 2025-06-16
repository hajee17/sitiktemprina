@extends('layouts.master')

@section('title', $knowledge->title)

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4">

        <div class="bg-white shadow-lg rounded-lg p-6 md:p-8">
            @if($sourceTicket)

        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8">
                <p class="text-sm font-semibold text-blue-600">{{ optional($sourceTicket->category)->name ?? 'Artikel' }}</p>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 leading-tight">{{ $knowledge->title }}</h1>
                <p class="text-sm text-gray-500 mt-3">
                    Solusi untuk Tiket #{{ $sourceTicket->id }} • Dibuat oleh {{ optional($knowledge->author)->name ?? 'Admin' }} pada {{ $knowledge->created_at->translatedFormat('d F Y') }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 flex flex-col gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md border border-blue-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Ringkasan Solusi</h3>
                        <p class="text-sm text-gray-700 bg-blue-50 p-4 rounded-md">{{ $knowledge->content }}</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="font-bold text-lg mb-4">Riwayat Diskusi Lengkap</h3>
                        <div class="space-y-6">
                             <div class="flex gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(optional($sourceTicket->author)->name) }}&background=EBF4FF&color=007BFF" class="w-10 h-10 rounded-full">
                                <div>
                                    <div class="bg-gray-100 p-3 rounded-lg max-w-md">
                                        <p class="text-sm font-semibold">{{ optional($sourceTicket->author)->name }} (Pelapor)</p>
                                        <p class="text-sm text-gray-700 mt-1">{{ $sourceTicket->description }}</p>
                                        @if($sourceTicket->attachments->isNotEmpty())
                                            <div class="mt-2 border-t pt-2">
                                            @foreach($sourceTicket->attachments as $attachment)
                                             <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank" class="text-blue-600 hover:underline text-xs block">{{ basename($attachment->path) }}</a>
                                            @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Dibuat pada {{ $sourceTicket->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            @foreach($sourceTicket->comments->sortBy('created_at') as $comment)
                            <div class="flex gap-4 @if($comment->author->isDeveloper()) justify-end @endif">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(optional($comment->author)->name) }}&background=random" class="w-10 h-10 rounded-full @if($comment->author->isDeveloper()) order-2 @endif">
                                <div class="@if($comment->author->isDeveloper()) order-1 text-right @endif">
                                    <div class="p-3 rounded-lg max-w-md @if($comment->author->isDeveloper()) bg-blue-100 @else bg-gray-100 @endif">
                                        <p class="text-sm font-semibold">{{ optional($comment->author)->name }}</p>
                                        <p class="text-sm text-gray-700 mt-1">{{ $comment->message }}</p>
                                        @if($comment->file_path)
                                        <a href="{{ asset('storage/' . $comment->file_path) }}" target="_blank" class="mt-2 inline-block">
                                            <img src="{{ asset('storage/' . $comment->file_path) }}" class="rounded-lg max-w-xs max-h-48 @if($comment->author->isDeveloper()) ml-auto @endif">
                                        </a>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md sticky top-24">
                        <h3 class="text-base font-bold mb-4 border-b pb-3">Detail Info Tiket</h3>
                        <div class="text-sm text-gray-800 space-y-3">
                            <p><strong>Status:</strong><br><span class="font-semibold px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ optional($sourceTicket->status)->name }}</span></p>
                            <p><strong>Prioritas:</strong><br>{{ optional($sourceTicket->priority)->name }}</p>
                            <p><strong>Kategori:</strong><br>{{ optional($sourceTicket->category)->name }}</p>
                            <p><strong>Penanggung Jawab:</strong><br>{{ optional($sourceTicket->assignee)->name ?? 'N/A' }}</p>
                            <p><strong>Lokasi:</strong><br>{{ optional($sourceTicket->sbu)->name }} / {{ optional($sourceTicket->department)->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            @elseif($knowledge->type === 'blog')
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($knowledge->content)) !!} 
                </div>

            @elseif($knowledge->type === 'pdf' && $knowledge->file_path)
                <div class="bg-gray-100 p-4 rounded-lg text-center">
                    <p class="font-semibold mb-2">Dokumen PDF tidak dapat ditampilkan langsung.</p>
                    <a href="{{ asset('storage/' . $knowledge->file_path) }}" target="_blank" class="inline-block bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700">
                        Buka/Unduh PDF
                    </a>
                </div>

            @elseif($knowledge->type === 'video' && $knowledge->content)

                @if($knowledge->embed_url)
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe src="{{ $knowledge->embed_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full rounded-lg shadow-md"></iframe>
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