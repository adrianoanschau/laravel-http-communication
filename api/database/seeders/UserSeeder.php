<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'username' => 'root',
            'password' => Hash::make('password'),
            'admin' => true,
        ]);

        \App\Models\User::factory(185)->create();
    }
}
