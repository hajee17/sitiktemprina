@extends('layouts.developer')

@section('content')
<div class="bg-gray-50 p-6 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h2 class="font-bold text-2xl mb-6">Detail Tiket</h2>

        <div class="flex flex-col lg:flex-row gap-6">

            <!-- Kolom Kiri: Detail dan Form Update -->
            <div class="flex-grow bg-white rounded-xl shadow-md border p-6">
                
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">{{ $ticket->title }}</h1>
                        <p class="text-sm text-gray-500 font-mono">#{{ $ticket->id }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $ticket->created_at->format('d M Y – H:i') }}</p>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full 
                        @if(optional($ticket->priority)->name == 'Tinggi') bg-red-100 text-red-800
                        @elseif(optional($ticket->priority)->name == 'Sedang') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ optional($ticket->priority)->name ?? 'N/A' }}
                    </span>
                </div>

                <div class="text-sm text-gray-700 space-y-1 border-t pt-4 mb-4">
                    <p><strong>Pembuat Tiket:</strong> {{ optional($ticket->author)->name ?? 'N/A' }} – {{ optional($ticket->author->role)->name ?? 'N/A' }}</p>
                    <p><strong>Lokasi:</strong> {{ $ticket->department->name ?? 'N/A' }} / {{ $ticket->sbu->name ?? 'N/A' }}</p>
                    <p><strong>Kategori Tiket:</strong> {{ optional($ticket->category)->name ?? 'N/A' }}</p>
                </div>

                <!-- Bukti Foto -->
                @if($ticket->attachments->isNotEmpty())
                    <div class="flex gap-3 mb-4">
                        @foreach($ticket->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $attachment->path) }}" class="w-24 h-24 object-cover rounded-md border hover:opacity-80">
                            </a>
                        @endforeach
                    </div>
                @endif

                <p class="text-sm text-gray-800 bg-gray-50 p-4 rounded-md">
                    {{ $ticket->description }}
                </p>

                <!-- Form update status -->
                {{-- PERBAIKAN DI SINI --}}
                <form method="POST" action="{{ route('developer.tickets.update', $ticket->id) }}" class="mt-6 border-t pt-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Menggunakan metode PUT untuk update --}}

                    {{-- Menambahkan input tersembunyi untuk title agar validasi lolos --}}
                    <input type="hidden" name="title" value="{{ $ticket->title }}">
                    
                    <div class="mb-4">
                        <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">Perbarui status tiket<span class="text-red-500">*</span></label>
                        <select id="status_id" name="status_id" required class="w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Pilih status --</option>
                            @foreach(App\Models\TicketStatus::all() as $status)
                                <option value="{{ $status->id }}" {{ $ticket->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Penjelasan atau tindakan perbaikan (opsional)</label>
                        <textarea id="comment" name="comment" rows="3" class="w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Contoh: Sedang melakukan pengecekan router di lokasi."></textarea>
                    </div>
                    
                    {{-- @todo: Implement file upload logic in controller for this form --}}
                    {{-- <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unggah bukti penyelesaian (opsional)</label>
                        <input type="file" name="attachments[]" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"/>
                    </div> --}}

                    <button type="submit" class="w-full bg-black text-white py-2.5 px-4 rounded-lg font-semibold hover:bg-gray-800 transition">Simpan Perubahan</button>
                </form>
            </div>

            <!-- Kolom Kanan: Riwayat -->
            <div class="w-full lg:w-80 lg:flex-shrink-0">
                <h3 class="font-bold text-lg mb-4">Riwayat Penanganan</h3>
                <div class="space-y-4">
                    @forelse($ticket->comments->sortByDesc('created_at') as $comment)
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-semibold">{{ optional($comment->author)->name }}</p>
                            <p class="text-xs text-gray-600">{{ $comment->message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-sm text-gray-500 py-4">
                        <p>Belum ada riwayat penanganan.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
