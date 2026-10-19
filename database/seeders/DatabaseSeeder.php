<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            ['name' => 'Reporter Demo', 'email' => 'reporter.demo@gmail.com', 'role' => UserRole::Reporter],
            ['name' => 'Developer Demo', 'email' => 'developer.demo@gmail.com', 'role' => UserRole::Developer],
        ] as $demoUser) {
            User::updateOrCreate(
                ['email' => $demoUser['email']],
                [
                    'name' => $demoUser['name'],
                    'role' => $demoUser['role'],
                    'password' => Hash::make('TwinBug@Demo2025!'),
                ],
            );
        }
    }
}
