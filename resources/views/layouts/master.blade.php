<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>@yield('title', 'Temprina SITIK')</title>
    <style>
        body {
            font-family: 'SF Pro', sans-serif;
            margin: 0;
            background-color: #F3F2F2;
        }

        /* Header */
        header {
            background-color: #000;
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            height: 40px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav a {
            text-decoration: none;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        /* Container Utama */
        .container {
            background-color: white;
            border-radius: 32px 32px 0 0;
            padding: 40px;
            box-shadow: 0 4px 5.5px rgba(0, 0, 0, 0.2);
            margin: 20px auto;
            width: 90%;
            max-width: 1200px;
        }

        /* Footer */
        footer {
            background-color: black;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
    @stack('styles')
</head>
<body>

    <header class="bg-black text-white py-4 px-6 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/Logo1.png') }}" alt="Logo Temprina SITIK" class="h-10">
        </a>
    
        <!-- Navigasi -->
        <nav>
            <ul class="flex items-center space-x-6 text-lg font-semibold">
                <li><a href="{{ route('dashboard') }}#kategori" class="hover:text-gray-300">Kategori</a></li>
                <li><a href="{{ url('/faq') }}" class="hover:text-gray-300">FAQ</a></li>
    
                @auth
                    <li><a href="{{ route('tickets.index') }}" class="hover:text-gray-300">Tiket Saya</a></li>
                    <li><a href="{{ url('/myaccount') }}" class="hover:text-gray-300">Akun Saya</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-gray-300">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ url('/login') }}" class="hover:text-gray-300">Login</a></li>
                    <li><a href="{{ url('/register') }}" class="hover:text-gray-300">Register</a></li>
                @endauth
            </ul>
        </nav>
    </header>

<main>
    @yield('content')
</main>

<footer>
    ©2025 All Rights Reserved by PT. Temprina Media Grafika
</footer>

@stack('scripts')
</body>
</html>
