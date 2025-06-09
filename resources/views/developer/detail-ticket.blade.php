@extends('layouts.developer')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm text-gray-500">Tiket #{{ $ticket->id }}</p>
            <h1 class="text-3xl font-bold text-gray-800">{{ $ticket->title }}</h1>
        </div>
        <div class="flex gap-2">
            @if($ticket->status->name !== 'Closed' && $ticket->assignee_id === auth()->id())
            <form action="{{ route('developer.tickets.complete', $ticket->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700">
                    Tandai Selesai
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-lg border-b pb-3 mb-4">Detail Tiket</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status:</span>
                        <span class="font-semibold px-2 py-1 text-xs rounded-full {{ 
                            match($ticket->status->name) {
                                'Open' => 'bg-blue-100 text-blue-800',
                                'In Progress' => 'bg-yellow-100 text-yellow-800',
                                'Closed' => 'bg-green-100 text-green-800',
                                default => 'bg-gray-100 text-gray-800'
                            } 
                        }}">{{ $ticket->status->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Prioritas:</span>
                        <span class="font-semibold px-2 py-1 text-xs rounded-full {{ 
                            match($ticket->priority->name) {
                                'Tinggi' => 'bg-red-100 text-red-800',
                                'Sedang' => 'bg-yellow-100 text-yellow-800',
                                default => 'bg-green-100 text-green-800'
                            } 
                        }}">{{ $ticket->priority->name }}</span>
                    </div>
                    <div class="flex justify-between"><span class="text-gray-500">Kategori:</span> <span class="font-semibold">{{ $ticket->category->name }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">SBU/Lokasi:</span> <span class="font-semibold">{{ $ticket->sbu->name }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Dibuat:</span> <span class="font-semibold">{{ $ticket->created_at->format('d M Y, H:i') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Ditugaskan ke:</span> <span class="font-semibold">{{ $ticket->assignee->name ?? 'Belum Diambil' }}</span></div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-lg border-b pb-3 mb-4">Informasi Pelapor</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Nama:</span> <span class="font-semibold">{{ $ticket->author->name }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Posisi:</span> <span class="font-semibold">{{ $ticket->author->position->name ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Departemen:</span> <span class="font-semibold">{{ $ticket->author->department->name ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Email:</span> <span class="font-semibold">{{ $ticket->author->email }}</span></div>
                </div>
            </div>
            
            @if($ticket->attachments->count() > 0)
            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-lg border-b pb-3 mb-4">Lampiran</h3>
                <ul class="space-y-2">
                    @foreach($ticket->attachments as $attachment)
                    <li>
                        <a href="{{ Storage::url($attachment->path) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span>{{ basename($attachment->path) }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-lg border-b pb-3 mb-4">Deskripsi Masalah</h3>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($ticket->description)) !!}
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-lg border-b pb-3 mb-4">Diskusi & Komentar</h3>
                <div class="space-y-5">
                    @forelse($ticket->comments as $comment)
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center font-bold text-gray-500">
                            {{ strtoupper(substr($comment->author->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $comment->author->name }} <span class="text-xs text-gray-400 font-normal">{{ $comment->created_at->diffForHumans() }}</span></p>
                            <p class="text-gray-700 mt-1">{!! nl2br(e($comment->message)) !!}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">Belum ada komentar.</p>
                    @endforelse

                    <div class="border-t pt-5 mt-5">
                        <form action="{{ route('developer.comments.add', $ticket->id) }}" method="POST">
                            @csrf
                            <textarea name="message" rows="4" class="w-full border-gray-300 rounded-lg focus:ring-black focus:border-black" placeholder="Tulis komentar Anda..." required></textarea>
                            <button type="submit" class="mt-2 bg-black text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-800">
                                Kirim Komentar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection