<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a paginated listing of products with search and filter.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by name or SKU
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Filter by active status
        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by featured
        if ($request->has('is_featured') && $request->input('is_featured') !== '') {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0|lt:price',
            'stock'             => 'required|integer|min:0',
            'sku'               => 'nullable|string|max:100|unique:products,sku',
            'thumbnail'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images'            => 'nullable|array',
            'images.*'          => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'weight'            => 'nullable|numeric|min:0',
            'is_active'         => 'nullable|boolean',
            'is_featured'       => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter++;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        // Remove 'images' from validated data before creating product
        $images = $request->file('images', []);
        unset($validated['images']);

        $product = Product::create($validated);

        // Handle multiple product images upload
        if (!empty($images)) {
            foreach ($images as $index => $image) {
                $path = $image->store('products/images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'productImages']);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $product->load('productImages');
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0|lt:price',
            'stock'             => 'required|integer|min:0',
            'sku'               => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'thumbnail'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images'            => 'nullable|array',
            'images.*'          => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'weight'            => 'nullable|numeric|min:0',
            'is_active'         => 'nullable|boolean',
            'is_featured'       => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Ensure unique slug (excluding the current product)
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->where('id', '!=', $product->id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter++;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if it exists
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        // Remove 'images' from validated data before updating product
        $images = $request->file('images', []);
        unset($validated['images']);

        $product->update($validated);

        // Handle multiple product images upload
        if (!empty($images)) {
            $maxSortOrder = $product->productImages()->max('sort_order') ?? -1;
            foreach ($images as $index => $image) {
                $path = $image->store('products/images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $maxSortOrder + $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete thumbnail
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        // Delete all product images from storage
        foreach ($product->productImages as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Add images to an existing product.
     */
    public function addImages(Request $request, Product $product)
    {
        $request->validate([
            'images'   => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $maxSortOrder = $product->productImages()->max('sort_order') ?? -1;

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('products/images', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'sort_order' => $maxSortOrder + $index + 1,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Images added successfully.');
    }

    /**
     * Remove a specific image from a product.
     */
    public function removeImage(Product $product, ProductImage $image)
    {
        // Ensure the image belongs to this product
        if ($image->product_id !== $product->id) {
            return redirect()
                ->back()
                ->with('error', 'Image does not belong to this product.');
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()
            ->back()
            ->with('success', 'Image removed successfully.');
    }
}
