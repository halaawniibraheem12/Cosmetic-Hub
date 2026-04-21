<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\User;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Run UserSeeder first.');
            return;
        }

        $suppliers = [
            [
                'name'  => 'Glamour Wholesale',
                'email' => 'glamour@suppliers.com',
            ],
            [
                'name'  => 'Beauty Supply Co',
                'email' => 'beauty@suppliers.com',
            ],
            [
                'name'  => 'Cosmetics Hub',
                'email' => 'hub@suppliers.com',
            ],
            [
                'name'  => 'Aroma Distribution',
                'email' => 'aroma@suppliers.com',
            ],
            [
                'name'  => 'Pink Line Trading',
                'email' => 'pinkline@suppliers.com',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['email' => $supplier['email']],
                [
                    'name'    => $supplier['name'],
                    'user_id' => $users->random()->id,
                ]
            );
        }

        $this->command->info('Suppliers seeded successfully.');
    }
}