@extends('layouts.master')

@section('title', 'Akun Saya')

@section('content')

<div class="py-8 px-4 flex justify-center items-start md:items-center min-h-full">

    <div class="w-full max-w-xl bg-white rounded-xl shadow-lg text-center overflow-hidden">

        <div class="relative">

            <div class="h-32 w-full bg-cover bg-center" style="background-image: url('{{ asset('images/banner.png') }}')"></div>

            <div class="absolute top-full left-1/2 -translate-x-1/2 -translate-y-1/2">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=100&background=EBF4FF&color=007BFF" 
                     alt="Foto Profil" 
                     class="w-24 h-24 rounded-full border-4 border-white shadow-md">
            </div>
        </div>
        <div class="mt-16 px-6 md:px-10 pb-8 flex flex-col gap-4">
            <div class="text-left">
                <label for="name" class="font-bold text-gray-700 mb-1 block">Nama Lengkap</label>
                <input type="text" id="name" value="{{ Auth::user()->name }}" disabled 
                       class="w-full p-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
            </div>

            <div class="text-left">
                <label for="email" class="font-bold text-gray-700 mb-1 block">Email</label>
                <input type="email" id="email" value="{{ Auth::user()->email }}" disabled 
                       class="w-full p-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
            </div>
            <div class="text-left">
                <label for="phone" class="font-bold text-gray-700 mb-1 block">Nomor Telepon</label>
                <input type="text" id="phone" value="{{ Auth::user()->phone ?? 'Belum diisi' }}" disabled 
                       class="w-full p-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
            </div>
            
            <div class="pt-2"></div>

            <a href="{{ route('user.password.form') }}" class="w-full flex justify-between items-center bg-white border border-gray-200 hover:bg-gray-100 transition duration-200 p-3 rounded-lg text-gray-700 no-underline">
                <span class="font-semibold">🔑 Ubah Kata Sandi</span>
                <span class="font-bold text-lg">→</span>
            </a>
            <button class="w-full p-3 border-2 border-red-500 text-red-500 font-bold rounded-lg transition duration-200 hover:bg-red-500 hover:text-white">
                🚨 Hapus Akun
            </button>
        </div>
    </div>
</div>
@endsection