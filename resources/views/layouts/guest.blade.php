<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Career Booster') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white">
        {{-- Shared shell for every signed-out page (login, register, password
             reset, etc.): brand mark centred above a white card. --}}
        <div class="min-h-screen flex flex-col justify-center items-center px-6 py-12">

            {{-- Logo links home so guests can always get back to the landing page. --}}
            <a href="/" aria-label="Career Booster home">
                <img src="{{ asset('images/image.svg') }}" alt="Career Booster logo" class="h-20 w-20 sm:h-24 sm:w-24">
            </a>

            <h1 class="mt-4 text-2xl font-semibold tracking-tight text-[#0E6378]">
                Career Booster
            </h1>

            {{-- The page content (the form) is passed in through $slot. --}}
            <div class="w-full sm:max-w-md mt-8 px-6 py-6 bg-white border border-gray-200 shadow-sm overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
