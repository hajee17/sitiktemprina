@extends('layouts.developer')

@section('content')
<div class="w-full h-screen bg-[#F5F6FA] flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center px-10 py-3 bg-white w-[calc(100%-280px)] ml-[280px] fixed top-0 z-50">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-[#1F1F1F] relative">
                <div class="absolute w-6 h-6 bg-black rounded-full top-2 left-2"></div>
            </div>
            <div>
                <p class="font-bold text-sm text-[#404040]">Hafidz Irham</p>
                <p class="text-xs font-medium text-[#565656]">Developer</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="mt-[80px] ml-[280px] px-10 py-6 flex justify-between">
        <!-- Tiket Detail -->
        <div class="w-[65%] bg-white p-6 rounded-xl shadow-md">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-xl font-bold text-[#1F1F1F]">{{ $ticket->title }}</h1>
                    <p class="text-sm text-gray-500 font-mono">{{ $ticket->code }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y – H:i') }}</p>
                </div>
                <span class="@if($ticket->priority == 'Tinggi') bg-red-500 @elseif($ticket->priority == 'Sedang') bg-yellow-500 @else bg-blue-400 @endif text-white text-xs px-2 py-1 rounded-full font-semibold">
                    {{ ucfirst($ticket->priority) }}
                </span>
            </div>

            <div class="mt-4 text-sm text-gray-700 space-y-1">
                <p><strong>Pembuat Tiket:</strong> {{ $ticket->reporter }} – {{ $ticket->role }}</p>
                <p><strong>Lokasi:</strong> {{ $ticket->location }}</p>
                <p><strong>Kategori Tiket:</strong> {{ $ticket->category }}</p>
            </div>

            @if ($ticket->images && count($ticket->images) > 0)
            <div class="flex gap-3 mt-4">
                @foreach ($ticket->images as $img)
                    <img src="{{ asset('storage/' . $img) }}" class="w-1/3 h-[100px] object-cover rounded-md">
                @endforeach
            </div>
            @endif

            <p class="mt-4 text-sm text-gray-800">{{ $ticket->description }}</p>

             <!-- Status Update -->
            <form action="{{ route('developer.tickets.update', $ticket->id) }}" method="POST" class="mt-6">
                @csrf
                @method('PUT') 
                <input type="hidden" name="title" value="{{ $ticket->title }}">
                <label for="status_id" class="block font-medium text-sm text-gray-700 mb-1">Perbarui status tiket<span class="text-red-500">*</span></label>
                <select name="status_id" id="status_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring focus:ring-blue-300" required>
                    <option value="">-- Perbarui status tiket ---</option>
                    @foreach(App\Models\TicketStatus::all() as $status)
                        <option value="{{ $status->id }}" @if($ticket->status_id == $status->id) selected @endif>{{ $status->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="mt-4 px-6 py-2 bg-black text-white rounded-full font-semibold hover:bg-gray-800">Simpan Perubahan</button>
            </form>
        </div>

        <!-- Riwayat Penanganan -->
        <div class="w-[30%]">
            <h2 class="text-lg font-semibold mb-3">Riwayat Penanganan</h2>
            <div class="bg-white rounded-xl p-4 shadow space-y-2">
                @foreach ($ticket->history as $log)
                <div class="flex items-start gap-2">
                    <div class="w-3 h-3 rounded-full bg-blue-600 mt-1"></div>
                    <div>
                        <p class="text-sm font-medium text-black">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y – H:i') }}</p>
                        <p class="text-sm text-gray-600">{{ $log->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Floating Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <button class="bg-black text-white px-5 py-3 rounded-full shadow-lg text-sm font-semibold hover:bg-gray-800 flex items-center gap-2">
            <span class="material-icons">chat</span> Chat Pelapor
        </button>
    </div>
</div>
@endsection
