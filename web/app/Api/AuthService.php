<?php

namespace App\Api;

use Illuminate\Auth\GenericUser;
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
            throw new HttpException(0, 'register failed');
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
            throw new HttpException(0, 'login failed');
        }

        $userId = $response->json('user_id');
        $token = $response->json('access_token');

        session()->put('access_token', $token);

        return new GenericUser([
            'id' => $userId,
            'token' => $token,
            'username' => $username,
        ]);
    }

    public function registerAndLogin(array $data)
    {
        $this->register($data);

        return $this->login($data['username'], $data['password']);
    }
}
