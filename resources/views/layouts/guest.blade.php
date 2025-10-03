<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $favicon = App\Models\WebSetting::where('key', 'site_favicon')->first();
    @endphp
    @if ($favicon && $favicon->value)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $favicon->value) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class=" bg-gray-900/50"
    style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/images/straight_signup.png');  background-attachment: fixed; background-position: center center; background-size: cover; background-repeat: no-repeat;">
    <div class="min-h-screen flex items-center justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="flex items-center justify-center text-center mb-6 sm:mb-8">
                @php
                    $logo = App\Models\WebSetting::where('key', 'site_logo')->first();
                @endphp
                @if ($logo && $logo->value)
                    <a href="{{ route('home') }}" class="">
                        <img src="{{ asset('storage/' . $logo->value) }}" alt="Logo" class="h-12 object-contain">
                    </a>
                @else
                    <a href="{{ route('home') }}">
                        <img src="/logo.png" alt="Logo" class="h-12 object-contain">
                    </a>
                @endif
            </div>

            @yield('content')

            <!-- Dark Mode Toggle untuk Guest -->
            <div class="mt-6 text-center">
                <button type="button" onclick="toggleDarkMode()"
                    class="inline-flex cursor-pointer active:scale-95 items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition">
                    <svg class="w-5 h-5 mr-2 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                    <svg class="w-5 h-5 mr-2 hidden dark:block" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span class="dark:hidden">Dark Mode</span>
                    <span class="hidden dark:inline">Light Mode</span>
                </button>
            </div>
        </div>
    </div>
</body>

</html>
