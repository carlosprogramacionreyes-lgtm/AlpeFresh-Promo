<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['username' => 'creyes'],
            [
                'full_name' => 'Carlos Reyes',
                'email' => 'creyes@example.com',
                'phone' => null,
                'role' => UserRole::Admin,
                'is_active' => true,
                'password' => Hash::make('carina2230'),
            ],
        );
    }
}
