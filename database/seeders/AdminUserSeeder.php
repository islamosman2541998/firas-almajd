<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@firasalmajd.com'],
            ['name' => 'Firas Al Majd', 'password' => 'Admin@12345', 'locale' => 'ar', 'is_active' => true]
        );
    }
}
