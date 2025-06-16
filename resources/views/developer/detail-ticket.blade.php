@extends('layouts.developer')

@section('title', 'Detail Tiket #' . $ticket->id)

@section('content')
<div class="bg-gray-50 p-6 min-h-screen" x-data="{ status: '{{ $ticket->status_id }}' }">
    <div class="max-w-7xl mx-auto">
        <h2 class="font-bold text-2xl mb-6">Detail & Penanganan Tiket</h2>
        @if(session('info'))
            <div class="mb-4 p-4 bg-blue-100 border border-blue-200 text-blue-700 rounded-lg" role="alert">
                {{ session('info') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
                <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Kolom Utama: Detail dan Komentar -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detail Tiket -->
                <div class="bg-white rounded-xl shadow-md border p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">{{ $ticket->title }}</h1>
                            <p class="text-sm text-gray-500 font-mono">ID: #{{ $ticket->id }} • Dibuat: {{ $ticket->created_at->format('d M Y') }}</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ optional($ticket->priority)->name == 'Tinggi' ? 'bg-red-100 text-red-800' : '' }}">{{ optional($ticket->priority)->name ?? 'N/A' }}</span>
                    </div>
                    <p class="text-sm text-gray-700 bg-gray-50 p-4 rounded-md">{{ $ticket->description }}</p>
                    @if($ticket->attachments->isNotEmpty())
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold mb-2">Lampiran:</h4>
                        @foreach($ticket->attachments as $attachment)
                         <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank" class="text-blue-600 hover:underline text-sm">{{ basename($attachment->path) }}</a>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Riwayat Komentar/Diskusi -->
                <div class="bg-white rounded-xl shadow-md border p-6">
                    <h3 class="font-bold text-lg mb-4">Riwayat Diskusi</h3>
                    <div class="space-y-6">
                        @forelse($ticket->comments->sortBy('created_at') as $comment)
                        <div class="flex gap-4 {{ $comment->author->isDeveloper() ? 'justify-end' : '' }}">
                            <div class="{{ $comment->author->isDeveloper() ? 'order-2' : 'order-1' }}">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(optional($comment->author)->name) }}&background=random" class="w-10 h-10 rounded-full">
                            </div>
                            <div class="order-1 {{ $comment->author->isDeveloper() ? 'text-right' : '' }}">
                                <div class="bg-gray-100 p-3 rounded-lg max-w-md">
                                    <p class="text-sm font-semibold">{{ optional($comment->author)->name }}</p>
                                    <p class="text-sm text-gray-700 mt-1">{{ $comment->message }}</p>
                                    @if($comment->file_path)
                                    <a href="{{ asset('storage/' . $comment->file_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $comment->file_path) }}" class="mt-2 rounded-lg max-w-xs max-h-48 {{ $comment->author->isDeveloper() ? 'ml-auto' : '' }}">
                                    </a>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-sm text-gray-500">Belum ada diskusi pada tiket ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Kolom Samping: Aksi -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md border p-6 sticky top-24">
                    <h3 class="font-bold text-lg mb-4">Aksi & Pembaruan</h3>
                    <form method="POST" action="{{ route('developer.tickets.update', $ticket->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="title" value="{{ $ticket->title }}">

                        <div class="space-y-4">
                            {{-- 1. Input untuk Status Tiket --}}
                            <div>
                                <label for="status_id" class="block text-sm font-medium text-gray-700">Ubah Status Tiket</label>
                                <select id="status_id" name="status_id" x-model="status" class="mt-1 w-full p-2 border-gray-300 rounded-md">
                                    @foreach(App\Models\TicketStatus::all() as $status)
                                        <option value="{{ $status->id }}" @if($ticket->status_id == $status->id) selected @endif>{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 2. Input untuk Tanggapan / Solusi (Teks) --}}
                            <div>
                                <label for="comment" class="block text-sm font-medium text-gray-700">Tanggapan / Solusi</label>
                                <textarea id="comment" name="comment" rows="4" class="mt-1 w-full p-2 border-gray-300 rounded-md" placeholder="Tuliskan tindakan atau solusi Anda di sini..."></textarea>
                            </div>
                            
                            {{-- 3. Input untuk Lampiran File --}}
                            <div>
                                <label for="comment_file" class="block text-sm font-medium text-gray-700">Lampirkan File (Opsional)</label>
                                <input type="file" name="comment_file" id="comment_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-gray-100 hover:file:bg-gray-200">
                            </div>

                            {{-- 4. Opsi untuk membuat Knowledge Base --}}
                            <div x-show="status == {{ \App\Models\TicketStatus::where('name', 'Closed')->first()->id ?? 0 }}" x-transition>
                                <label class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg cursor-pointer">
                                    <input type="checkbox" name="create_knowledge_base" value="1" class="rounded text-blue-600">
                                    <span class="ml-3 text-sm text-blue-800">Jadikan solusi ini artikel <strong>Knowledge Base</strong></span>
                                </label>
                            </div>

                            {{-- 5. Tombol Submit Tunggal --}}
                            <button type="submit" class="w-full bg-black text-white py-2.5 px-4 rounded-lg font-semibold hover:bg-gray-800 transition-colors">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
