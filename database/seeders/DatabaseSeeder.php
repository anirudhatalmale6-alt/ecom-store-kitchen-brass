<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@store.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'John Customer',
            'email' => 'customer@store.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
        ]);

        $categories = [
            ['name' => 'Kitchen Utensils', 'description' => 'Essential kitchen tools and utensils for everyday cooking'],
            ['name' => 'Brass Statues', 'description' => 'Handcrafted brass statues and figurines'],
            ['name' => 'Cookware', 'description' => 'Pots, pans, and cooking vessels'],
            ['name' => 'Brass Decor', 'description' => 'Decorative brass items for home'],
            ['name' => 'Storage & Organization', 'description' => 'Kitchen storage containers and organizers'],
            ['name' => 'Brass Pooja Items', 'description' => 'Traditional brass items for worship'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'is_active' => true,
            ]);
        }

        $products = [
            ['category' => 'Kitchen Utensils', 'name' => 'Stainless Steel Spatula Set', 'price' => 24.99, 'stock' => 50, 'description' => 'Set of 3 premium stainless steel spatulas', 'is_featured' => true],
            ['category' => 'Kitchen Utensils', 'name' => 'Wooden Spoon Collection', 'price' => 18.99, 'stock' => 35, 'description' => 'Handmade wooden spoons set of 5'],
            ['category' => 'Kitchen Utensils', 'name' => 'Chef Knife Set', 'price' => 89.99, 'sale_price' => 69.99, 'stock' => 20, 'description' => 'Professional 5-piece chef knife set', 'is_featured' => true],
            ['category' => 'Kitchen Utensils', 'name' => 'Silicone Baking Tools', 'price' => 32.99, 'stock' => 40, 'description' => 'Heat-resistant silicone baking tool set'],
            ['category' => 'Brass Statues', 'name' => 'Brass Ganesha Statue', 'price' => 149.99, 'stock' => 15, 'description' => 'Hand-carved brass Lord Ganesha statue, 12 inches', 'is_featured' => true],
            ['category' => 'Brass Statues', 'name' => 'Dancing Nataraja', 'price' => 199.99, 'sale_price' => 179.99, 'stock' => 8, 'description' => 'Exquisite brass Nataraja dancing Shiva statue'],
            ['category' => 'Brass Statues', 'name' => 'Brass Buddha Meditation', 'price' => 129.99, 'stock' => 12, 'description' => 'Serene meditating Buddha in solid brass', 'is_featured' => true],
            ['category' => 'Brass Statues', 'name' => 'Brass Horse Figurine', 'price' => 89.99, 'stock' => 18, 'description' => 'Galloping horse figurine in polished brass'],
            ['category' => 'Cookware', 'name' => 'Cast Iron Skillet 12"', 'price' => 45.99, 'stock' => 25, 'description' => 'Pre-seasoned 12-inch cast iron skillet'],
            ['category' => 'Cookware', 'name' => 'Non-Stick Pan Set', 'price' => 79.99, 'sale_price' => 64.99, 'stock' => 30, 'description' => '3-piece non-stick frying pan set', 'is_featured' => true],
            ['category' => 'Cookware', 'name' => 'Stainless Steel Pressure Cooker', 'price' => 59.99, 'stock' => 22, 'description' => '5-liter stainless steel pressure cooker'],
            ['category' => 'Cookware', 'name' => 'Copper Bottom Kadai', 'price' => 34.99, 'stock' => 28, 'description' => 'Traditional copper bottom kadai for Indian cooking'],
            ['category' => 'Brass Decor', 'name' => 'Brass Wall Hanging Plate', 'price' => 59.99, 'stock' => 20, 'description' => 'Decorative engraved brass plate for wall display'],
            ['category' => 'Brass Decor', 'name' => 'Brass Candle Holder Set', 'price' => 44.99, 'stock' => 25, 'description' => 'Pair of ornate brass candle holders'],
            ['category' => 'Brass Decor', 'name' => 'Antique Brass Vase', 'price' => 74.99, 'sale_price' => 64.99, 'stock' => 10, 'description' => 'Vintage-style engraved brass flower vase', 'is_featured' => true],
            ['category' => 'Storage & Organization', 'name' => 'Stainless Steel Container Set', 'price' => 39.99, 'stock' => 35, 'description' => 'Set of 5 airtight stainless steel containers'],
            ['category' => 'Storage & Organization', 'name' => 'Spice Box (Masala Dabba)', 'price' => 29.99, 'stock' => 40, 'description' => 'Traditional stainless steel spice box with 7 compartments', 'is_featured' => true],
            ['category' => 'Storage & Organization', 'name' => 'Bamboo Shelf Organizer', 'price' => 49.99, 'stock' => 15, 'description' => '3-tier bamboo kitchen shelf organizer'],
            ['category' => 'Brass Pooja Items', 'name' => 'Brass Diya Set', 'price' => 24.99, 'stock' => 50, 'description' => 'Set of 5 traditional brass oil lamps'],
            ['category' => 'Brass Pooja Items', 'name' => 'Brass Pooja Thali', 'price' => 49.99, 'stock' => 20, 'description' => 'Complete brass pooja thali with accessories', 'is_featured' => true],
            ['category' => 'Brass Pooja Items', 'name' => 'Brass Bell (Ghanti)', 'price' => 19.99, 'stock' => 45, 'description' => 'Handmade brass prayer bell with clear ring'],
            ['category' => 'Brass Pooja Items', 'name' => 'Brass Incense Holder', 'price' => 14.99, 'stock' => 55, 'description' => 'Decorative brass incense stick holder'],
        ];

        foreach ($products as $p) {
            $category = Category::where('name', $p['category'])->first();
            Product::create([
                'category_id' => $category->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'short_description' => substr($p['description'], 0, 80),
                'price' => $p['price'],
                'sale_price' => $p['sale_price'] ?? null,
                'stock' => $p['stock'],
                'sku' => strtoupper(Str::random(8)),
                'is_active' => true,
                'is_featured' => $p['is_featured'] ?? false,
            ]);
        }
    }
}
