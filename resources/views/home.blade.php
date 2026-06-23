@extends('layouts.store')

@section('title', 'KitchenCraft & Brass - Home')

@section('content')

    {{-- Hero Banner --}}
    <section class="relative bg-gradient-to-br from-stone-900 via-stone-800 to-amber-900 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23d97706&quot; fill-opacity=&quot;0.3&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight">
                    Artisan Kitchen
                    <span class="text-amber-500">Essentials</span> &
                    <span class="text-amber-500">Brass</span> Masterpieces
                </h1>
                <p class="mt-6 text-lg text-stone-300 leading-relaxed">
                    Discover our curated collection of premium kitchen tools, cookware, and
                    handcrafted brass statues. Where everyday functionality meets timeless artistry.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        Shop Now
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('shop.index') }}?category=brass-statues" class="inline-flex items-center px-8 py-3 border-2 border-amber-500 text-amber-400 hover:bg-amber-500 hover:text-white font-semibold rounded-lg transition-all duration-300">
                        Browse Brass Collection
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Products --}}
    @if($featuredProducts->count())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-stone-800">Featured Products</h2>
            <p class="mt-2 text-stone-500">Handpicked selections from our finest collections</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @foreach($featuredProducts as $product)
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg border border-stone-200 overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                    {{-- Product Image --}}
                    <a href="{{ route('shop.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-stone-100">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-stone-100 to-stone-200">
                                <svg class="w-16 h-16 text-stone-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </a>

                    {{-- Product Info --}}
                    <div class="p-4">
                        @if($product->category)
                            <span class="text-xs font-medium text-amber-600 uppercase tracking-wider">{{ $product->category->name }}</span>
                        @endif
                        <h3 class="mt-1 text-sm font-semibold text-stone-800 truncate">
                            <a href="{{ route('shop.show', $product->slug) }}" class="hover:text-amber-700 transition">{{ $product->name }}</a>
                        </h3>

                        {{-- Price --}}
                        <div class="mt-2 flex items-center gap-2">
                            @if($product->sale_price)
                                <span class="text-lg font-bold text-amber-700">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-sm text-stone-400 line-through">${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="text-lg font-bold text-stone-800">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>

                        {{-- Add to Cart --}}
                        <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                                </svg>
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Categories Section --}}
    @if($categories->count())
    <section class="bg-stone-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-stone-800">Shop by Category</h2>
                <p class="mt-2 text-stone-500">Find exactly what you're looking for</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($categories as $cat)
                    <a href="{{ route('shop.category', $cat->slug) }}" class="group relative overflow-hidden rounded-xl bg-white shadow-sm hover:shadow-lg border border-stone-200 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="aspect-[4/3] overflow-hidden bg-stone-100">
                            @if($cat->image)
                                <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-amber-50 to-amber-100">
                                    <svg class="w-12 h-12 text-amber-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 text-center">
                            <h3 class="text-sm font-semibold text-stone-800 group-hover:text-amber-700 transition">{{ $cat->name }}</h3>
                            <p class="text-xs text-stone-500 mt-1">{{ $cat->products()->count() }} {{ Str::plural('product', $cat->products()->count()) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Latest Products --}}
    @if($latestProducts->count())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-bold text-stone-800">Latest Arrivals</h2>
                <p class="mt-2 text-stone-500">Fresh additions to our collection</p>
            </div>
            <a href="{{ route('shop.index') }}" class="hidden sm:inline-flex items-center text-amber-700 hover:text-amber-800 font-medium transition">
                View All
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @foreach($latestProducts as $product)
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg border border-stone-200 overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                    {{-- Product Image --}}
                    <a href="{{ route('shop.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-stone-100">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-stone-100 to-stone-200">
                                <svg class="w-16 h-16 text-stone-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </a>

                    {{-- Product Info --}}
                    <div class="p-4">
                        @if($product->category)
                            <span class="text-xs font-medium text-amber-600 uppercase tracking-wider">{{ $product->category->name }}</span>
                        @endif
                        <h3 class="mt-1 text-sm font-semibold text-stone-800 truncate">
                            <a href="{{ route('shop.show', $product->slug) }}" class="hover:text-amber-700 transition">{{ $product->name }}</a>
                        </h3>

                        {{-- Price --}}
                        <div class="mt-2 flex items-center gap-2">
                            @if($product->sale_price)
                                <span class="text-lg font-bold text-amber-700">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-sm text-stone-400 line-through">${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="text-lg font-bold text-stone-800">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>

                        {{-- Add to Cart --}}
                        <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                                </svg>
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-8 sm:hidden">
            <a href="{{ route('shop.index') }}" class="inline-flex items-center text-amber-700 hover:text-amber-800 font-medium transition">
                View All Products
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </section>
    @endif

    {{-- Trust Badges --}}
    <section class="bg-amber-50 border-t border-amber-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-stone-800">Quality Guaranteed</h4>
                    <p class="text-xs text-stone-500 mt-1">Premium materials only</p>
                </div>
                <div>
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-stone-800">Free Shipping</h4>
                    <p class="text-xs text-stone-500 mt-1">On orders over $50</p>
                </div>
                <div>
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-stone-800">Easy Returns</h4>
                    <p class="text-xs text-stone-500 mt-1">30-day return policy</p>
                </div>
                <div>
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-stone-800">Secure Payment</h4>
                    <p class="text-xs text-stone-500 mt-1">100% secure checkout</p>
                </div>
            </div>
        </div>
    </section>

@endsection
