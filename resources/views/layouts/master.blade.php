<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @vite('resources/css/app.css')
    <title>@yield('title', 'Temprina SITIK')</title>
    
    {{-- Anda bisa memindahkan style ini ke file CSS jika diinginkan --}}
    <style> 
        body {
            font-family: 'SF Pro', sans-serif;
            margin: 0;
            background-color: #F3F2F2;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">

    <header class="bg-black text-white py-4 px-6 md:px-10 flex items-center justify-between shadow-lg">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/Logo1.png') }}" alt="Logo Temprina SITIK" class="h-10">
        </a>
    
        <nav>
            <ul class="flex items-center space-x-4 md:space-x-6 text-sm md:text-base font-semibold">
                <li><a href="{{ route('dashboard') }}#kategori" class="hover:text-gray-300 transition">Kategori</a></li>
                
                {{-- PENYESUAIAN: Menggunakan helper route() untuk konsistensi --}}
                <li><a href="{{ route('faq') }}" class="hover:text-gray-300 transition">FAQ</a></li>
    
                @auth
                    <li><a href="{{ route('user.tickets.index') }}" class="hover:text-gray-300 transition">Tiket Saya</a></li>
                    <li><a href="{{ route('kb.index') }}" class="hover:text-gray-300 transition">Knowledge Base</a></li>
                    <li><a href="{{ route('my.account') }}" class="hover:text-gray-300 transition">Akun Saya</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="font-semibold hover:text-gray-300 transition">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="hover:text-gray-300 transition">Login</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-gray-300 transition">Register</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <main class="py-8">
        @yield('content')
    </main>

    <footer class="bg-black text-white text-center p-4 mt-8">
        ©{{ date('Y') }} All Rights Reserved by PT. Temprina Media Grafika
    </footer>

    @stack('scripts')
</body>
</html>