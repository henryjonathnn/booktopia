<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Booktopia' }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
    @once
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    @endonce
</head>

<body class="min-h-full flex flex-col">
    <div class="flex-grow">
        {{ $slot }}
    </div>
    
    @livewireScripts
</body>

</html>
