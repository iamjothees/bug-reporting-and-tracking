<?php

namespace Database\Seeders;

use App\Models\Bug;
use App\Models\User;
use App\UserRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'role' => UserRole::ADMIN,
            'email' => 'admin@example.com',
            'password' => Hash::make('admin@123'),
        ]);

        User::factory()->create([
            'name' => 'Test Reporter',
            'role' => UserRole::REPORTER,
            'email' => 'reporter@example.com',
            'password' => Hash::make('reporter@123'),
        ]);

        User::factory()->create([
            'name' => 'Test Developer',
            'role' => UserRole::DEVELOPER,
            'email' => 'developer@example.com',
            'password' => Hash::make('developer@123'),
        ]);

        Bug::factory(10)->create();
    }
}
