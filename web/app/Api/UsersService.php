<?php

namespace App\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UsersService
{
    public function __construct(
        private ClientService $client
    ) {
        $this->client = $client;
    }

    /**
     * Get list of Users
     *
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
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

    /**
     * Get one User
     *
     * @param  string $id
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
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

    /**
     * Store one User
     *
     * @param  array $data
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
    public function storeUser($data)
    {
        $access_token = session()->get('access_token');

        $response = $this->client->post("/users", $data, [
            'Authorization' => "Bearer {$access_token}",
        ]);

        if ($response->failed()) {
            throw new HttpException($response->status(), 'store user failed');
        }

        return $response->json();
    }

    /**
     * Update one User
     *
     * @param  string $id
     * @param  array $data
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
    public function updateUser(string $id, array $data)
    {
        $access_token = session()->get('access_token');

        $response = $this->client->patch("/users/{$id}", $data, [
            'Authorization' => "Bearer {$access_token}",
        ]);

        if ($response->failed()) {
            throw new HttpException($response->status(), 'update user failed');
        }

        return $response->json();
    }

    /**
     * Delete one User
     *
     * @param  string $id
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
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

    /**
     * Delete a list of Users
     *
     * @param  string $ids
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
    public function deleteUsers(string $ids)
    {
        $access_token = session()->get('access_token');

        $response = $this->client->delete("/users/bulk/{$ids}", null, [
            'Authorization' => "Bearer {$access_token}",
        ]);

        if ($response->failed()) {
            throw new HttpException($response->status(), 'bulk delete users failed');
        }

        return $response->json();
    }
}
