<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

use App\Repositories\UserRepository;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * Create one Access Token for a User
     *
     * @param  string $username
     * @param  string $password
     * @return [
     *      'token' => 'string',
     *      'user'  => App\Models\User
     * ]
     */
    public function login(string $username, string $password)
    {
        $user = $this->userRepository->findByUsername($username);

        if(!$user || !Hash::check($password, $user->password)){
            throw new AuthenticationException;
        }

        $abilities = [];

        if ($user->isAdmin()) {
            $abilities[] = 'admin';
        }

        $token = $user->createToken($user->username."-AuthToken", $abilities)->plainTextToken;

        return [ 'token' => $token, 'user' => $user ];
    }
}
