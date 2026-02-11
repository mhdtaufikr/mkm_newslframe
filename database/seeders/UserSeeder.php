<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin System',
            'email' => 'admin@example.com',
            'email_verified_at' => null,
            'password' => Hash::make('Password.1'),
            'username' => 'admin',
            'role' => 'admin',
            'is_active' => 1,
            'last_login' => null,
            'login_counter' => 0,
            'remember_token' => null,
        ]);
    }
}
