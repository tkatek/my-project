<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $owner = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Agency Owner',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        // role is intentionally not mass-assignable (prevents privilege escalation).
        $owner->role = 'owner';
        $owner->save();
    }
}
