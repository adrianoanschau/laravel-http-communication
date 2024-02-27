<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\User;
use App\Models\PersonalAccessToken;

class UserAuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_register_a_new_user(): void
    {
        $response = $this->postJson('/register', [
            "firstname"=> "Test",
            "lastname"=> "User",
            'username'=> 'testuser',
            'email'=> 'testuser@example.com',
            'password'=> 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'User Created');
    }

    public function test_login_a_user(): void
    {
        $username = "testuser";
        $password = "password";

        $user = User::factory()->create([
            "firstname"=> "Test",
            "lastname"=> "User",
            "email"=> "testuser@example.com",
            'email_verified_at' => now(),
            "username" => $username,
            "password"=> Hash::make($password),
            'remember_token' => Str::random(10),
        ]);

        $response = $this->postJson('/login', [
            'username' => $username,
            'password' => $password,
        ]);

        $personalAccessToken = PersonalAccessToken::findToken($response->json('access_token'));

        $response->assertStatus(200);

        $this->assertEquals($user->firstname, $personalAccessToken->tokenable->firstname);
        $this->assertEquals($user->lastname, $personalAccessToken->tokenable->lastname);
        $this->assertEquals($user->email, $personalAccessToken->tokenable->email);
        $this->assertEquals($user->username, $personalAccessToken->tokenable->username);
    }

    public function test_login_with_invalid_credentials(): void
    {
        $username = "testuser";
        $password = "password";

        User::factory()->create([
            "firstname"=> "Test",
            "lastname"=> "User",
            "email"=> "testuser@example.com",
            'email_verified_at' => now(),
            "username" => $username,
            "password"=> Hash::make($password),
            'remember_token' => Str::random(10),
        ]);

        $response = $this->postJson('/login', [
            "username" => $username,
            'password' => "anotherpassword",
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('message', 'Invalid Credentials');
    }

    public function test_logout_a_user(): void
    {
        $username = "testuser";
        $password = "password";

        $user = User::factory()->create([
            "firstname"=> "Test",
            "lastname"=> "User",
            "email"=> "testuser@example.com",
            'email_verified_at' => now(),
            "username" => $username,
            "password"=> Hash::make($password),
            'remember_token' => Str::random(10),
        ]);

        $token = $user->createToken($user->username."-AuthToken")->plainTextToken;

        $response = $this->postJson('/logout', [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'logged out');

        $this->assertTrue($user->fresh()->tokens->isEmpty());
    }
}
