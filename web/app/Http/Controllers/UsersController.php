<?php

namespace App\Http\Controllers;

use App\Api\UsersService;

class UsersController extends Controller
{
    private UsersService $usersService;

    public function __construct(UsersService $usersService) {
        $this->usersService = $usersService;
    }

    public function index()
    {
        return $this->usersService->listUsers();
    }

    public function destroy(string $id)
    {
        return $this->usersService->deleteUser($id);
    }
}
