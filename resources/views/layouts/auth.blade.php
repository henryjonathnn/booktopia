<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Authentication - Booktopia' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#0D0D1A]">
    <div class="min-h-screen flex">
        <!-- Left Side - Animated Background -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <div class="absolute inset-0  z-10"></div>
            <div class="absolute inset-0 bg-grid-pattern animate-grid-movement"></div>
            <div class="relative z-20 flex items-center justify-center w-full">
                <div class="text-center">
                    <a href="/" class="text-4xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
                        Booktopia
                    </a>
                    <p class="mt-4 text-gray-300 max-w-md mx-auto">
                        Akses ribuan buku-buku menarik dari penulis terkenal dan penerbit di seluruh dunia.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Auth Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html> 