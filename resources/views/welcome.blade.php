@extends('layouts.master')

@section('title', 'Selamat Datang di Portal Bantuan SITIK')

@section('content')

{{-- 1. Hero Section --}}
<div class="bg-white">
    <div class="container mx-auto px-6 py-16 md:py-24">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            
            {{-- Kolom Teks --}}
            <div class="lg:w-1/2 text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                    Layanan Bantuan Terintegrasi
                    <span class="block text-blue-600">Untuk Seluruh Kebutuhan Anda</span>
                </h1>
                <p class="mt-6 text-lg text-gray-600">
                    Laporkan kendala teknis, ajukan permintaan, dan lacak status penyelesaian masalah Anda dengan mudah melalui Sistem Informasi Ticketing (SITIK).
                </p>
                {{--Tombol mengarah ke Login dan Register --}}
                <div class="mt-8 flex justify-center lg:justify-start gap-4">
                    <a href="{{ route('login') }}" class="inline-block bg-black text-white font-bold text-lg py-3 px-8 rounded-full hover:bg-gray-800 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-block bg-white text-black border-2 border-black font-bold text-lg py-3 px-8 rounded-full hover:bg-gray-100 transition-colors">
                        Daftar Sekarang
                    </a>
                </div>
            </div>

            {{-- Kolom Gambar --}}
            <div class="lg:w-1/2 mt-10 lg:mt-0">
                <img src="{{ asset('images/hero-illustration.svg') }}" alt="Ilustrasi Tim Helpdesk" class="w-full h-auto">
            </div>

        </div>
    </div>
</div>

{{-- 2. Fitur Utama Section (Tidak ada perubahan, bagian ini bagus untuk informasi) --}}
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800">Solusi dalam Genggaman Anda</h2>
            <p class="text-gray-600 mt-2">Tiga langkah mudah untuk menyelesaikan setiap kendala.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            {{-- Fitur 1 --}}
            <div class="text-center p-6">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Pelaporan Mudah</h3>
                <p class="text-gray-500">Isi formulir tiket yang sederhana dan intuitif untuk melaporkan masalah Anda dalam hitungan menit.</p>
            </div>
            {{-- Fitur 2 --}}
            <div class="text-center p-6">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Pelacakan Real-time</h3>
                <p class="text-gray-500">Pantau status tiket Anda kapan saja, mulai dari verifikasi hingga penyelesaian, secara transparan.</p>
            </div>
            {{-- Fitur 3 --}}
            <div class="text-center p-6">
                <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Knowledge Base</h3>
                <p class="text-gray-500">Temukan solusi mandiri untuk masalah umum melalui kumpulan artikel dan panduan yang kami sediakan.</p>
            </div>
        </div>
    </div>
</div>

{{-- 3. Final Call to Action Section --}}
<div class="bg-white py-16">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Siap Menyelesaikan Masalah Anda?</h2>
        <p class="text-gray-600 mt-2 mb-8">Jangan biarkan kendala teknis menghambat produktivitas Anda. Tim kami siap membantu.</p>
        {{-- PERBAIKAN: Tombol mengarah ke Register --}}
        <a href="{{ route('register') }}" class="inline-block bg-black text-white font-bold text-lg py-3 px-8 rounded-full hover:bg-gray-800 transition-colors">
            Gabung Sekarang
        </a>
    </div>
</div>
@endsection
