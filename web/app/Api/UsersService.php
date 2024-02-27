<?php

namespace App\Api;

use Illuminate\Auth\GenericUser;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UsersService {
    private $client;

    public function __construct(ClientService $client) {
        $this->client = $client;
    }

    public function getUser(string $id)
    {
        $access_token = session()->get('access_token');

        $response = $this->client->get("/users/{$id}", [
            'Authorization' => "Bearer {$access_token}",
        ]);

        if ($response->failed()) {
            throw new HttpException(0, 'get user failed');
        }

        return new GenericUser($response->json('data'));
    }
}
