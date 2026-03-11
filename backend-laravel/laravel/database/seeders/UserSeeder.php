<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'John Manager',
            'email' => 'john@example.com',
            // 'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Alice Developer',
            'email' => 'alice@example.com',
            // 'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Bob Developer',
            'email' => 'bob@example.com',
            // 'password' => bcrypt('password')
        ]);
    }
}
