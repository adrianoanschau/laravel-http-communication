<?php

namespace App\Api;

use Illuminate\Contracts\Auth\UserProvider as AuthUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class UserProvider implements AuthUserProvider {

    private UsersService $usersService;
    private AuthService $authService;

    public function __construct(ClientService $clientService) {
        $this->usersService = new UsersService($clientService);
        $this->authService = new AuthService($clientService);
    }

    public function retrieveById($identifier) {
        return $this->usersService->getUser($identifier);
    }

    public function retrieveByToken($identifier, $token) {
        dd('userprovider#retrieveByToken', $identifier, $token);
    }

    public function updateRememberToken(Authenticatable $user, $token) {
        dd('userprovider#updateRememberToken', $user, $token);
    }

    public function retrieveByCredentials(array $credentials) {
        return $this->authService->login($credentials['username'], $credentials['password']);
    }

    public function validateCredentials(Authenticatable $user, array $credentials) {
        return $user->username === $credentials['username'];
    }
}
