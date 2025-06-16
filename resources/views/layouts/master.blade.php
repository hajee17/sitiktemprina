<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>@yield('title', 'Temprina SITIK')</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans flex flex-col min-h-screen">

    <header class="bg-black text-white shadow-md">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <a href="{{ Auth::check() ? route('dashboard') : route('welcome') }}">
                        <img src="{{ asset('images/Logo1.png') }}" alt="Logo Temprina SITIK" class="h-10">
                    </a>
                </div>

                <div class="flex items-center">
                    <nav class="hidden lg:flex lg:items-center lg:space-x-6">
                        <ul class="flex items-center space-x-6 text-lg font-semibold">
                            @auth
                                <li><a href="{{ route('user.dashboard') }}" class="hover:text-gray-300">Dashboard</a></li>
                                <li><a href="{{ route('user.tickets.index') }}" class="hover:text-gray-300">Tiket Saya</a></li>
                                <li><a href="{{ route('user.knowledgebase.index') }}" class="hover:text-gray-300">Knowledge Base</a></li>
                                <li><a href="{{ route('user.faq') }}" class="hover:text-gray-300">FAQ</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="hover:text-gray-300">Login</a></li>
                                <li><a href="{{ route('register') }}" class="hover:text-gray-300">Register</a></li>
                            @endauth
                        </ul>
                    </nav>

                    @auth
                    <div x-data="{ dropdownOpen: false }" class="relative ml-6">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3 focus:outline-none">
                            <div class="text-right hidden md:block">
                                <div class="text-sm font-semibold text-white">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-400 capitalize">User</div>
                            </div>
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-yellow-400 border-2 border-white flex items-center justify-center">
                                <span class="text-black font-bold text-lg">
                                    @php
                                        $nameParts = explode(' ', Auth::user()->name);
                                        $initials = count($nameParts) > 1
                                            ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
                                            : strtoupper(substr($nameParts[0], 0, 2));
                                    @endphp
                                    {{ $initials }}
                                </span>
                            </div>
                        </button>

                        <div x-show="dropdownOpen"
                             @click.away="dropdownOpen = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border text-black"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             style="display: none;">

                            <a href="{{ route('user.account') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile Saya
                            </a>

                            <div class="border-t border-gray-100"></div>

                            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth

                    <div class="lg:hidden flex items-center ml-4">
                        <button id="hamburger-button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-300 hover:bg-gray-800 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="lg:hidden hidden">
            <nav class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <ul class="flex flex-col space-y-2 text-lg font-semibold text-center">
                     @auth
                        <li><a href="{{ route('user.dashboard') }}" class="block px-3 py-2 rounded-md hover:text-gray-300 hover:bg-gray-800">Dashboard</a></li>
                        <li><a href="{{ route('user.tickets.index') }}" class="block px-3 py-2 rounded-md hover:text-gray-300 hover:bg-gray-800">Tiket Saya</a></li>
                        <li><a href="{{ route('user.knowledgebase.index') }}" class="block px-3 py-2 rounded-md hover:text-gray-300 hover:bg-gray-800">Knowledge Base</a></li>
                        <li><a href="{{ route('user.faq') }}" class="block px-3 py-2 rounded-md hover:text-gray-300 hover:bg-gray-800">FAQ</a></li>
                        <li><a href="{{ route('user.account') }}" class="block px-3 py-2 rounded-md hover:text-gray-300 hover:bg-gray-800">Profile Saya</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="w-full logout-form">
                                @csrf
                                <button type="submit" class="w-full block px-3 py-2 rounded-md hover:text-gray-300 hover:bg-gray-800 bg-transparent border-none text-white text-lg font-semibold cursor-pointer">
                                    Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="block px-3 py-2 rounded-md hover:text-gray-300 hover:bg-gray-800">Login</a></li>
                        <li><a href="{{ route('register') }}" class="block px-3 py-2 rounded-md hover:text-gray-300 hover:bg-gray-800">Register</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <main class="flex-grow">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </div>
    </main>

    <footer class="bg-black text-white text-center p-4 mt-auto">
        ©{{ date('Y') }} All Rights Reserved by PT. Temprina Media Grafika
    </footer>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hamburgerButton = document.getElementById('hamburger-button');
            const mobileMenu = document.getElementById('mobile-menu');

            hamburgerButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });
            const logoutForms = document.querySelectorAll('.logout-form');

            logoutForms.forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault(); 

                    const userConfirmed = confirm('Apakah Anda yakin ingin logout?');
                    if (userConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>