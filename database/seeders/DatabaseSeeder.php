<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'role' => User::ROLES['Admin'],
                'password' => Hash::make('password'),
            ]);
            User::factory()->create([
                'name' => 'Owner',
                'email' => 'owner@gmail.com',
                'role' => User::ROLES['Owner'],
                'password' => Hash::make('password'),
            ]);

        }
    }
}
