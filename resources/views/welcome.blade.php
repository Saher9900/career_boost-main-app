<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Career Booster') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white">
        {{-- Landing page: single centred column on a plain white background,
             vertically centred with min-h-screen. --}}
        <div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">

            {{-- Brand mark. Served straight from public/, so asset() gives the
                 correct URL without needing the Vite manifest. --}}
            <a href="/" aria-label="Career Booster home">
                <img src="{{ asset('images/image.svg') }}" alt="Career Booster logo" class="h-24 w-24 sm:h-28 sm:w-28">
            </a>

            <h1 class="mt-6 text-3xl sm:text-4xl font-semibold tracking-tight text-[#0E6378]">
                Career Booster
            </h1>

            {{-- Short welcome line. max-w-md keeps the text readable instead of
                 stretching across a wide screen. --}}
            <p class="mt-3 max-w-md text-center text-base leading-relaxed text-gray-500">
                {{ __('Welcome! Boost your career by finding the right opportunity, or post one for others to find.') }}
            </p>

            {{-- Guest actions: log in or create an account. Switching on @auth
                 means a signed-in visitor sees a single dashboard link instead. --}}
            <div class="mt-10 w-full max-w-xs flex flex-col gap-3">
                @auth
                    <a
                        href="{{ url('/dashboard') }}"
                        class="inline-flex items-center justify-center rounded-md bg-[#138A9E] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0E6378] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2"
                    >
                        {{ __('Go to dashboard') }}
                    </a>
                @else
                    {{-- Primary action: filled button in the logo's teal. --}}
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center justify-center rounded-md bg-[#138A9E] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0E6378] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2"
                    >
                        {{ __('Log in') }}
                    </a>

                    {{-- Secondary action, guarded in case registration is ever
                         disabled in routes/auth.php. --}}
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center justify-center rounded-md border border-[#138A9E] bg-white px-6 py-3 text-sm font-semibold text-[#0E6378] transition hover:bg-[#138A9E]/5 focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2"
                        >
                            {{ __('Create an account') }}
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </body>
</html>
