<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Utwórz super admina tylko jeśli nie istnieje
        if (!User::where('email', 'admin@medvita.pl')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@medvita.pl',
                'password' => Hash::make('Admin123!'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]);
        }
    }
}
