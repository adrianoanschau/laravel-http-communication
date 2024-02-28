<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|email',
        ]);

        return $this->usersService->storeUser($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'firstname' => 'string',
            'lastname' => 'string',
            'email' => 'string|email',
        ]);

        return $this->usersService->updateUser($id, $data);
    }

    public function destroy(string $id)
    {
        return $this->usersService->deleteUser($id);
    }
}
