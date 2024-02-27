<?php

namespace Tests\Feature;

use Tests\TestCase;

class WelcomeTest extends TestCase
{
    public function test_returns_app_name_and_version(): void
    {
        $response = $this->getJson('/');

        $response->assertStatus(200)
            ->assertJsonPath('name', config('app.name'))
            ->assertJsonPath('version', config('app.version'));
    }
}
