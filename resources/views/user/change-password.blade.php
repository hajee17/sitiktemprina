@extends('layouts.master')

@section('title', 'Ubah Kata Sandi')

@section('content')

<div class="w-full min-h-full bg-[#F3F2F2] py-8 px-4 flex justify-center items-start md:items-center">

    <div class="w-full max-w-md bg-white p-6 sm:p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Ubah Kata Sandi</h2>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 p-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                <ul class="list-disc list-inside text-left text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('user.password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-left text-sm font-semibold mb-1.5 text-gray-700">Kata Sandi Saat Ini</label>
                <div class="relative">
                    <input type="password" id="current_password" name="current_password" required
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <button type="button" onclick="togglePasswordVisibility('current_password', 'iconCurrentPassword')"
                            class="absolute right-0 top-0 h-full w-12 flex items-center justify-center text-gray-500 hover:text-gray-800" tabindex="-1">
                        <span id="iconCurrentPassword">{!! '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>' !!}</span>
                    </button>
                </div>
            </div>

            <div>
                <label for="password" class="block text-left text-sm font-semibold mb-1.5 text-gray-700">Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <button type="button" onclick="togglePasswordVisibility('password', 'iconNewPassword')"
                            class="absolute right-0 top-0 h-full w-12 flex items-center justify-center text-gray-500 hover:text-gray-800" tabindex="-1">
                        <span id="iconNewPassword">{!! '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>' !!}</span>
                    </button>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-left text-sm font-semibold mb-1.5 text-gray-700">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'iconConfirmNewPassword')"
                            class="absolute right-0 top-0 h-full w-12 flex items-center justify-center text-gray-500 hover:text-gray-800" tabindex="-1">
                        <span id="iconConfirmNewPassword">{!! '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>' !!}</span>
                    </button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row-reverse sm:justify-start gap-3 pt-4">
                <button type="submit" class="w-full sm:w-auto inline-block bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                    Simpan Perubahan
                </button>
                <a href="{{ route('user.account') }}" class="w-full sm:w-auto inline-block bg-gray-200 text-gray-800 text-center px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

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

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.innerHTML = eyeSlashSvg; 
        } else {
            passwordInput.type = "password";
            icon.innerHTML = eyeSvg; 
        }
    }
</script>
@endsection