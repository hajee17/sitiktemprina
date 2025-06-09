<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Developer Dashboard')</title>
    
    {{-- Memuat CSS dari Vite --}}
    @vite('resources/css/app.css')
    
    {{-- Memuat Alpine.js untuk interaktivitas sidebar --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#F5F6FA]" x-data="{ open: true }">

    <aside class="fixed top-0 left-0 h-full bg-white shadow-lg z-50 transition-all duration-300 ease-in-out"
         :class="open ? 'w-64' : 'w-20'">
        
        <button @click="open = !open" 
                class="absolute -right-3 top-6 bg-white rounded-full p-1.5 shadow border border-gray-200 hover:bg-gray-100 z-10 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        {{-- Memasukkan komponen sidebar yang sudah kita perbaiki --}}
        @include('components.sidebar')
    </aside>

    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-30 z-40 lg:hidden" @click="open = false"></div>

    <nav :class="open ? 'lg:ml-64' : 'lg:ml-20'" 
         class="fixed top-0 right-0 left-0 h-16 bg-white shadow flex items-center justify-end px-6 transition-all duration-300 z-30">
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                {{-- PENYESUAIAN: Menggunakan properti 'name' dari relasi 'role' --}}
                <div class="text-xs text-gray-500 capitalize">{{ Auth::user()->role->name ?? 'User' }}</div>
            </div>
            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200">
                <img src="{{ asset('images/icon-user.png') }}" alt="User Icon" class="object-cover w-full h-full">
            </div>
        </div>
    </nav>

    <main :class="open ? 'lg:ml-64' : 'lg:ml-20'" 
          class="pt-20 p-6 min-h-screen transition-all duration-300">
        @yield('content')
    </main>

 
    @stack('scripts')
</body>
</html>