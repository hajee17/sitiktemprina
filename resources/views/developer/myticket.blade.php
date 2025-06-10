@extends('layouts.developer')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen flex flex-col md:flex-row">
    <!-- Main Content -->
    <div class="flex-1 max-w-6xl mx-auto space-y-6">
        <!-- Title -->
        <h1 class="text-2xl font-semibold text-gray-800">Tiket Saya</h1>

        @forelse($tickets as $ticket)
        <!-- Ticket Card -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-xl font-bold text-black">{{ $ticket->Judul_Tiket }}</h2>
                    <p class="text-sm text-gray-500 font-medium">{{ $ticket->ID_Ticket }}</p>
                    <p class="text-sm text-gray-400">{{ $ticket->created_at->format('j M Y – H:i') }}</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full 
                    @if($ticket->priority == 'Tinggi') bg-red-500 text-white
                    @elseif($ticket->priority == 'Sedang') bg-yellow-500 text-white
                    @else bg-green-500 text-white @endif">
                    {{ $ticket->priority }}
                </span>
            </div>

            <div class="text-sm text-gray-700 space-y-1 mb-4">
                <p><span class="font-semibold">Pembuat Tiket:</span> {{ $ticket->account->Name ?? 'N/A' }} – <span class="text-black font-medium">{{ $ticket->Position }}</span></p>
                <p><span class="font-semibold">Lokasi:</span> <span class="text-black font-medium">{{ $ticket->Location }}</span></p>
                <p><span class="font-semibold">Kategori Tiket:</span> <span class="text-black font-medium">{{ $ticket->Category }}</span></p>
                <p><span class="font-semibold">Status:</span> <span class="text-black font-medium">{{ $ticket->status->Status ?? 'Baru' }}</span></p>
            </div>

            <!-- Images -->
            @if($ticket->documentations->isNotEmpty())
            <div class="flex gap-3 overflow-x-auto mb-4">
                @foreach($ticket->documentations as $doc)
                <img src="{{ asset('storage/'.$doc->file_path) }}" 
                     class="rounded-lg w-40 h-24 object-cover" 
                     alt="Dokumentasi tiket">
                @endforeach
            </div>
            @endif

            <!-- Description -->
            <p class="text-sm text-gray-800 mb-6">
                {{ $ticket->Desc ?? 'Tidak ada deskripsi' }}
            </p>

            <!-- Update Status Form -->
            <form action="{{ route('developer.tickets.update-status', $ticket->ID_Ticket) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="status" class="block font-medium text-gray-700 mb-1">
                        Perbarui status tiket<span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih status --</option>
                        <option value="Diproses" {{ optional($ticket->status)->Status == 'Diproses' ? 'selected' : '' }}>Sedang diproses</option>
                        <option value="Selesai" {{ optional($ticket->status)->Status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Ditunda" {{ optional($ticket->status)->Status == 'Ditunda' ? 'selected' : '' }}>Ditunda</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <p class="text-gray-500">Anda belum mengambil tiket apapun</p>
            <a href="{{ route('developer.tickets.index') }}" class="text-blue-500 hover:underline mt-2 inline-block">
                Ambil Tiket Sekarang
            </a>
        </div>
        @endforelse
    </div>

    <!-- Riwayat Penanganan -->
    @if(isset($ticket))
    <div class="w-full md:w-72 mt-6 md:mt-0 md:ml-6">
        <h3 class="text-md font-semibold text-gray-700 mb-3">Riwayat Penanganan</h3>
        <div class="bg-white rounded-xl shadow p-4 text-sm space-y-4">
            @foreach($ticket->statusHistory as $history)
            <div class="flex items-start space-x-2">
                <div class="mt-1 w-2 h-2 bg-blue-500 rounded-full"></div>
                <div>
                    <p class="font-medium text-gray-800">{{ $history->Update_Time->format('j M Y - H:i') }}</p>
                    <p class="text-gray-500">Status: {{ $history->Status }}</p>
                    @if($history->Desc)
                    <p class="text-gray-500 mt-1">{{ $history->Desc }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection