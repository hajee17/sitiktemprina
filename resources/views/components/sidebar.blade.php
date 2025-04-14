<aside class="w-72 h-screen bg-white px-6 py-6 shadow-md flex flex-col justify-between fixed">
    <div class="space-y-10">
        {{-- Logo + Toggle --}}
        <div class="flex justify-between items-center">
            <div class="h-7 w-32 bg-no-repeat bg-center bg-contain" style="background-image: url('/images/logo.png');"></div>
            <div class="flex flex-col gap-1">
                <div class="w-[9px] h-[18px] bg-gray-500"></div>
                <div class="w-[7.5px] h-[18px] bg-gray-500 ml-1"></div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="space-y-4">
            @php
                $nav = [
                    ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => '🏠'],
                    ['name' => 'Kelola Akun', 'route' => 'admin.users', 'icon' => '👤'],
                    ['name' => 'Kelola Tiket', 'route' => 'admin.tickets', 'icon' => '🎫'],
                ];
            @endphp

            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-semibold
                   {{ request()->routeIs($item['route']) ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                    <span class="text-xl">{{ $item['icon'] }}</span>
                    <span>{{ $item['name'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</aside>
