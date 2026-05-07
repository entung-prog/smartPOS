<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Minimal seed: create an initial admin user (local/dev convenience).
        User::firstOrCreate(
            ['email' => 'admin@smartpos.test'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ],
        );
    }
}
