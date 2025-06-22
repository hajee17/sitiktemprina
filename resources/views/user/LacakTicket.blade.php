@extends('layouts.master')

@section('title', 'Detail Tiket #' . $ticket->id)

@section('content')

<div class="w-full py-10 px-5 bg-gray-100">
    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Detail Tiket, Beri Tanggapan, dan Riwayat Diskusi --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            {{-- Detail Tiket --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-gray-800 text-xl font-bold m-0">{{ $ticket->title }}</h2>
                        <p class="m-0 text-sm text-gray-500">ID: #{{ $ticket->id }}</p>
                    </div>
                    @php
                        $statusName = optional($ticket->status)->name ?? 'Unknown';
                        $statusColors = [
                            'Open' => 'bg-blue-500', 'In Progress' => 'bg-yellow-500',
                            'Closed' => 'bg-green-500', 'Cancelled' => 'bg-red-500',
                            'On Hold' => 'bg-orange-500'
                        ];
                    @endphp
                    <span class="text-white text-xs font-semibold px-3 py-1 rounded-full {{ $statusColors[$statusName] ?? 'bg-gray-500' }}">
                        {{ $statusName }}
                    </span>
                </div>
                <hr class="my-4">
                <p class="mt-4 text-sm text-gray-800 whitespace-pre-wrap">{{ $ticket->description }}</p>
                @if($ticket->attachments->isNotEmpty())
                    <div class="mt-4">
                        <p class="font-semibold text-sm mb-2">Lampiran Awal:</p>
                        @foreach($ticket->attachments as $attachment)
                           <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank" class="text-blue-600 underline text-sm">{{ basename($attachment->path) }}</a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Beri Tanggapan atau Jawaban --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold mb-4">Beri Tanggapan atau Jawaban</h3>
                <form action="{{ route('user.comments.store', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <textarea name="message" rows="4" placeholder="Tulis komentar Anda di sini..." class="w-full p-2 border border-gray-300 rounded-md mb-3"></textarea>
                    <div class="flex justify-between items-center">
                        <input type="file" name="comment_file" id="comment_file" class="text-sm">
                        <button type="submit" class="px-5 py-2 bg-black text-white border-none rounded-md font-bold cursor-pointer hover:bg-gray-800">Kirim</button>
                    </div>
                </form>
            </div>

            {{-- Riwayat Diskusi - DIPINDAH KE SINI --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold mb-4 border-b pb-3">Riwayat Diskusi</h3> {{-- Mengubah font-base ke font-lg agar konsisten --}}
                <div class="max-h-96 overflow-y-auto pr-2 space-y-4">
                    @forelse($ticket->comments->sortBy('created_at') as $comment)
                    <div class="flex gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(optional($comment->author)->name) }}&background=random" class="w-10 h-10 rounded-full flex-shrink-0">
                        <div>
                            <p class="font-semibold text-sm m-0">{{ optional($comment->author)->name }} <span class="font-normal text-gray-500 text-xs">• {{ $comment->created_at->diffForHumans() }}</span></p>
                            <div class="bg-gray-100 p-3 rounded-md mt-1">
                                <p class="text-sm text-gray-800 m-0 whitespace-pre-wrap">{{ $comment->message }}</p>
                                @if($comment->file_path)
                                    @php
                                        $fileExtension = pathinfo($comment->file_path, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($fileExtension), ['jpeg', 'jpg', 'png', 'gif']);
                                    @endphp
                                    @if($isImage)
                                        <a href="{{ asset('storage/' . $comment->file_path) }}" target="_blank" class="block mt-2">
                                            <img src="{{ asset('storage/' . $comment->file_path) }}" class="rounded-md max-w-[200px] max-h-36 object-cover">
                                        </a>
                                    @else
                                        {{-- Jika bukan gambar, tampilkan link biasa --}}
                                        <a href="{{ asset('storage/' . $comment->file_path) }}" target="_blank" class="text-blue-600 underline text-sm mt-2 block">{{ basename($comment->file_path) }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-sm text-gray-500">Belum ada diskusi pada tiket ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Info & Rekomendasi Solusi --}}
        <div class="flex flex-col gap-6">
            {{-- Detail Info --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold mb-4 border-b pb-3">Detail Info</h3> {{-- Mengubah font-base ke font-lg agar konsisten --}}
                <div class="text-sm text-gray-800 space-y-3">
                    <p><strong>Pelapor:</strong><br>{{ optional($ticket->author)->name }}</p>
                    <p><strong>Kategori:</strong><br>{{ optional($ticket->category)->name }}</p>
                    <p><strong>Prioritas:</strong><br>{{ optional($ticket->priority)->name }}</p>
                    <p><strong>Penanggung Jawab:</strong><br>{{ optional($ticket->assignee)->name ?? 'Belum ada' }}</p>
                </div>
            </div>

            {{-- Rekomendasi Solusi (jika ada) --}}
            @if(isset($recommendations) && $recommendations->isNotEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold mb-4 border-b pb-3">💡 Rekomendasi Solusi</h3>
                <div class="flex flex-col gap-3">
                    @foreach($recommendations as $kb)
                        <a href="{{ route('user.knowledgebase.show', $kb->id) }}" class="no-underline text-inherit block">
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-500">
                                <p class="font-semibold text-sm m-0 mb-1">{{ $kb->title }}</p>
                                <p class="text-xs text-gray-600 m-0">{{ Str::limit($kb->content, 80) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection