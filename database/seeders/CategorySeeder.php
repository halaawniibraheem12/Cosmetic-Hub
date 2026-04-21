<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            "Accessories",
            "Bags",
            "Fragrance",
            "Makeup",
            "Skincare",
        ];

        $user = User::first();

        if (!$user) {
            $this->command->warn('No users found. Run User/DemoUser seeder first.');
            return;
        }

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => $user->id,
                ]
            );
        }

        $this->command->info('Categories seeded successfully.');
    }
}