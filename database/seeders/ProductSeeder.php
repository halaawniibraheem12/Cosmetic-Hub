<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch categories
        $accessories = Category::where('name', 'Accessories')->first();
        $bags        = Category::where('name', 'Bags')->first();
        $fragrance   = Category::where('name', 'Fragrance')->first();
        $makeup      = Category::where('name', 'Makeup')->first();
        $skincare    = Category::where('name', 'Skincare')->first();

        // Safety check - categories
        if (!$accessories || !$bags || !$fragrance || !$makeup || !$skincare) {
            $this->command->warn('Categories not found. Run CategorySeeder first.');
            return;
        }

        // Fetch users (owners)
        $users = User::all();

        // Safety check - users
        if ($users->isEmpty()) {
            $this->command->warn('Users not found. Run UserSeeder first.');
            return;
        }

        $products = [
            ['name' => 'Sunglasses',          'price' => 99.99,  'category_id' => $accessories->id],
            ['name' => 'Tote Bag',            'price' => 59.99,  'category_id' => $bags->id],
            ['name' => 'Perfume',             'price' => 550.70, 'category_id' => $fragrance->id],
            ['name' => 'Scarf',               'price' => 30.50,  'category_id' => $accessories->id],
            ['name' => 'Hair Clips',          'price' => 29.00,  'category_id' => $accessories->id],
            ['name' => 'Lipstick',            'price' => 25.00,  'category_id' => $makeup->id],
            ['name' => 'Hydrating Cleanser',  'price' => 35.00,  'category_id' => $skincare->id],
        ];

        foreach ($products as $product) {

            // Assign an owner randomly (or you can use $users->first()->id)
            $product['user_id'] = $users->random()->id;

            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}