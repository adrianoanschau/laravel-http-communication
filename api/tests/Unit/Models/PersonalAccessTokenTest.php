<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use App\Models\PersonalAccessToken;
use App\Models\User;

class PersonalAccessTokenTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_table_has_expected_columns()
    {
        $this->assertTrue(
          Schema::hasColumns('personal_access_tokens', [
            'id', 'tokenable_type', 'tokenable_id', 'name', 'token', 'abilities', 'last_used_at', 'expires_at', 'created_at', 'updated_at'
        ]), 1);
    }

    public function test_create_a_personal_token()
    {
        $user = User::first();

        $userToken = $user->createToken($user->username."-AuthToken");

        $token = PersonalAccessToken::first();

        $this->assertEquals($userToken->accessToken->token, $token->token);
    }
}
