<?php

namespace App\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService {
    private $client;

    public function __construct(ClientService $client) {
        $this->client = $client;
    }

    /**
     * Register a User
     *
     * @param  array $data
     * @return Illuminate\Http\Client\Response
     */
    public function register(array $data)
    {
        $response = $this->client->post('/register', $data);

        if ($response->failed()) {
            throw new HttpException($response->status(), 'register failed');
        }

        return $response;
    }

    /**
     * Log In a User
     *
     * @param  string $username
     * @param  string $password
     * @return Illuminate\Http\Client\Response
     */
    public function login(string $username, string $password)
    {
        $response = $this->client->post('/login', [
            'username' => $username,
            'password' => $password,
        ]);

        if ($response->failed()) {
            throw new HttpException($response->status(), 'login failed');
        }

        $data = $response->json('data');

        session()->put('access_token', $data['access_token']);

        return $data;
    }

    /**
     * Register and Log In a User
     *
     * @param  array $data
     * @return Illuminate\Http\Client\Response
     */
    public function registerAndLogin(array $data)
    {
        $this->register($data);

        return $this->login($data['username'], $data['password']);
    }
}
