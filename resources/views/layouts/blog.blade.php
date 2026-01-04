<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        scrollProgress: 0
    }"
    @scroll.window="scrollProgress = (window.pageYOffset / (document.documentElement.scrollHeight - window.innerHeight)) * 100"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    <meta name="description"
        content="@yield('meta_description', 'Welcome to ' . config('app.name') . ' - Your source for latest thoughts and insights.')">

    <link rel="icon" href="{{ asset('images/ico.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/ico.png') }}">

    <!-- SEO & Social Media -->
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description"
        content="@yield('meta_description', 'Explore our latest thoughts, guides, and insights.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/ico.png'))">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('app.name'))">
    <meta name="twitter:description"
        content="@yield('meta_description', 'Explore our latest thoughts, guides, and insights.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/ico.png'))">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
        }

        .prose pre {
            padding: 0 !important;
            background: transparent !important;
        }
    </style>
    @stack('header_scripts')
    @yield('header_scripts')
</head>

<body class="antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-300"
    x-data="{ mobileMenuOpen: false }">
    <!-- Reading Progress Bar -->
    <div class="fixed top-0 left-0 h-1 bg-[#459bc9] z-[9999]" :style="{ width: scrollProgress + '%' }"></div>

    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-[#1e74a0] shadow-lg border-b border-[#165e84]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Logo & Brand -->
                    <div class="flex items-center shrink-0">
                        <a href="{{ route('blog.index') }}"
                            class="flex flex-col items-start justify-center gap-0.5 group">
                            <img src="{{ asset('images/logo.svg') }}" alt="Statum Logo" width="135" height="28"
                                class="h-7 w-auto brightness-0 invert group-hover:scale-105 transition-transform duration-300">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-bold text-white tracking-wider leading-none">Engineering
                                    at Scale</span>
                            </div>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ route('blog.index') }}"
                            class="text-sm font-medium text-white/90 hover:text-white transition-colors {{ request()->routeIs('blog.index') ? 'text-white font-bold' : '' }} flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Home
                        </a>

                        <a href="{{ url('/rss.xml') }}"
                            class="text-sm font-medium text-white/90 hover:text-white transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 5c7.18 0 13 5.82 13 13M6 11a7 7 0 017 7m-6 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                            RSS
                        </a>

                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                            aria-label="Toggle dark mode"
                            class="p-2 rounded-lg bg-white/10 text-white hover:bg-white/20 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                </path>
                            </svg>
                            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h1M4 12H3m15.364-6.364l.707-.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex items-center md:hidden gap-4">
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                            aria-label="Toggle dark mode"
                            class="p-2 rounded-lg text-white hover:bg-white/10 transition-colors">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                </path>
                            </svg>
                            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h1M4 12H3m15.364-6.364l.707-.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z">
                                </path>
                            </svg>
                        </button>
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                            class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden bg-[#1e74a0]">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('blog.index') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ request()->routeIs('blog.index') ? 'bg-white/20' : '' }} flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Home
                    </a>

                    <a href="{{ url('/rss.xml') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 5c7.18 0 13 5.82 13 13M6 11a7 7 0 017 7m-6 0a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        RSS Feed
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer id="site-footer"
            class="bg-[#111827] text-white py-16 border-t border-gray-800/50 relative overflow-hidden">
            <!-- Background Elements -->
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-1 bg-gradient-to-r from-transparent via-[#459bc9] to-transparent opacity-50">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 gap-12">
                    <!-- Info Section -->
                    <div class="text-center max-w-4xl mx-auto space-y-6">
                        <div class="space-y-2">
                            <h3 class="text-3xl font-extrabold tracking-tight text-white font-heading">Statum</h3>
                            <div class="w-20 h-1.5 bg-[#459bc9] mx-auto rounded-full"></div>
                        </div>
                        <p class="text-gray-200 leading-relaxed text-base md:text-lg">
                            <strong class="text-white">Statum</strong> is a leading software development company in
                            Nairobi, Kenya, offering a comprehensive range of IT services. Our team of skilled
                            <a href="https://statum.co.ke/custom-software-development"
                                class="font-bold text-white hover:text-[#459bc9] transition-colors decoration-2 underline-offset-2 hover:underline">software
                                developers</a>,
                            <a href="https://statum.co.ke/ui-ux-design-services"
                                class="font-bold text-white hover:text-[#459bc9] transition-colors decoration-2 underline-offset-2 hover:underline">UI/UX
                                designers</a>,
                            <a href="https://statum.co.ke/mobile-app-development"
                                class="font-bold text-white hover:text-[#459bc9] transition-colors decoration-2 underline-offset-2 hover:underline">mobile
                                application developers</a>,
                            <a href="https://statum.co.ke/developer-apis"
                                class="font-bold text-white hover:text-[#459bc9] transition-colors decoration-2 underline-offset-2 hover:underline">integration
                                developers</a>,
                            <a href="https://statum.co.ke/web-development"
                                class="font-bold text-white hover:text-[#459bc9] transition-colors decoration-2 underline-offset-2 hover:underline">web
                                developers</a>,
                            <a href="https://statum.co.ke/services/digital-marketing-company"
                                class="font-bold text-white hover:text-[#459bc9] transition-colors decoration-2 underline-offset-2 hover:underline">digital
                                marketers</a>,
                            ...and so many others are dedicated to providing tailored
                            <a href="https://statum.co.ke/custom-software-development"
                                class="font-bold text-white hover:text-[#459bc9] transition-colors decoration-2 underline-offset-2 hover:underline">custom
                                software solutions</a>
                            that meet your unique business requirements. We specialize in crafting innovative and
                            scalable solutions that drive <strong class="text-[#459bc9]">business growth</strong>.
                        </p>
                    </div>

                    <!-- Contacts Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                        <!-- Contact Item 1: Phone -->
                        <div
                            class="group flex flex-col items-center text-center p-8 rounded-2xl bg-white/5 border border-white/10 hover:border-[#fcb03b]/50 hover:bg-white/10 transition-all duration-300">
                            <div
                                class="w-14 h-14 mb-4 text-[#fcb03b] group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                                <svg enable-background="new 0 0 64 64" version="1.1" viewBox="0 0 64 64"
                                    xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                                    class="w-full h-full stroke-current fill-none" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M45.1,44.2C42.9,42,39.6,40,37,42.6c-1.8,1.8-2.6,3.9-2.6,3.9s-4.3,2.3-11.7-5.2s-5.2-11.7-5.2-11.7s2.1-0.8,3.9-2.6 c2.6-2.6,0.6-5.9-1.7-8.1c-2.7-2.7-6.2-4.9-8.2-2.9c-3.7,3.7-4.4,8.4-4.4,8.4S9,35.5,18.7,45.3s20.9,11.6,20.9,11.6s4.7-0.7,8.4-4.4 C50,50.4,47.8,46.9,45.1,44.2z" />
                                    <path d="M18.4,12.2C22.2,9.5,26.9,8,32,8c13.3,0,24,10.8,24,24c0,4-1.3,9-4.4,12.2" />
                                    <path d="M27.3,55.6c-9.8-1.9-17.5-9.8-19.1-19.7" />
                                    <path
                                        d="M30,21c0,0,4.4,0,5.2,0c1.2,0,1.8,0.2,1.8,1.1s0,0.7,0,1.3c0,0.6,0,1.4-1.6,2.5c-2.3,1.6-5.6,3.8-5.6,5.1c0,1.6,0.7,2,1.8,2 s5.3,0,5.3,0" />
                                    <path d="M40,21c0,0,0,2.8,0,3.8S39.9,27,41.5,27c1.6,0,4.5,0,4.5,0v-6.1V33" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <a href="tel:+254721553678"
                                    class="block text-xl font-bold text-white hover:text-[#fcb03b] transition-colors">+254
                                    721 553678</a>
                                <p class="text-sm font-medium text-gray-200">Mon-Fri 9am-5pm EAT</p>
                            </div>
                        </div>

                        <!-- Contact Item 2: Email -->
                        <div
                            class="group flex flex-col items-center text-center p-8 rounded-2xl bg-white/5 border border-white/10 hover:border-[#f15b26]/50 hover:bg-white/10 transition-all duration-300">
                            <div
                                class="w-14 h-14 mb-4 text-[#f15b26] group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                                <svg enable-background="new 0 0 64 64" version="1.1" viewBox="0 0 64 64"
                                    xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                                    class="w-full h-full stroke-current fill-none" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="54,17 32,36 10,17" />
                                    <line x1="10.9" x2="26" y1="48" y2="36" />
                                    <path d="M32.7,49H13c-2.2,0-4-1.8-4-4V19c0-2.2,1.8-4,4-4h38c2.2,0,4,1.8,4,4v15.5" />
                                    <circle cx="44.9" cy="43.1" r="10.1" />
                                    <path d="M44,41.4c0,0-1.3,3.4-0.9,5.1c0.4,1.7,2.6,2.1,3.7,1.1" />
                                    <g fill="#f15b26" stroke="none">
                                        <circle cx="45.4" cy="38.3" r="0.9" fill="#DCE9EE" />
                                        <path
                                            d="M45.4,37.3c-0.5,0-0.9,0.4-0.9,0.9c0,0.5,0.4,0.9,0.9,0.9s0.9-0.4,0.9-0.9C46.4,37.8,46,37.3,45.4,37.3 L45.4,37.3z" />
                                    </g>
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <a href="mailto:info@statum.co.ke"
                                    class="block text-xl font-bold text-white hover:text-[#f15b26] transition-colors"><span
                                        class="__cf_email__">info@statum.co.ke</span></a>
                                <p class="text-sm font-medium text-gray-200">Contact e-mail</p>
                            </div>
                        </div>

                        <!-- Contact Item 3: Website -->
                        <div
                            class="group flex flex-col items-center text-center p-8 rounded-2xl bg-white/5 border border-white/10 hover:border-[#3cb878]/50 hover:bg-white/10 transition-all duration-300">
                            <div
                                class="w-14 h-14 mb-4 text-[#3cb878] group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                                <svg enable-background="new 0 0 64 64" version="1.1" viewBox="0 0 64 64"
                                    xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                                    class="w-full h-full stroke-current fill-none" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polygon
                                        points="38.7,36.4 56,32 38.7,27.6 42,22 36.4,25.3 32,8 27.6,25.3 22,22 25.3,27.6 8,32 25.3,36.4 22,42 27.6,38.7 32,56 36.4,38.7 42,42" />
                                    <circle cx="32" cy="32" r="4" />
                                    <path d="M26.1,53.2c-7.9-2.2-13.9-8.6-15.6-16.7" />
                                    <path d="M53.5,36.9c-1.8,8.1-8.2,14.6-16.3,16.5" />
                                    <path d="M36.9,10.5c8.2,1.9,14.7,8.3,16.6,16.6" />
                                    <path d="M10.5,27.1c1.9-8.2,8.3-14.6,16.4-16.5" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <a href="https://statum.co.ke/" target="_blank"
                                    class="block text-xl font-bold text-white hover:text-[#3cb878] transition-colors">www.statum.co.ke</a>
                                <p class="text-sm font-medium text-gray-200">Website</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="mt-12 pt-8 border-t border-gray-800 text-center text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} Statum Ltd. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>