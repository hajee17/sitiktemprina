@extends('layouts.developer')

@section('content')
{{-- Menggunakan Alpine.js untuk state management modal --}}
<div class="px-8 py-6 bg-gray-50 min-h-screen" x-data="{ showModal: false, ticket: null }">
    <h1 class="text-2xl font-semibold mb-6">Ambil Tiket yang Tersedia</h1>

    {{-- Filter dan Search Form --}}
    <form action="{{ route('developer.tickets.index') }}" method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input 
                type="text" 
                name="search"
                placeholder="Cari ID atau Judul Tiket"
                class="w-full px-4 py-2 rounded-md border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 md:col-span-2"
                value="{{ request('search') }}"
            >
            <select name="priority_id" class="w-full px-4 py-2 rounded-md border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Prioritas</option>
                @foreach($priorities as $priority)
                    <option value="{{ $priority->id }}" {{ request('priority_id') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="w-full bg-black text-white px-5 py-2 rounded-md font-medium hover:bg-gray-800">Cari & Filter</button>
        </div>
    </form>

    {{-- List Tiket Tersedia --}}
    @if($tickets->isEmpty())
        <div class="bg-white p-8 rounded-lg text-center shadow-sm">
            <p class="text-gray-500">Tidak ada tiket yang tersedia saat ini. Kerja bagus!</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tickets as $ticket)
                <div class="bg-white border-2 border-gray-200 rounded-xl p-6 flex flex-col justify-between hover:shadow-lg hover:border-blue-500 transition-all duration-300">
                    <div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold px-3 py-1 rounded-full w-fit
                                @if(optional($ticket->priority)->name == 'Tinggi') bg-red-100 text-red-800
                                @elseif(optional($ticket->priority)->name == 'Sedang') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ optional($ticket->priority)->name ?? 'N/A' }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-500">#{{ $ticket->id }}</p>
                            <h3 class="mt-1 font-bold text-lg leading-tight">{{ $ticket->title }}</h3>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Pelapor: <strong>{{ optional($ticket->author)->name ?? 'N/A' }}</strong></p>
                        <button 
                            type="button" 
                            class="w-full mt-4 bg-gray-100 text-gray-800 px-4 py-2 rounded-md text-sm hover:bg-gray-200 font-semibold"
                            @click="ticket = {{ json_encode($ticket) }}; showModal = true">
                            Lihat Detail Singkat
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $tickets->links() }}</div>
    @endif

    <!-- Modal untuk Detail Singkat -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4" @click.away="showModal = false" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6" @click.stop>
            <template x-if="ticket">
                <div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-bold" x-text="ticket.title"></h2>
                            <p class="text-sm text-gray-500" x-text="'#' + ticket.id"></p>
                        </div>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>
                    <div class="mt-4 border-t pt-4 text-sm space-y-2">
                        <p><strong>Pelapor:</strong> <span x-text="ticket.author ? ticket.author.name : 'N/A'"></span></p>
                        <p><strong>Kategori:</strong> <span x-text="ticket.category ? ticket.category.name : 'N/A'"></span></p>
                        <p><strong>Prioritas:</strong> <span x-text="ticket.priority ? ticket.priority.name : 'N/A'"></span></p>
                        <p><strong>Lokasi:</strong> <span x-text="(ticket.department ? ticket.department.name : 'N/A') + ' / ' + (ticket.sbu ? ticket.sbu.name : 'N/A')"></span></p>
                        <p class="mt-2 bg-gray-50 p-3 rounded-md" x-text="ticket.description"></p>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <form :action="`/developer/tickets/${ticket.id}/take`" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                                Ambil Tiket Ini
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection
