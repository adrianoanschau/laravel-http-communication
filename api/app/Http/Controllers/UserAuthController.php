<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use App\Services\AuthService;

class UserAuthController extends Controller
{
    public function __construct(
        private UserService $userService,
        private AuthService $authService,
    )
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * Register a User
     *
     * @param  RegisterRequest $request
     * @return [
     *      "message"   => "User Created",
     *      "user"      => App\Models\User
     * ]
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->userService->create($request->all());

        return response()->json([
            "message" => "User Created",
            "data" => $user,
        ], 201);
    }

    /**
     * Create a Access Token for a User
     *
     * @param  LoginRequest $request
     * @return [
     *      "access_token"  => "string",
     *      "user_id"       => "string"
     * ]
     */
    public function login(LoginRequest $request)
    {
        $response = $this->authService->login(
            $request->get("username"),
            $request->get("password"),
        );

        return response()->json([
            "access_token" => $response['token'],
            "user_id" => $response['user']->id,
        ]);
    }

    /**
     * Delete a Access Token for a User
     *
     * @return [
     *      "message"  => "logged out"
     * ]
     */
    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
          "message" => "logged out"
        ]);
    }
}
