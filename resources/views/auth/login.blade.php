@extends('layouts.master')

@section('content')

<div class="relative w-full min-h-screen bg-white flex justify-center items-center p-4">
    <div class="w-full max-w-6xl bg-[#ECEBE4] border-4 border-black rounded-[40px] flex flex-col lg:flex-row overflow-hidden shadow-lg">
        <div class="hidden lg:block lg:w-1/2 bg-cover bg-center" style="background-image: url('{{ asset('images/login-image.png') }}')"></div>
        <div class="w-full lg:w-1/2 bg-white p-8 sm:p-12 flex flex-col justify-center">
            <div class="text-right mb-12">
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-block bg-black text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-gray-800 transition-colors">Daftar</a>
                @endif
            </div>
            
            <div class="text-left mb-6">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                <h2 class="m-0 text-2xl font-bold">Selamat Datang Kembali!</h2>
                <p class="text-gray-500 mt-2">Masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
                @csrf

                <div>
                    <label for="email" class="block font-semibold mb-1.5">Email</label>
                    <input type="email" id="email" name="email" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-black focus:outline-none @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email Anda"
                        value="{{ old('email', session('email')) }}" 
                        required 
                        autocomplete="email" 
                        autofocus>
                    @error('email')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block font-semibold mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="loginPassword" name="password"
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:border-black focus:outline-none @error('password') border-red-500 @enderror"
                            placeholder="Masukkan kata sandi"
                            required autocomplete="current-password">

                        <button type="button" onclick="togglePasswordVisibility('loginPassword', 'iconLogin')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-black" tabindex="-1">
                            <span id="iconLogin"></span>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Ingat Saya</label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-black hover:text-gray-600 font-semibold">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-black text-white py-3.5 rounded-lg font-medium hover:bg-gray-800 transition-colors mt-3">
                    Masuk
                </button>

                <div class="relative flex items-center my-2">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="flex-shrink mx-4 text-gray-500 text-sm">atau masuk dengan</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                @if(config('services.google.client_id'))
                    <a href="{{ route('login.google') }}" class="block">
                        <button type="button" class="w-full bg-white border border-gray-300 py-2.5 rounded-lg font-medium flex justify-center items-center gap-3 hover:border-gray-400 transition-colors">
                            <img src="{{ asset('images/logo-google.png') }}" alt="Google" class="h-5">
                            Masuk dengan Google
                        </button>
                    </a>
                @endif

                @error('system')
                    <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm text-center">
                        {{ $message }}
                    </div>
                @enderror
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        const eyeSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>`;
        const eyeSlashSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.967 9.967 0 012.284-3.568m3.268-2.412A9.956 9.956 0 0112 5c4.477 0 8.267 2.943 9.542 7a9.961 9.961 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>`;

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.innerHTML = eyeSlashSvg;
        } else {
            passwordInput.type = "password";
            icon.innerHTML = eyeSvg;
        }
    }

    document.getElementById('iconLogin').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>`;
</script>
@endpush