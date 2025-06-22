<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Developer Dashboard')</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        /* Optional: Custom scrollbar for better aesthetics */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        /* Custom styles for mobile sidebar slide-in/out */
        .sidebar-mobile-closed {
            transform: translateX(-100%);
        }
        .sidebar-mobile-open {
            transform: translateX(0);
        }
    </style>
</head>
<body class="bg-[#F5F6FA]" x-data="">
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('global', {
                open: window.innerWidth >= 1024,
                isMobile: window.innerWidth < 1024
            });

            window.addEventListener('resize', () => {
                Alpine.store('global').isMobile = window.innerWidth < 1024;
                if (!Alpine.store('global').isMobile) {
                    Alpine.store('global').open = true;
                    document.body.style.overflow = 'auto'; // Ensure scroll is enabled on desktop
                } else {
                    Alpine.store('global').open = false; // Always close sidebar on mobile resize
                    document.body.style.overflow = 'auto'; // Ensure scroll is auto on mobile resize
                }
            });

            // Handle initial state for body overflow on page load
            if (Alpine.store('global').isMobile && Alpine.store('global').open) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }

            // Watch for changes in sidebar open state on mobile to control body overflow
            Alpine.effect(() => {
                if (Alpine.store('global').isMobile) {
                    if (Alpine.store('global').open) {
                        document.body.style.overflow = 'hidden'; // Disable scroll when sidebar is open on mobile
                    } else {
                        document.body.style.overflow = 'auto'; // Enable scroll when sidebar is closed on mobile
                    }
                } else {
                    document.body.style.overflow = 'auto'; // Always auto scroll on desktop
                }
            });
        });
    </script>

    {{-- Overlay untuk mobile --}}
    <div x-show="$store.global.open && $store.global.isMobile"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.global.open = false"
         class="fixed inset-0 bg-black bg-opacity-30 z-30 lg:hidden">
    </div>

    {{-- Sidebar Container --}}
    <div class="fixed top-0 left-0 h-full bg-white shadow-lg z-40 transition-transform duration-300 ease-in-out"
         :class="{
             'w-64': $store.global.open && !$store.global.isMobile, // Desktop open
             'w-20 hidden lg:block': !$store.global.open && !$store.global.isMobile, /* Desktop closed */
             'w-full max-w-[80vw]': $store.global.open && $store.global.isMobile, /* Mobile open - PERUBAHAN DI SINI */
             'w-0': !$store.global.open && $store.global.isMobile, /* Mobile closed */
             'sidebar-mobile-open': $store.global.open && $store.global.isMobile, /* Mobile open - slide in */
             'sidebar-mobile-closed': !$store.global.open && $store.global.isMobile /* Mobile closed - slide out */
         }"
         x-show="$store.global.open || !$store.global.isMobile"
         @click.outside="$store.global.isMobile && $store.global.open ? $store.global.open = false : null">

        <button @click="$store.global.open = !$store.global.open"
                class="absolute -right-3 top-6 bg-white rounded-full p-2 shadow-md border border-gray-200 hover:bg-gray-100 z-10 hidden lg:block">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      :d="$store.global.open ? 'M15 19l-7-7-7-7' : 'M9 5l7 7-7 7'" />
            </svg>
        </button>

        @include('components.sidebar')
    </div>

    {{-- Main Content Area --}}
    <div :class="{
             'lg:ml-64': $store.global.open && !$store.global.isMobile,
             'lg:ml-20': !$store.global.open && !$store.global.isMobile,
             'ml-0': $store.global.isMobile && !$store.global.open
         }"
         class="flex-1 flex flex-col transition-all duration-300 ease-in-out">
        <nav class="h-16 bg-white shadow flex items-center justify-between px-6">
            <button @click="$store.global.open = !$store.global.open" class="lg:hidden p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div x-data="{ dropdownOpen: false }" class="relative ml-auto">
                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3 focus:outline-none">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 capitalize">{{ optional(Auth::user()->role)->name ?? 'User' }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 border">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff" alt="User Icon" class="object-cover w-full h-full">
                    </div>
                </button>

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

                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
        </nav>

        <main class="p-6 min-h-[calc(100vh-64px)]">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoutForm = document.querySelector('.logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const userConfirmed = confirm('Apakah Anda yakin ingin logout?');
                    if (userConfirmed) {
                        this.submit();
                    }
                });
            }
        });
    </script>
</body>
</html>