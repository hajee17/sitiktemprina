<div class="h-full flex flex-col overflow-hidden">
    <div class="p-4 border-b flex items-center" :class="$store.global.open ? 'justify-start' : 'justify-center'">
        {{-- Logo full (sembunyikan di mobile saat sidebar terbuka, sembunyikan saat sidebar tertutup di desktop) --}}
        <img src="{{ asset('images/logo-sitik1.png') }}" alt="Logo" class="h-8" 
             :class="$store.global.open && !$store.global.isMobile ? 'block' : 'hidden'">
        <span class="ml-2 font-bold text-lg" x-show="$store.global.open && !$store.global.isMobile">DevPanel</span>

        {{-- Logo icon (selalu terlihat di desktop saat sidebar tertutup, dan di mobile SAAT SIDEBAR TERBUKA atau tertutup) --}}
        {{-- PERUBAHAN DI SINI: ganti kondisi hidden/block untuk logo icon --}}
        <img src="{{ asset('images/logo-sitik1.png') }}" alt="Logo"
             class="object-contain h-8 w-auto"
             :class="!$store.global.open && !$store.global.isMobile ? 'block' : ($store.global.isMobile ? 'block' : 'hidden')">
        {{-- Penjelasan:
             - (!$store.global.open && !$store.global.isMobile): Logo icon muncul saat desktop sidebar tertutup
             - ($store.global.isMobile ? 'block' : 'hidden'): Logo icon SELALU muncul di mobile (baik sidebar terbuka atau tertutup).
               Ini memungkinkan logo tetap ada sedikit bahkan saat mobile sidebar terbuka.
        --}}
        
        {{-- Tombol Close untuk Mobile Sidebar (terlihat hanya di mobile saat sidebar terbuka) --}}
        <button x-show="$store.global.isMobile && $store.global.open" 
                @click="$store.global.open = false" 
                class="absolute top-4 right-4 p-1 text-gray-500 hover:text-gray-700 focus:outline-none z-50"> {{-- Tambahkan z-50 untuk memastikan di atas konten lain --}}
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto p-4">
        <ul class="space-y-2">
            @php
                $navItems = [
                    ['name' => 'Dashboard', 'icon' => '🏠', 'route' => 'developer.dashboard'],
                    ['name' => 'Ambil Tiket', 'icon' => '🎟️', 'route' => 'developer.tickets.index'],
                    ['name' => 'Tiket Saya', 'icon' => '📄', 'route' => 'developer.myticket'],
                    ['name' => 'Knowledge Base', 'icon' => '📚', 'route' => 'developer.knowledgebase.index'],
                    ['name' => 'Kelola Tags', 'icon' => '🏷️', 'route' => 'developer.tags.index'],
                ];
            @endphp

            @foreach($navItems as $item)
                <li>
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center rounded-lg p-3 transition-colors duration-200
                              {{ request()->routeIs($item['route'].'*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100 text-gray-700' }}"
                       :class="$store.global.open ? 'justify-start gap-3' : 'justify-center'">
                        <span class="text-xl">{{ $item['icon'] }}</span>
                        <span x-show="$store.global.open" class="whitespace-nowrap">{{ $item['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-6">
            <h3 class="text-xs font-semibold uppercase text-gray-500 mb-2" x-show="$store.global.open">Admin Panel</h3>
            <ul class="space-y-2">
                @php
                    $adminItems = [
                        ['name' => 'Kelola Akun', 'icon' => '👤', 'route' => 'developer.akun.index'],
                        ['name' => 'Kelola Tiket', 'icon' => '📄', 'route' => 'developer.kelola-ticket'],
                    ];
                @endphp

                @foreach($adminItems as $item)
                    <li>
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center rounded-lg p-3 transition-colors duration-200
                                   {{ request()->routeIs($item['route'].'*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100 text-gray-700' }}"
                           :class="$store.global.open ? 'justify-start gap-3' : 'justify-center'">
                            <span class="text-xl">{{ $item['icon'] }}</span>
                            <span x-show="$store.global.open" class="whitespace-nowrap">{{ $item['name'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
</div>