@extends('layouts.master')

@section('content')
<div class="relative w-full min-h-screen bg-white flex justify-center items-center">
    <div class="w-[1200px] h-[760px] bg-[#ECEBE4] border-4 border-black rounded-[40px] flex overflow-hidden">
        
        <!-- Bagian Kiri (Background Gambar) -->
        <div class="w-1/2 bg-cover bg-center" style="background-image: url('{{ asset('images/login-image.png') }}')"></div>

        <!-- Bagian Kanan (Form Registrasi) -->
        <div class="w-1/2 bg-white p-12 flex flex-col justify-center relative">
            
            <!-- Tombol Masuk -->
            <div class="absolute top-5 right-8">
                <a href="{{ route('login') }}" class="inline-block bg-black text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-gray-800 transition-colors">Masuk</a>
            </div>

            <!-- Judul -->
            <div class="text-left mb-6">
                <h2 class="text-2xl font-bold">Selamat Datang!</h2>
                <p class="text-gray-500 mt-2">Buat akun Anda dan mulai sekarang.</p>
            </div>

            <!-- Form Registrasi -->
            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="Name" class="block font-semibold mb-1.5">Nama Lengkap</label>
                    <input type="text" id="Name" name="Name" placeholder="Masukkan nama lengkap"
                        class="w-full px-4 py-3 border border-gray-300 rounded focus:border-black focus:outline-none @error('Name') border-red-500 @enderror"
                        value="{{ old('Name') }}" required autofocus>
                    @error('Name')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="Email" class="block font-semibold mb-1.5">Email</label>
                    <input type="email" id="Email" name="Email" placeholder="Masukkan email"
                        class="w-full px-4 py-3 border border-gray-300 rounded focus:border-black focus:outline-none @error('Email') border-red-500 @enderror"
                        value="{{ old('Email') }}" required>
                    @error('Email')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="Telp_Num" class="block font-semibold mb-1.5">Nomor Telepon</label>
                    <input type="text" id="Telp_Num" name="Telp_Num" placeholder="Masukkan nomor telepon"
                        class="w-full px-4 py-3 border border-gray-300 rounded focus:border-black focus:outline-none @error('Telp_Num') border-red-500 @enderror"
                        value="{{ old('Telp_Num') }}" required>
                    @error('Telp_Num')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="registerPassword" class="block font-semibold mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="registerPassword" name="password"
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded focus:border-black focus:outline-none @error('password') border-red-500 @enderror"
                            placeholder="Masukkan kata sandi (minimal 8 karakter)" required>

                        <button type="button" onclick="togglePasswordVisibility('registerPassword', 'iconRegister')" 
                            class="absolute right-3 top-3 text-gray-700 hover:text-black" tabindex="-1">
                            <span id="iconRegister">{!! '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>' !!}</span>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="confirmPassword" class="block font-semibold mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="confirmPassword" name="password_confirmation"
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded focus:border-black focus:outline-none"
                            placeholder="Masukkan ulang kata sandi" required>

                        <button type="button" onclick="togglePasswordVisibility('confirmPassword', 'iconConfirm')" 
                            class="absolute right-3 top-3 text-gray-700 hover:text-black" tabindex="-1">
                            <span id="iconConfirm">{!! '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>' !!}</span>
                        </button>
                    </div>
                </div>

                <!-- Role Hidden -->
                <input type="hidden" name="ID_Role" value="2">

                <!-- Terms -->
                <div class="flex items-start gap-2">
                    <input type="checkbox" id="terms" name="terms" class="mt-1" required>
                    <label for="terms" class="text-sm">Saya menyetujui <a href="#" class="text-blue-600 hover:underline">Syarat Penggunaan</a> dan <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a></label>
                </div>
                @error('terms')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-black text-white py-3.5 rounded font-medium hover:bg-gray-800 transition-colors">
                    Buat Akun
                </button>

                <!-- Divider -->
                <div class="relative flex items-center my-2">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="flex-shrink mx-4 text-gray-500 text-sm">atau daftar dengan</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <!-- Google Register -->
                @if(config('services.google.client_id'))
                    <a href="{{ route('register.google') }}" class="block">
                        <button type="button" class="w-full bg-white border border-gray-300 py-2.5 rounded font-medium flex justify-center items-center gap-3 hover:border-gray-400 transition-colors">
                            <img src="{{ asset('images/logo-google.png') }}" alt="Google" class="h-5">
                            Daftar dengan Google
                        </button>
                    </a>
                @endif
            </form>

            <!-- Global Error -->
            @if($errors->any())
                <div class="mt-5 text-red-600 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.innerHTML = eyeSlashSvg; // Show eye-slash
            } else {
                passwordInput.type = "password";
                icon.innerHTML = eyeSvg; // Show eye
            }
        }

        const eyeSvg = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>`;

        const eyeSlashSvg = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.967 9.967 0 012.284-3.568m3.268-2.412A9.956 9.956 0 0112 5c4.477 0 8.267 2.943 9.542 7a9.961 9.961 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3l18 18" />
            </svg>`;
    </script>

</div>
@endsection
