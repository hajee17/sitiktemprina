@extends('layouts.developer')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-[#1F1F1F]">Edit Tiket Saya</h1>
        {{-- Tombol Kembali ke daftar tiket yang Anda tangani --}}
        <a href="{{ route('developer.myTickets') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Kembali ke Tiket Saya</a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-100 text-green-800 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="p-4 bg-blue-100 text-blue-800 rounded-lg">{{ session('info') }}</div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        <div class="w-full lg:w-[65%] bg-white p-6 rounded-xl shadow-md border">
            <div class="flex justify-between items-start">
                <div>
                    {{-- PENYESUAIAN: Menggunakan properti model yang benar --}}
                    <h1 class="text-xl font-bold text-[#1F1F1F]">{{ $ticket->title }}</h1>
                    <p class="text-sm text-gray-500 font-mono">#{{ $ticket->id }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ $ticket->created_at->format('d M Y – H:i') }}</p>
                </div>
                {{-- PENYESUAIAN: Menggunakan relasi priority->name --}}
                <span class="text-white text-xs px-2 py-1 rounded-full font-semibold {{ 
                    match($ticket->priority->name) {
                        'Tinggi' => 'bg-red-500',
                        'Sedang' => 'bg-yellow-500',
                        default => 'bg-blue-400'
                    } 
                }}">
                    {{ $ticket->priority->name }}
                </span>
            </div>

            <div class="mt-4 text-sm text-gray-700 space-y-1 border-t pt-4">
                {{-- PENYESUAIAN: Menggunakan relasi author, role, sbu, dan category --}}
                <p><strong>Pembuat Tiket:</strong> {{ $ticket->author->name }} – {{ $ticket->author->role->name ?? 'User' }}</p>
                <p><strong>Lokasi:</strong> {{ $ticket->sbu->name }}</p>
                <p><strong>Kategori Tiket:</strong> {{ $ticket->category->name }}</p>
            </div>

            {{-- PENYESUAIAN: Looping melalui relasi 'attachments' --}}
            @if ($ticket->attachments->count() > 0)
            <div class="mt-4">
                <p class="font-semibold text-sm mb-2">Lampiran:</p>
                <div class="flex flex-wrap gap-3">
                    @foreach ($ticket->attachments as $attachment)
                        <a href="{{ Storage::url($attachment->path) }}" target="_blank" class="block">
                            <img src="{{ Storage::url($attachment->path) }}" class="w-32 h-24 object-cover rounded-md border hover:opacity-80 transition">
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="prose max-w-none mt-4 text-sm text-gray-800 border-t pt-4">
                {!! nl2br(e($ticket->description)) !!}
            </div>

            {{-- PENYESUAIAN: Route disesuaikan --}}
            <form action="{{ route('developer.tickets.updateStatus', $ticket->id) }}" method="POST" class="mt-6 border-t pt-6">
                @csrf
                @method('PATCH') {{-- Menggunakan PATCH untuk update parsial --}}
                <label for="status" class="block font-medium text-sm text-gray-700 mb-1">Perbarui status tiket<span class="text-red-500">*</span></label>
                <div class="flex items-center gap-4">
                    <select name="status" id="status" class="w-full lg:w-1/2 border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring focus:ring-blue-300" required>
                        {{-- PENYESUAIAN: Opsi status dinamis dari controller --}}
                        @foreach($statuses as $status)
                            <option value="{{ $status->name }}" @if($ticket->status->name == $status->name) selected @endif>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg font-semibold hover:bg-gray-800">Simpan</button>
                </div>
            </form>
        </div>

        <div class="w-full lg:w-[30%]">
            <h2 class="text-lg font-semibold mb-3">Riwayat Penanganan</h2>
            <div class="bg-white rounded-xl p-4 shadow space-y-4 border max-h-[70vh] overflow-y-auto">
                {{-- PENYESUAIAN: Looping melalui relasi 'comments' bukan 'history' --}}
                @forelse ($ticket->comments as $comment)
                <div class="flex items-start gap-3">
                    <div class="w-3 h-3 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
                    <div>
                        {{-- PENYESUAIAN: Menggunakan $comment->message dan $comment->author->name --}}
                        <p class="text-sm text-gray-600">{!! nl2br(e($comment->message)) !!}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            oleh <strong>{{ $comment->author->name }}</strong> - {{ $comment->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-center text-gray-500 p-4">Belum ada riwayat penanganan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection