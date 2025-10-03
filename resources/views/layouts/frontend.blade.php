<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'XTube'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $favicon = App\Models\WebSetting::where('key', 'site_favicon')->first();
        $logo = App\Models\WebSetting::where('key', 'site_logo')->first();
    @endphp
    @if ($favicon && $favicon->value)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $favicon->value) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Header Affiliate Code -->
    @php
        $headerAffiliateCode = App\Helpers\AffiliateHelper::render('header_affiliate_code');
    @endphp
    @if ($headerAffiliateCode)
        {!! $headerAffiliateCode !!}
    @endif
</head>

<body class="bg-gray-50 dark:bg-gray-950">
    <!-- Header Sticky Banner -->
    @php
        $headerStickyBanner = App\Helpers\AffiliateHelper::render('banner_header_sticky');
    @endphp
    @if ($headerStickyBanner)
        <div id="header-sticky-banner" class="fixed top-0 left-0 right-0 bg-black/50 z-50 shadow-md">
            <div class="relative flex items-center justify-center">
                {!! $headerStickyBanner !!}
                <button onclick="closeHeaderBanner()"
                    class="absolute top-2 right-2 p-1 bg-black bg-opacity-50 cursor-pointer hover:bg-opacity-70 rounded-full transition">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
        <script>
            function closeHeaderBanner() {
                document.getElementById('header-sticky-banner').style.display = 'none';
                document.querySelector('header').style.marginTop = '0';
            }
            // Adjust header position
            const bannerHeight = document.getElementById('header-sticky-banner')?.offsetHeight || 0;
            if (bannerHeight > 0) {
                document.querySelector('header').style.marginTop = bannerHeight + 'px';
            }
        </script>
    @endif

    <!-- Header -->
    <header
        class="fixed top-0 left-0 right-0 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 z-40">
        <div class="flex items-center justify-between px-4 py-2">
            <!-- Left: Menu & Logo -->
            <div class="flex items-center space-x-4">
                <button onclick="toggleSidebar()"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer rounded-full transition">
                    <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <a href="/" class="flex items-center space-x-2">
                    @if ($logo && $logo->value)
                        <img src="{{ asset('storage/' . $logo->value) }}" alt="Logo" class="h-6 object-contain">
                    @else
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21.582 6.186c-.23-0.86-0.908-1.538-1.768-1.768C18.254 4 12 4 12 4s-6.254 0-7.814 0.418c-0.86 0.23-1.538 0.908-1.768 1.768C2 7.746 2 12 2 12s0 4.254 0.418 5.814c0.23 0.86 0.908 1.538 1.768 1.768C5.746 20 12 20 12 20s6.254 0 7.814-0.418c0.86-0.23 1.538-0.908 1.768-1.768C22 16.254 22 12 22 12s0-4.254-0.418-5.814zM10 15.464V8.536L16 12l-6 3.464z" />
                        </svg>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">VideoHub</span>
                    @endif
                </a>
            </div>

            <!-- Center: Search -->
            <div class="flex-1 max-w-2xl mx-4 hidden md:block">
                <form action="{{ route('search') }}" method="GET" class="flex">
                    <input type="text" name="q" placeholder="Search videos..."
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-l-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:border-blue-500">
                    <button type="submit"
                        class="px-6 py-2 bg-gray-100 dark:bg-gray-800 border cursor-pointer border-l-0 border-gray-300 dark:border-gray-600 rounded-r-full hover:bg-gray-200 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Right: Dark Mode & Login -->
            <div class="flex items-center space-x-2">
                <button onclick="toggleDarkMode()"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer rounded-full transition">
                    <svg class="w-6 h-6 text-gray-700 dark:text-gray-300 dark:hidden" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                    <svg class="w-6 h-6 text-gray-300 hidden dark:block" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </button>
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition">
                            🚀 Dashboard Admin
                        </a>
                        {{-- @else
                        <a href="{{ route('home') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition">
                            My Profile
                        </a> --}}
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full transition">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>

        <!-- Mobile Search -->
        <div class="px-4 pb-2 md:hidden">
            <form action="{{ route('search') }}" method="GET" class="flex">
                <input type="text" name="q" placeholder="Search..."
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-l-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:outline-none">
                <button type="submit"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-800 border cursor-pointer border-l-0 border-gray-300 dark:border-gray-600 rounded-r-full">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    <div class="flex pt-14">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed left-0 top-14 bottom-0 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 overflow-y-auto transition-transform duration-300 z-30 lg:translate-x-0 -translate-x-full">
            <nav class="py-2 pt-14 lg:pt-0">
                <div class="flex flex-col justify-between min-h-[80dvh]">
                    <div>
                        <!-- Home -->
                        <a href="/"
                            class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('home') ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                            <svg class="w-6 h-6 mr-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                            </svg>
                            <span class="font-medium">Home</span>
                        </a>

                        <div class="border-t border-gray-200 dark:border-gray-800 my-2"></div>

                        <!-- Categories -->
                        <div class="px-6 py-2">
                            <h3
                                class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Categories</h3>
                        </div>

                        @foreach ($categories ?? [] as $category)
                            <a href="/?category={{ $category->id }}"
                                class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->input('category') == $category->id ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                                @if ($category->icon_svg)
                                    <div class="w-6 h-6 mr-4" style="color: {{ $category->color }}">
                                        {!! $category->icon_svg !!}
                                    </div>
                                @else
                                    <svg class="w-6 h-6 mr-4" style="color: {{ $category->color }}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                @endif
                                <span>{{ $category->name }}</span>
                            </a>
                        @endforeach
                    </div>
                    @if (Auth::check())
                        <!-- Bottom Actions -->
                        <div>
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full px-4 py-2 text-gray-700 cursor-pointer dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        <span class="inline">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </nav>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebarOverlay" onclick="toggleSidebar()"
            class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden lg:hidden"></div>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 p-4 min-h-screen mt-14">
            @yield('content')
        </main>
    </div>

    <!-- Bottom Sticky Banner -->
    @php
        $bottomStickyBanner = App\Helpers\AffiliateHelper::render('banner_bottom_sticky');
    @endphp
    @if ($bottomStickyBanner)
        <div id="bottom-sticky-banner"
            class="fixed bottom-0 left-0 right-0 z-50 bg-black/50 shadow-lg border-t border-gray-200 dark:border-gray-800">
            <div class="relative flex items-center justify-center">
                {!! $bottomStickyBanner !!}
                <button onclick="closeBottomBanner()"
                    class="absolute top-2 right-2 p-1 cursor-pointer bg-black bg-opacity-50 hover:bg-opacity-70 rounded-full transition">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <script>
            function closeBottomBanner() {
                document.getElementById('bottom-sticky-banner').style.display = 'none';
            }
        </script>
    @endif

    <!-- Popup Centered Banner -->
    @php
        $popupBanner = App\Helpers\AffiliateHelper::render('banner_popup_centered');
    @endphp
    @if ($popupBanner)
        <div id="popup-banner"
            class="fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center p-4"
            style="display: none;">
            <div
                class="relative bg-white flex items-center justify-center dark:bg-gray-900 rounded-lg shadow-2xl max-w-min w-full">
                <button onclick="closePopup()"
                    class="absolute -top-3 -right-3 p-2 bg-red-600 cursor-pointer hover:bg-red-700 rounded-full transition">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="p-4">
                    {!! $popupBanner !!}
                </div>
            </div>
        </div>
        <script>
            function closePopup() {
                document.getElementById('popup-banner').style.display = 'none';
                localStorage.setItem('popup_closed', 'true');
            }

            // Show popup after 1 seconds if not closed before
            setTimeout(() => {
                if (!localStorage.getItem('popup_closed')) {
                    document.getElementById('popup-banner').style.display = 'flex';
                }
            }, 1000);

            // Reset popup closed status after 24 hours
            // setTimeout(() => {
            //     localStorage.removeItem('popup_closed');
            // }, 24 * 60 * 60 * 1000);
            // Reset popup closed status after 2 seconds
            setTimeout(() => {
                localStorage.removeItem('popup_closed');
            }, 3000);
        </script>
    @endif

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Close sidebar when clicking outside on mobile
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebarOverlay').classList.add('hidden');
            }
        });

        // Close popup on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const popup = document.getElementById('popup-banner');
                if (popup && popup.style.display !== 'none') {
                    closePopup();
                }
            }
        });
    </script>

    <!-- Histats Analytics Code -->
    @php
        $histatsCode = App\Helpers\AffiliateHelper::render('histats_code');
    @endphp
    @if ($histatsCode)
        {!! $histatsCode !!}
    @endif
</body>

</html>
