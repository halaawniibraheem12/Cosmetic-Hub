<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12346789'),
                'is_admin' => true,
            ]
        );

        // Owners / Normal Users
        User::updateOrCreate(
            ['email' => 'mona.ali@gmail.com'],
            [
                'name' => 'Mona Ali',
                'password' => Hash::make('12346789'),
                'is_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['email' => 'lina.khaled@gmail.com'],
            [
                'name' => 'Lina Khaled',
                'password' => Hash::make('12346789'),
                'is_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['email' => 'sara.ahmed@gmail.com'],
            [
                'name' => 'Sara Ahmed',
                'password' => Hash::make('12346789'),
                'is_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['email' => 'noor.hassan@gmail.com'],
            [
                'name' => 'Noor Hassan',
                'password' => Hash::make('12346789'),
                'is_admin' => false,
            ]
        );
    }
}