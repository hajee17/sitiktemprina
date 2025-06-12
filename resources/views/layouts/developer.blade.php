<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Developer Dashboard')</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#F5F6FA]" x-data="{ open: true }">

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-full bg-white shadow-lg z-40 transition-all duration-300 ease-in-out"
         :class="open ? 'w-64' : 'w-20'">
         
        <!-- Tombol Toggle Sidebar -->
        <button @click="open = !open" 
                class="absolute -right-3 top-6 bg-white rounded-full p-2 shadow-md border border-gray-200 hover:bg-gray-100 z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      :d="open ? 'M15 19l-7-7-7-7' : 'M9 5l7 7-7 7'" />
            </svg>
        </button>

        @include('components.sidebar')
    </div>

    <!-- Overlay untuk mobile saat sidebar terbuka -->
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-30 z-30 lg:hidden" @click="open = false"></div>

    <!-- Konten Utama dan Navbar -->
    <div :class="open ? 'lg:ml-64' : 'lg:ml-20'" class="transition-all duration-300">
        <!-- Navbar -->
        <nav class="h-16 bg-white shadow flex items-center justify-end px-6">
            {{-- PERBAIKAN: Menambahkan dropdown profil --}}
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3 focus:outline-none">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 capitalize">{{ optional(Auth::user()->role)->name ?? 'User' }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 border">
                        {{-- Menggunakan UI Avatars untuk gambar profil dinamis --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff" alt="User Icon" class="object-cover w-full h-full">
                    </div>
                </button>
        
                <!-- Menu Dropdown -->
                <div x-show="dropdownOpen" 
                     @click.away="dropdownOpen = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     style="display: none;">
                    
                    {{-- @todo: Buat rute dan halaman untuk profil developer --}}
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profile Saya
                    </a>
                    
                    <div class="border-t border-gray-100"></div>
        
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="p-6 min-h-[calc(100vh-64px)]">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
