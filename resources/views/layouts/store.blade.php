<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'KitchenCraft & Brass - Premium Kitchen Items & Brass Statues')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-stone-50 text-stone-800">

    {{-- Top Bar --}}
    <div class="bg-stone-900 text-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">

                {{-- Store Name --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                    <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    <span class="text-lg font-bold text-white tracking-wide">KitchenCraft <span class="text-amber-500">&</span> Brass</span>
                </a>

                {{-- Search Bar --}}
                <form action="{{ route('shop.index') }}" method="GET" class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search products..."
                            class="w-full pl-4 pr-10 py-2 rounded-lg bg-stone-800 border border-stone-700 text-stone-200 placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm transition"
                        >
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-stone-400 hover:text-amber-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Right Side: Cart + Auth --}}
                <div class="flex items-center gap-4">
                    {{-- Cart Icon --}}
                    <a href="{{ route('cart.index') }}" class="relative text-stone-300 hover:text-amber-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        @php
                            $cartCount = 0;
                            if (isset($cart) && $cart) {
                                $cartCount = $cart->cartItems->sum('quantity');
                            }
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-amber-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Auth Links --}}
                    @auth
                        <div class="relative group">
                            <button class="flex items-center gap-1 text-stone-300 hover:text-amber-500 transition text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-stone-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 text-sm text-stone-700 hover:bg-amber-50 hover:text-amber-700 rounded-t-lg">My Account</a>
                                <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-sm text-stone-700 hover:bg-amber-50 hover:text-amber-700">My Orders</a>
                                <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-sm text-stone-700 hover:bg-amber-50 hover:text-amber-700">Profile</a>
                                <hr class="border-stone-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-stone-700 hover:bg-red-50 hover:text-red-700 rounded-b-lg">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3 text-sm">
                            <a href="{{ route('login') }}" class="text-stone-300 hover:text-amber-500 transition">Login</a>
                            <span class="text-stone-600">|</span>
                            <a href="{{ route('register') }}" class="text-stone-300 hover:text-amber-500 transition">Register</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Bar --}}
    <nav class="bg-amber-700 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-12">

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-medium text-white hover:bg-amber-600 rounded-lg transition {{ request()->routeIs('home') ? 'bg-amber-800' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('shop.index') }}" class="px-4 py-2 text-sm font-medium text-white hover:bg-amber-600 rounded-lg transition {{ request()->routeIs('shop.*') ? 'bg-amber-800' : '' }}">
                        Shop
                    </a>

                    {{-- Categories Dropdown --}}
                    <div class="relative group">
                        <button class="px-4 py-2 text-sm font-medium text-white hover:bg-amber-600 rounded-lg transition flex items-center gap-1">
                            Categories
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-0 w-56 bg-white rounded-lg shadow-xl border border-stone-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            @php
                                $navCategories = $categories ?? \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
                            @endphp
                            @forelse($navCategories as $navCat)
                                <a href="{{ route('shop.category', $navCat->slug) }}" class="block px-4 py-2 text-sm text-stone-700 hover:bg-amber-50 hover:text-amber-700 {{ $loop->first ? 'rounded-t-lg' : '' }} {{ $loop->last ? 'rounded-b-lg' : '' }}">
                                    {{ $navCat->name }}
                                </a>
                            @empty
                                <span class="block px-4 py-2 text-sm text-stone-400">No categories yet</span>
                            @endforelse
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('customer.dashboard') }}" class="px-4 py-2 text-sm font-medium text-white hover:bg-amber-600 rounded-lg transition {{ request()->routeIs('customer.*') ? 'bg-amber-800' : '' }}">
                            My Account
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <button id="mobile-menu-btn" class="md:hidden text-white hover:text-amber-200 transition" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Mobile Search --}}
                <form action="{{ route('shop.index') }}" method="GET" class="md:hidden flex-1 mx-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search..."
                        class="w-full px-3 py-1.5 rounded-lg bg-amber-800 border border-amber-600 text-white placeholder-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
                    >
                </form>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobile-menu" class="md:hidden hidden pb-4">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-sm font-medium text-white hover:bg-amber-600 rounded-lg">Home</a>
                <a href="{{ route('shop.index') }}" class="block px-3 py-2 text-sm font-medium text-white hover:bg-amber-600 rounded-lg">Shop</a>
                @isset($navCategories)
                    @foreach($navCategories as $navCat)
                        <a href="{{ route('shop.category', $navCat->slug) }}" class="block pl-6 py-2 text-sm text-amber-200 hover:bg-amber-600 rounded-lg">{{ $navCat->name }}</a>
                    @endforeach
                @endisset
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="block px-3 py-2 text-sm font-medium text-white hover:bg-amber-600 rounded-lg">My Account</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg" role="alert">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Page Content --}}
    <main class="min-h-[60vh]">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-stone-900 text-stone-400 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                {{-- Store Info --}}
                <div class="md:col-span-1">
                    <h3 class="text-white text-lg font-bold mb-3">KitchenCraft <span class="text-amber-500">&</span> Brass</h3>
                    <p class="text-sm leading-relaxed">Your premier destination for premium kitchen essentials and handcrafted brass statues. Quality craftsmanship meets timeless elegance.</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-white text-sm font-semibold uppercase tracking-wider mb-3">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition">Home</a></li>
                        <li><a href="{{ route('shop.index') }}" class="hover:text-amber-500 transition">Shop</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-amber-500 transition">Cart</a></li>
                        @auth
                            <li><a href="{{ route('customer.dashboard') }}" class="hover:text-amber-500 transition">My Account</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-amber-500 transition">Login</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-amber-500 transition">Register</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Categories --}}
                <div>
                    <h4 class="text-white text-sm font-semibold uppercase tracking-wider mb-3">Categories</h4>
                    <ul class="space-y-2 text-sm">
                        @isset($navCategories)
                            @foreach($navCategories as $footerCat)
                                <li><a href="{{ route('shop.category', $footerCat->slug) }}" class="hover:text-amber-500 transition">{{ $footerCat->name }}</a></li>
                            @endforeach
                        @endisset
                    </ul>
                </div>

                {{-- Contact Info --}}
                <div>
                    <h4 class="text-white text-sm font-semibold uppercase tracking-wider mb-3">Contact Us</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            info@kitchencraftbrass.com
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            +1 (555) 123-4567
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            123 Artisan Lane, Craftsville, CS 12345
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-stone-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} KitchenCraft & Brass. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
