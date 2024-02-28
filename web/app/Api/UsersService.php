<?php

namespace App\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UsersService {
    private $client;

    public function __construct(ClientService $client) {
        $this->client = $client;
    }

    public function listUsers()
    {
        $access_token = session()->get('access_token');

        $response = $this->client->get("/users", null, [
            'Authorization' => "Bearer {$access_token}",
        ]);

        if ($response->failed()) {
            throw new HttpException($response->status(), 'list users failed');
        }

        return $response->json();
    }

    public function getUser(string $id)
    {
        $access_token = session()->get('access_token');

        $response = $this->client->get("/users/{$id}", null, [
            'Authorization' => "Bearer {$access_token}",
        ]);

        if ($response->failed()) {
            throw new HttpException($response->status(), 'get user failed');
        }

        return $response->json();
    }

    public function deleteUser(string $id)
    {
        $access_token = session()->get('access_token');

        $response = $this->client->delete("/users/{$id}", null, [
            'Authorization' => "Bearer {$access_token}",
        ]);

        if ($response->failed()) {
            throw new HttpException($response->status(), 'delete user failed');
        }

        return $response->json();
    }
}
