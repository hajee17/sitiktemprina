<div class="h-full flex flex-col overflow-hidden bg-white border-r">
    <div class="p-4 border-b flex items-center" :class="open ? 'justify-start' : 'justify-center'">
        <a href="{{ route('developer.dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8" :class="open ? 'block' : 'hidden'">
        </a>
        <span class="ml-3 font-bold text-lg" x-show="open">DevPanel</span>
        <a href="{{ route('developer.dashboard') }}" :class="open ? 'hidden' : 'block'">
            <img src="{{ asset('images/logo-icon.png') }}" alt="Logo Icon" class="h-8">
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto p-4">
        <ul class="space-y-2">
            @php
                $navItems = [
                    ['name' => 'Dashboard',        'icon' => '🏠', 'route' => 'developer.dashboard'],
                    ['name' => 'Ambil Tiket',      'icon' => '🎟️', 'route' => 'developer.allTickets'],
                    ['name' => 'Tiket Saya',       'icon' => '📄', 'route' => 'developer.myTickets'],
                    ['name' => 'Knowledge Base',   'icon' => '📚', 'route' => 'knowledgebase.index'],
                ];
            @endphp

            @foreach($navItems as $item)
                <li>
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center rounded-lg p-3 transition-colors duration-200
                              {{ request()->routeIs($item['route'].'*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' }}"
                       :class="open ? 'justify-start gap-3' : 'justify-center'">
                        <span class="text-xl">{{ $item['icon'] }}</span>
                        <span x-show="open" class="whitespace-nowrap">{{ $item['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-8 pt-4 border-t">
            <h3 class="px-3 text-xs font-semibold uppercase text-gray-500 mb-2" x-show="open">Admin Panel</h3>
            <ul class="space-y-2">
                @php
                    $adminItems = [
                        ['name' => 'Kelola Akun', 'icon' => '👤', 'route' => 'developer.kelolaAkun'],
                    ];
                @endphp

                @foreach($adminItems as $item)
                    <li>
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center rounded-lg p-3 transition-colors duration-200
                                  {{ request()->routeIs($item['route'].'*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' }}"
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