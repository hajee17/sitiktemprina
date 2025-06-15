<div class="h-full flex flex-col overflow-hidden">
    <!-- Logo -->
    <div class="p-4 border-b flex items-center" :class="open ? 'justify-start' : 'justify-center'">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8" :class="open ? 'block' : 'hidden'">
        <span class="ml-2 font-bold text-lg" x-show="open">DevPanel</span>
        <img src="{{ asset('images/logo-icon.png') }}" alt="Logo" class="h-8" :class="open ? 'hidden' : 'block'">
    </div>

    <!-- Menu Items -->
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
                       :class="open ? 'justify-start gap-3' : 'justify-center'">
                        <span class="text-xl">{{ $item['icon'] }}</span>
                        <span x-show="open" class="whitespace-nowrap">{{ $item['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Admin Panel -->
        <div class="mt-6">
            <h3 class="text-xs font-semibold uppercase text-gray-500 mb-2" x-show="open">Admin Panel</h3>
            <ul class="space-y-2">
                @php
                    $adminItems = [
                        // PERBAIKAN: Menyesuaikan nama rute untuk resource
                        ['name' => 'Kelola Akun', 'icon' => '👤', 'route' => 'developer.akun.index'],
                        ['name' => 'Kelola Tiket', 'icon' => '�', 'route' => 'developer.kelola-ticket'],
                    ];
                @endphp

                @foreach($adminItems as $item)
                    <li>
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center rounded-lg p-3 transition-colors duration-200
                                  {{ request()->routeIs($item['route'].'*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100 text-gray-700' }}"
                           :class="open ? 'justify-start gap-3' : 'justify-center'">
                            <span class="text-xl">{{ $item['icon'] }}</span>
                            <span x-show="open" class="whitespace-nowrap">{{ $item['name'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
</div>
�