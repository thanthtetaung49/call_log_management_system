<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(50)->create();

        User::create([
            'name' => 'super admin',
            'email' => 'super_admin@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'status' => 'approved',
        ]);
    }
}
