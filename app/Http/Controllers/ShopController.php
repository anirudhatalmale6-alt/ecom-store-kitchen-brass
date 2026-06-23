<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::active()->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('shop.index', compact('products', 'categories'));
    }

    public function show(string $slug): View
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['category', 'productImages'])
            ->firstOrFail();

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }

    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::active()
            ->where('category_id', $category->id)
            ->with('category')
            ->latest()
            ->paginate(12);

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('shop.index', compact('products', 'categories', 'category'));
    }
}
