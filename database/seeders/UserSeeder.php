<?php

namespace Database\Seeders;

use App\Enums\UserRole;
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
        // Create Admin user
        User::factory()->admin()->create([
            'name'  => 'Admin User',
            'email' => 'admin@quizzy.test',
        ]);

        // Create Host user
        User::factory()->host()->create([
            'name'  => 'Host User',
            'email' => 'host@quizzy.test',
        ]);

        // Create Participant users
        User::factory()->count(3)->create([
            'role'     => UserRole::Participant,
            'password' => Hash::make('password'),
        ]);

        // Create Viewer user
        User::factory()->create([
            'name'  => 'Viewer User',
            'email' => 'viewer@quizzy.test',
            'role'  => UserRole::Viewer,
        ]);
    }
}
