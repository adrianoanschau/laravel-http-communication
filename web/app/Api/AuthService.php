<?php

namespace App\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService {
    private $client;

    public function __construct(ClientService $client) {
        $this->client = $client;
    }

    public function register(array $data)
    {
        $response = $this->client->post('/register', $data);

        if ($response->failed()) {
            throw new HttpException($response->status(), 'register failed');
        }

        return $response;
    }

    public function login(string $username, string $password)
    {
        $response = $this->client->post('/login', [
            'username' => $username,
            'password' => $password,
        ]);

        if ($response->failed()) {
            throw new HttpException($response->status(), 'login failed');
        }

        session()->put('access_token', $response->json('access_token'));

        return $response->json();
    }

    public function registerAndLogin(array $data)
    {
        $this->register($data);

        return $this->login($data['username'], $data['password']);
    }
}
