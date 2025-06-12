@extends('layouts.master')

@section('title', 'Dashboard - Temprina SITIK')

@section('content')

<!-- Hero Section -->
<div class="w-full px-4 md:px-12 pt-0 pb-8">
    <div class="bg-cover bg-center bg-no-repeat rounded-xl p-8 md:p-12"
         style="background-image: url('{{ asset('images/frame21.png') }}');">
        {{-- Menampilkan nama user yang login --}}
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Selamat Datang di SITIK, {{ Auth::user()->name }}!</h2>
        <p class="text-lg text-gray-700 mb-6">Ada yang perlu kami bantu? Buat tiket baru sekarang.</p>
        <a href="{{ route('user.tickets.create') }}" class="inline-block bg-black text-white font-bold py-3 px-6 rounded-full">
            Buat Tiket Baru
        </a>
    </div>
</div>
<!-- Status Tiket Section -->
<div class="container mx-auto text-center my-12">
    <h3 class="text-2xl font-semibold text-gray-800">Cek Status Tiket Anda</h3>
    <p class="text-gray-600 mb-4">Masukkan nomor tiket untuk melacak tiket Anda!</p>
    {{-- Form ini sekarang akan berfungsi --}}
    <form id="trackTicketForm" class="flex justify-center">
        <input type="text" id="ticketIdInput" placeholder="Contoh : 51" class="w-2/5 p-3 border rounded-l-lg focus:outline-none">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-r-lg">
            Lacak Tiket
        </button>
    </form>
</div>

<!-- Statistik Tiket (menggunakan variabel dari controller) -->
<div class="container mx-auto flex flex-wrap justify-center gap-6">
    <div class="bg-white p-6 shadow-lg rounded-lg w-64 text-center">
        <img src="{{ asset('images/icon1.png') }}" alt="Total Tiket" class="mx-auto mb-3">
        <p class="text-2xl font-bold">{{ number_format($totalTiket) }}</p>
        <p class="text-gray-600">Total Tiket Dibuat</p>
    </div>
    <div class="bg-white p-6 shadow-lg rounded-lg w-64 text-center">
        <img src="{{ asset('images/icon2.png') }}" alt="Tiket Diproses" class="mx-auto mb-3">
        <p class="text-2xl font-bold">{{ number_format($tiketDiproses) }}</p>
        <p class="text-gray-600">Tiket Diproses</p>
    </div>
    <div class="bg-white p-6 shadow-lg rounded-lg w-64 text-center">
        <img src="{{ asset('images/icon3.png') }}" alt="Tiket Selesai" class="mx-auto mb-3">
        <p class="text-2xl font-bold">{{ number_format($tiketSelesai) }}</p>
        <p class="text-gray-600">Tiket Selesai</p>
    </div>
</div>

<!-- Kategori SITIK -->
<div id="kategori" class="container mx-auto my-12 text-center">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Kategori SITIK</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach([
            ['src' => 'frame1.png', 'hover' => 'frame1-hover.png', 'name' => 'Mesin'],
            ['src' => 'frame2.png', 'hover' => 'frame2-hover.png', 'name' => 'Perangkat Lunak'],
            ['src' => 'frame3.png', 'hover' => 'frame3-hover.png', 'name' => 'Perangkat Keras'],
            ['src' => 'frame4.png', 'hover' => 'frame4-hover.png', 'name' => 'Jaringan'],
            ['src' => 'frame5.png', 'hover' => 'frame5-hover.png', 'name' => 'Data'],
            ['src' => 'frame6.png', 'hover' => 'frame6-hover.png', 'name' => 'Support Teknis'],
            ['src' => 'frame7.png', 'hover' => 'frame7-hover.png', 'name' => 'Lainnya']
        ] as $category)
        <div class="bg-white p-4 shadow-lg rounded-lg text-center group relative">
            <img src="{{ asset('images/' . $category['src']) }}" 
                 alt="{{ $category['name'] }}" 
                 class="mx-auto mb-2 group-hover:hidden">
            <img src="{{ asset('images/' . $category['hover']) }}" 
                 alt="{{ $category['name'] }} Hover" 
                 class="mx-auto mb-2 hidden group-hover:block absolute top-4 left-1/2 transform -translate-x-1/2">
            <p class="font-semibold text-gray-700 mt-2">{{ $category['name'] }}</p>
        </div>
        @endforeach
    </div>
</div>


<!-- FAQ Section -->
<div class="container mx-auto text-center bg-gray-100 p-8 rounded-lg">
    <h3 class="text-2xl font-semibold text-gray-800">Butuh Solusi Cepat?</h3>
    <img src="{{ asset('images/icon-question.png') }}" alt="FAQ" class="mx-auto my-4">
    <p class="text-gray-600">Cek FAQ dulu, siapa tahu masalahmu bisa langsung teratasi!</p>
    <a href="{{ route('user.faq') }}" class="mt-4 inline-block bg-black text-white font-bold py-3 px-6 rounded-full">
        Cek FAQ
    </a>
</div>

@push('scripts')
<script>
    document.getElementById('trackTicketForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const ticketId = document.getElementById('ticketIdInput').value;
        if (ticketId) {
            // URL akan menjadi: http://domain.com/user/tickets/51
            const baseUrl = "{{ route('user.tickets.index') }}";
            window.location.href = `${baseUrl}/${ticketId}`;
        } else {
            alert('Silakan masukkan ID tiket Anda.');
        }
    });
</script>
@endpush
@endsection
