@extends('layouts.developer')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold text-gray-800">Tiket Saya</h1>

        @forelse($tickets as $ticket)
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            {{-- PENYESUAIAN: Menggunakan properti model yang benar --}}
                            <h2 class="text-xl font-bold text-black">{{ $ticket->title }}</h2>
                            <p class="text-sm text-gray-500 font-medium">#{{ $ticket->id }}</p>
                            <p class="text-sm text-gray-400">{{ $ticket->created_at->format('j M Y – H:i') }}</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ 
                            match($ticket->priority->name) {
                                'Tinggi' => 'bg-red-500 text-white',
                                'Sedang' => 'bg-yellow-500 text-white',
                                default => 'bg-green-500 text-white'
                            }
                        }}">
                            {{ $ticket->priority->name }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-700 space-y-1 mb-4 border-t pt-4">
                        {{-- PENYESUAIAN: Menggunakan relasi yang benar --}}
                        <p><span class="font-semibold">Pembuat Tiket:</span> {{ $ticket->author->name ?? 'N/A' }} – <span class="text-black font-medium">{{ $ticket->author->position->name ?? 'N/A' }}</span></p>
                        <p><span class="font-semibold">Lokasi:</span> <span class="text-black font-medium">{{ $ticket->sbu->name ?? 'N/A' }}</span></p>
                        <p><span class="font-semibold">Kategori:</span> <span class="text-black font-medium">{{ $ticket->category->name ?? 'N/A' }}</span></p>
                        <p><span class="font-semibold">Status:</span> <span class="text-black font-medium">{{ $ticket->status->name ?? 'Baru' }}</span></p>
                    </div>

                    @if($ticket->attachments->isNotEmpty())
                    <div class="mb-4">
                        <p class="font-semibold text-sm mb-2">Lampiran:</p>
                        <div class="flex gap-3 overflow-x-auto">
                            @foreach($ticket->attachments as $attachment)
                                <a href="{{ Storage::url($attachment->path) }}" target="_blank">
                                    <img src="{{ Storage::url($attachment->path) }}" class="rounded-lg w-40 h-24 object-cover border" alt="Lampiran tiket">
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="prose max-w-none text-sm text-gray-800 mb-6 border-t pt-4">
                        <p class="font-semibold mb-2">Deskripsi:</p>
                        {!! nl2br(e($ticket->description ?? 'Tidak ada deskripsi.')) !!}
                    </div>

                    {{-- PENYESUAIAN: Route dan ID disesuaikan --}}
                    <form action="{{ route('developer.tickets.updateStatus', $ticket->id) }}" method="POST" class="border-t pt-4">
                        @csrf
                        @method('PATCH')
                        <div class="mb-2">
                            <label for="status-{{$ticket->id}}" class="block font-medium text-gray-700 mb-1 text-sm">Perbarui status tiket</label>
                            <div class="flex gap-2">
                                <select id="status-{{$ticket->id}}" name="status" class="flex-grow border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    {{-- PENYESUAIAN: Opsi dinamis, status saat ini dipilih --}}
                                    @foreach(App\Models\TicketStatus::whereIn('name', ['In Progress', 'Closed'])->get() as $status)
                                        <option value="{{ $status->name }}" @if($ticket->status->name == $status->name) selected @endif>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="w-full md:w-72 mt-6 md:mt-0 md:border-l md:pl-6 flex-shrink-0">
                    <h3 class="text-md font-semibold text-gray-700 mb-3">Riwayat Penanganan</h3>
                    <div class="bg-gray-50 rounded-xl p-4 text-sm space-y-4 max-h-96 overflow-y-auto">
                        {{-- PENYESUAIAN: Looping dari relasi 'comments' --}}
                        @forelse($ticket->comments as $comment)
                        <div class="flex items-start space-x-2">
                            <div class="mt-1 w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                            <div>
                                {{-- PENYESUAIAN: Menggunakan properti dari model Comment dan Author --}}
                                <p class="text-gray-700">{!! nl2br(e($comment->message)) !!}</p>
                                <p class="text-xs text-gray-500 mt-1">oleh <strong>{{ $comment->author->name }}</strong> · {{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center">Belum ada riwayat.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow p-10 text-center">
            <p class="text-gray-500">Anda belum mengambil tiket apapun yang sedang aktif.</p>
            <a href="{{ route('developer.allTickets') }}" class="text-blue-500 hover:underline mt-2 inline-block font-semibold">
                Ambil Tiket Sekarang
            </a>
        </div>
        @endforelse

        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection