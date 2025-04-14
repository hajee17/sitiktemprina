<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#F5F6FA]">

    @include('components.sidebar')

    <main class="ml-72 p-6 min-h-screen">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
