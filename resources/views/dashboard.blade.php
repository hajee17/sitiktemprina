@extends('layouts.master')

@section('title', 'Dashboard - Temprina SITIK')

@section('content')

<!-- Hero Section -->
<div class="container mx-auto my-12" style="background-image: url('{{ asset('images/frame21.png') }}');">
    <div class="max-w-lg">
        <h2 class="text-3xl font-bold text-gray-800">Selamat Datang di SITIK!</h2>
        <p class="text-lg text-gray-700">Ada yang perlu kami bantu? Buat tiket baru sekarang.</p>
        <a href="#" class="mt-4 inline-block bg-black text-white font-bold py-3 px-6 rounded-full">Buat Tiket Baru</a>
    </div>
</div>

<!-- Status Tiket Section -->
<div class="container mx-auto text-center my-12">
    <h3 class="text-2xl font-semibold text-gray-800">Cek Status Tiket Anda</h3>
    <p class="text-gray-600 mb-4">Masukkan nomor tiket untuk melacak tiket Anda!</p>
    <div class="flex justify-center">
        <input type="text" placeholder="Contoh : LGA123456" class="w-2/5 p-3 border rounded-l-lg focus:outline-none">
        <a href="{{ url('lacakticket') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-r-lg">
            Lacak Tiket
        </a>
    </div>
</div>

<!-- Statistik Tiket -->
<div class="container mx-auto flex flex-wrap justify-center gap-6">
    <div class="bg-white p-6 shadow-lg rounded-lg w-64 text-center">
        <img src="{{ asset('images/icon1.png') }}" alt="Total Tiket" class="mx-auto mb-3">
        <p class="text-2xl font-bold">999.999.999</p>
        <p class="text-gray-600">Total Tiket Dibuat</p>
    </div>
    <div class="bg-white p-6 shadow-lg rounded-lg w-64 text-center">
        <img src="{{ asset('images/icon2.png') }}" alt="Tiket Diproses" class="mx-auto mb-3">
        <p class="text-2xl font-bold">999.999.999</p>
        <p class="text-gray-600">Tiket Diproses</p>
    </div>
    <div class="bg-white p-6 shadow-lg rounded-lg w-64 text-center">
        <img src="{{ asset('images/icon3.png') }}" alt="Tiket Selesai" class="mx-auto mb-3">
        <p class="text-2xl font-bold">999.999.999</p>
        <p class="text-gray-600">Tiket Selesai</p>
    </div>
</div>

<!-- Kategori SITIK -->
<div id="kategori" class="container mx-auto my-12 text-center">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Kategori SITIK</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach([
            ['src' => 'frame1.png', 'name' => 'Mesin'],
            ['src' => 'frame2.png', 'name' => 'Perangkat Lunak'],
            ['src' => 'frame3.png', 'name' => 'Perangkat Keras'],
            ['src' => 'frame4.png', 'name' => 'Jaringan'],
            ['src' => 'frame5.png', 'name' => 'Data'],
            ['src' => 'frame6.png', 'name' => 'Support Teknis'],
            ['src' => 'frame7.png', 'name' => 'Lainnya']
        ] as $category)
        <div class="bg-white p-4 shadow-lg rounded-lg text-center">
            <img src="{{ asset('images/' . $category['src']) }}" alt="{{ $category['name'] }}" class="mx-auto mb-2">
            <p class="font-semibold text-gray-700">{{ $category['name'] }}</p>
        </div>
        @endforeach
    </div>
</div>

<!-- FAQ Section -->
<div class="container mx-auto text-center bg-gray-100 p-8 rounded-lg">
    <h3 class="text-2xl font-semibold text-gray-800">Butuh Solusi Cepat?</h3>
    <img src="{{ asset('images/icon-question.png') }}" alt="FAQ" class="mx-auto my-4">
    <p class="text-gray-600">Cek FAQ dulu, siapa tahu masalahmu bisa langsung teratasi!</p>
    <a href="{{ url('/faq') }}" class="mt-4 inline-block bg-black text-white font-bold py-3 px-6 rounded-full">
        Cek FAQ
    </a>
</div>

@endsection
