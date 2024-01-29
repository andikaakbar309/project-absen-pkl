<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => 'dfa280e0-91f4-4e98-b4d8-0bdff05f043f',
            'name' => 'Andika',
            'username' => 'admin1',
            'email' => 'andika@demo.com',
            'password' => Hash::make('demo'),
            'email_verified_at' => now(),
        ]);
    }
}
