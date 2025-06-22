@extends('layouts.developer')

@section('content')

<div class="px-8 py-6 bg-gray-50 min-h-screen" x-data="{
    showModal: false,
    ticket: null,
    showImageModal: false,
    currentImage: '',
    // Zoom related properties for the image viewer
    scale: 1,

    // Function to handle opening the main detail modal
    openDetailModal(ticketData) {
        this.ticket = ticketData;
        this.showModal = true;
        document.body.style.overflow = 'hidden'; // Prevent body scroll
    },
    // Function to handle closing the main detail modal
    closeDetailModal() {
        this.showModal = false;
        document.body.style.overflow = 'auto'; // Re-enable body scroll
    },

    // Function to handle opening the image viewer modal
    openImageModal(imagePath) {
        this.currentImage = imagePath;
        this.scale = 1; // Reset zoom on new image
        this.showImageModal = true;
        document.body.style.overflow = 'hidden'; // Prevent body scroll
    },
    // Function to handle closing the image viewer modal
    closeImageModal() {
        this.showImageModal = false;
        document.body.style.overflow = 'auto'; // Re-enable body scroll
    },

    zoomIn() {
        this.scale = Math.min(3, this.scale + 0.2); // Max zoom 3x
    },
    zoomOut() {
        this.scale = Math.max(0.5, this.scale - 0.2); // Min zoom 0.5x
    },
    resetZoom() {
        this.scale = 1;
    }
}"
x-init="() => {}">
    <h1 class="text-2xl font-bold text-gray-800">Ambil Tiket yang Tersedia</h1>

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
                            @click="openDetailModal({{ json_encode($ticket->load('attachments')) }})">
                            Lihat Detail Singkat
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $tickets->links() }}</div>
    @endif

    {{-- Main Detail Modal --}}
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-800 bg-opacity-30 backdrop-blur-sm z-50 flex items-center justify-center p-4" {{-- Dikembalikan ke background blur --}}
         @click.away="closeDetailModal()"
         style="display: none;">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 relative" @click.stop>
            <template x-if="ticket">
                <div>
                    {{-- Tombol X --}}
                    <button @click="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-4xl p-2 leading-none focus:outline-none">
                        &times;
                    </button>
                    <div class="flex justify-between items-start pr-10">
                        <div>
                            <h2 class="text-xl font-bold" x-text="ticket.title"></h2>
                            <p class="text-sm text-gray-500" x-text="'#' + ticket.id"></p>
                        </div>
                    </div>
                    <div class="mt-4 border-t pt-4 text-sm space-y-2">
                        <p><strong>Pelapor:</strong> <span x-text="ticket.author ? ticket.author.name : 'N/A'"></span></p>
                        <p><strong>Kategori:</strong> <span x-text="ticket.category ? ticket.category.name : 'N/A'"></span></p>
                        <p><strong>Prioritas:</strong> <span x-text="ticket.priority ? ticket.priority.name : 'N/A'"></span></p>
                        <p><strong>Lokasi:</strong> <span x-text="(ticket.department ? ticket.department.name : 'N/A') + ' / ' + (ticket.sbu ? ticket.sbu.name : 'N/A')"></span></p>
                        <p class="mt-2 bg-gray-50 p-3 rounded-md" x-text="ticket.description"></p>

                        {{-- Lampiran --}}
                        <div x-show="ticket.attachments && ticket.attachments.length > 0" class="mt-4 border-t pt-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Lampiran:</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                <template x-for="attachment in ticket.attachments" :key="attachment.id">
                                    <div>
                                        {{-- Cek apakah file adalah gambar --}}
                                        <template x-if="attachment.path.toLowerCase().endsWith('.jpeg') || attachment.path.toLowerCase().endsWith('.jpg') || attachment.path.toLowerCase().endsWith('.png')">
                                            <a href="#" @click.prevent="openImageModal(`/storage/${attachment.path}`)"
                                               class="block w-full h-24 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-500 transition-all"> {{-- Kelas dikembalikan sebagai atribut 'class' --}}
                                                <img :src="`/storage/${attachment.path}`" alt="Lampiran" class="w-full h-full object-cover">
                                            </a>
                                        </template>
                                        {{-- Cek apakah file adalah PDF --}}
                                        <template x-if="attachment.path.toLowerCase().endsWith('.pdf')">
                                            <a :href="`/storage/${attachment.path}`" target="_blank" class="flex flex-col items-center justify-center w-full h-24 bg-red-50 rounded-lg border border-red-200 text-red-600 hover:border-red-500 transition-all">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 0 01-2-2V5a2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 0 01-2 2z"></path></svg>
                                                <span class="text-xs mt-1">PDF File</span>
                                            </a>
                                        </template>
                                        {{-- Nama file, terpotong jika terlalu panjang --}}
                                        <p class="text-xs text-gray-500 mt-1 truncate" x-text="attachment.path.split('/').pop()"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
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

    {{-- Image Viewer Modal (Simplified) --}}
    <div x-show="showImageModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-75 z-[60] flex items-center justify-center p-4"
         @click.away="closeImageModal()"
         style="display: none;">
        <div class="bg-gray-900 rounded-lg shadow-xl max-w-full max-h-[90vh] overflow-hidden relative flex items-center justify-center" @click.stop style="width: 90%; height: 90%;">
            {{-- Close button for image viewer --}}
            <button @click="closeImageModal()" class="absolute top-4 right-4 text-white text-3xl p-2 leading-none focus:outline-none z-10 bg-gray-800 rounded-full hover:bg-gray-700 w-10 h-10 flex items-center justify-center">
                &times;
            </button>

            <div class="w-full h-full flex items-center justify-center overflow-auto">
                <img :src="currentImage" alt="Zoomable Image"
                    :style="{ transform: `scale(${scale})`, 'transition': 'transform 0.1s ease-out', 'transform-origin': 'center center' }"
                    class="object-contain max-w-full max-h-full"
                    x-ref="zoomImage"
                >
            </div>

            {{-- Zoom Controls --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-4 p-2 bg-black bg-opacity-50 rounded-lg">
                <button @click="zoomIn()" class="text-white text-2xl font-bold px-3 py-1 rounded-full hover:bg-gray-700 focus:outline-none">&plus;</button>
                <button @click="zoomOut()" class="text-white text-2xl font-bold px-3 py-1 rounded-full hover:bg-gray-700 focus:outline-none">&minus;</button>
                <button @click="resetZoom()" class="text-white text-sm px-3 py-1 rounded-full hover:bg-gray-700 focus:outline-none">Reset</button>
            </div>
        </div>
    </div>
</div>
@endsection