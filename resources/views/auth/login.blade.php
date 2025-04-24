@extends('layouts.master')

@section('content')
<div class="relative w-full min-h-screen bg-white flex justify-center items-center">
    <div class="w-[1200px] h-[660px] bg-[#ECEBE4] border-4 border-black rounded-[40px] flex overflow-hidden">
        <!-- Bagian Kiri (Background Gambar) -->
        <div class="w-1/2 bg-cover bg-center" style="background-image: url('{{ asset('images/login-image.png') }}')"></div>

        <!-- Bagian Kanan (Form Login) -->
        <div class="w-1/2 bg-white p-12 flex flex-col justify-center">
            <!-- Tombol Daftar -->
            <div class="text-right mb-12">
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-block bg-black text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-gray-800 transition-colors">Daftar</a>
                @endif
            </div>
            
            <!-- Judul -->
            <div class="text-left mb-6">
                <h2 class="m-0 text-2xl font-bold">Selamat Datang Kembali!</h2>
                <p class="text-gray-500 mt-2">Masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <!-- Form Login -->
            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
                @csrf

            <!-- Email Input -->
            <div>
                <label for="email" class="block font-semibold mb-1.5">Email</label>
                <input type="email" id="email" name="email" 
                    class="w-full px-4 py-3 border border-gray-300 rounded focus:border-black focus:outline-none @error('email') border-red-500 @enderror"
                    placeholder="Masukkan email Anda"
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="email" 
                    autofocus>
                @error('email')
                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block font-semibold mb-1.5">Kata Sandi</label>
                <input type="password" id="password" name="password" 
                    class="w-full px-4 py-3 border border-gray-300 rounded focus:border-black focus:outline-none @error('password') border-red-500 @enderror"
                    placeholder="Masukkan kata sandi"
                    required 
                    autocomplete="current-password">
                @error('password')
                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="ml-2">Ingat Saya</label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-black hover:text-gray-600 font-medium">Lupa Password?</a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-black text-white py-3.5 rounded font-medium hover:bg-gray-800 transition-colors mt-3">
                    Masuk
                </button>

                <!-- Divider -->
                <div class="relative flex items-center my-4">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="flex-shrink mx-4 text-gray-500">atau masuk dengan</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <!-- Google Login Button -->
                @if(config('services.google.client_id'))
                    <a href="{{ route('login.google') }}" class="block">
                        <button type="button" class="w-full bg-white border border-gray-300 py-2.5 rounded font-medium flex justify-center items-center gap-3 hover:border-gray-400 transition-colors">
                            <img src="{{ asset('images/logo-google.png') }}" alt="Google" class="h-5">
                            Masuk dengan Google
                        </button>
                    </a>
                @endif

                <!-- System Error Messages -->
                @error('system')
                    <div class="mt-4 p-3 bg-red-100 text-red-700 rounded text-sm text-center">
                        {{ $message }}
                    </div>
                @enderror
            </form>
        </div>
    </div>
</div>
@endsection