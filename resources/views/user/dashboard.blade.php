@extends('layouts.master')

@section('title', 'Dashboard - Temprina SITIK')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-cover bg-center bg-no-repeat rounded-xl p-8 md:p-12"
         style="background-image: url('{{ asset('images/frame21.png') }}');">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Selamat Datang di SITIK!</h2>
        <p class="text-lg text-gray-700 mb-6">Ada yang perlu kami bantu? Buat tiket baru sekarang.</p>
        {{-- PENYESUAIAN: Menggunakan helper route() --}}
        <a href="{{ route('user.tickets.create') }}" class="inline-block bg-black text-white font-bold py-3 px-6 rounded-full hover:bg-gray-800 transition">
            Buat Tiket Baru
        </a>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center my-12">
    <h3 class="text-2xl font-semibold text-gray-800">Cek Status Tiket Anda</h3>
    <p class="text-gray-600 mb-4">Masukkan nomor tiket untuk melacak tiket Anda!</p>
    <div class="flex justify-center max-w-xl mx-auto">
        {{-- PENYESUAIAN: Form untuk Lacak Tiket --}}
        <form action="{{ route('tickets.track') }}" method="GET" class="w-full flex">
            <input type="text" name="ticket_id" placeholder="Contoh : 123" class="w-full p-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-1 focus:ring-blue-500" required>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-r-lg whitespace-nowrap">
                Lacak Tiket
            </button>
        </form>
    </div>
    @if(session('error'))
    <p class="text-red-500 text-sm mt-2">{{ session('error') }}</p>
    @endif
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap justify-center gap-6">
    <div class="bg-white p-6 shadow-lg rounded-lg w-64 text-center">
        <img src="{{ asset('images/icon1.png') }}" alt="Total Tiket" class="mx-auto mb-3 h-16">
        <p class="text-3xl font-bold">{{ number_format($totalTiket) }}</p>
        <p class="text-gray-600">Total Tiket Dibuat</p>
    </div>
    <div class="bg-white p-6 shadow-lg rounded-lg w-64 text-center">
        <img src="{{ asset('images/icon2.png') }}" alt="Tiket Diproses" class="mx-auto mb-3 h-16">
        <p class="text-3xl font-bold">{{ number_format($tiketDiproses) }}</p>
        <p class="text-gray-600">Tiket Diproses</p>
    </div>
    <div class="bg-white p-6 shadow-lg rounded-lg w-64 text-center">
        <img src="{{ asset('images/icon3.png') }}" alt="Tiket Selesai" class="mx-auto mb-3 h-16">
        <p class="text-3xl font-bold">{{ number_format($tiketSelesai) }}</p>
        <p class="text-gray-600">Tiket Selesai</p>
    </div>
</div>

<div id="kategori" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-12 text-center">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Kategori SITIK</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-6">
        {{-- PENYESUAIAN: Kategori dinamis dari database --}}
        @php
            // Pemetaan sederhana nama kategori ke gambar. Solusi terbaik adalah menyimpan path gambar di database.
            $categoryImages = [
                'Mesin' => ['src' => 'frame1.png', 'hover' => 'frame1-hover.png'],
                'Perangkat Lunak' => ['src' => 'frame2.png', 'hover' => 'frame2-hover.png'],
                'Perangkat Keras' => ['src' => 'frame3.png', 'hover' => 'frame3-hover.png'],
                'Jaringan' => ['src' => 'frame4.png', 'hover' => 'frame4-hover.png'],
                'Data' => ['src' => 'frame5.png', 'hover' => 'frame5-hover.png'],
                'Support Teknis' => ['src' => 'frame6.png', 'hover' => 'frame6-hover.png'],
                'Lainnya' => ['src' => 'frame7.png', 'hover' => 'frame7-hover.png'],
            ];
        @endphp
        @foreach($categories as $category)
            <a href="{{ route('user.tickets.create') }}?category={{ $category->id }}" class="block bg-white p-4 shadow-lg rounded-lg text-center group relative hover:-translate-y-2 transition-transform">
                <img src="{{ asset('images/' . ($categoryImages[$category->name]['src'] ?? 'default.png')) }}" alt="{{ $category->name }}" class="mx-auto mb-2 h-20 group-hover:hidden">
                <img src="{{ asset('images/' . ($categoryImages[$category->name]['hover'] ?? 'default-hover.png')) }}" alt="{{ $category->name }} Hover" class="mx-auto mb-2 h-20 hidden group-hover:block absolute top-4 left-1/2 transform -translate-x-1/2">
                <p class="font-semibold text-gray-700 mt-2">{{ $category->name }}</p>
            </a>
        @endforeach
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center bg-gray-100 p-8 rounded-lg">
        <h3 class="text-2xl font-semibold text-gray-800">Butuh Solusi Cepat?</h3>
        <img src="{{ asset('images/icon-question.png') }}" alt="FAQ" class="mx-auto my-4 h-20">
        <p class="text-gray-600">Cek FAQ dulu, siapa tahu masalahmu bisa langsung teratasi!</p>
        <a href="{{ route('faq') }}" class="mt-4 inline-block bg-black text-white font-bold py-3 px-6 rounded-full hover:bg-gray-800 transition">
            Cek FAQ
        </a>
    </div>
</div>

@endsection