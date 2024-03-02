<?php

namespace App\Api;

use Illuminate\Contracts\Auth\UserProvider as AuthUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\GenericUser;

class UserProvider implements AuthUserProvider
{
    private UsersService $usersService;

    private AuthService $authService;

    public function __construct(ClientService $clientService) {
        $this->usersService = new UsersService($clientService);
        $this->authService = new AuthService($clientService);
    }

    /**
     * Retrieve a User by Id
     *
     * @param  mixed $identifier
     * @return GenericUser
     */
    public function retrieveById($identifier) {
        $data = $this->usersService->getUser($identifier)['data'];
        $data['remember_token'] = false;

        return new GenericUser($data);
    }

    /**
     * disabled method
     */
    public function retrieveByToken($identifier, $token) {
        //
    }

    /**
     * disabled method
     */
    public function updateRememberToken(Authenticatable $user, $token) {
        //
    }

    /**
     * Retrieve a User by Credentials
     *
     * @param  array $credentials
     * @return GenericUser
     */
    public function retrieveByCredentials(array $credentials) {
        $data = $this->authService->login($credentials['username'], $credentials['password']);

        return new GenericUser([
            'id' => $data['user_id'],
            'token' => $data['access_token'],
            'username' => $credentials['username'],
        ]);
    }

    /**
     * Validate a User with Credentials
     *
     * @param  Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials) {
        return $user->username === $credentials['username'];
    }
}
