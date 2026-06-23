<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $latestProducts = Product::active()
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        return view('home', compact('featuredProducts', 'categories', 'latestProducts'));
    }
}
