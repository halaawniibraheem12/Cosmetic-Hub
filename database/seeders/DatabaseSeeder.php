<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DemoUserSeeder::class,
            UserSeeder::class,

            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            ProductSupplierSeeder::class,
        ]);
    }
}