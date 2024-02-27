<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_table_has_expected_columns()
    {
        $this->assertTrue(
          Schema::hasColumns('users', [
            'id', 'name', 'email', 'email_verified_at', 'password', 'created_at', 'updated_at'
        ]), 1);
    }

    public function test_create_user(): void
    {
        $user = User::factory()->create([
            "name"=> "Test User",
            "email"=> "testuser@example.com",
            'email_verified_at' => now(),
            "password"=> Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        $this->assertEquals("Test User", $user->name);
        $this->assertEquals("testuser@example.com", $user->email);
    }
}
