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

    public function list()
    {
        $columns = [
            ['field' => 'id', 'width' => 0],
            ['field' => 'username', 'label' => 'Username', 'width' => 180, 'fixed' => true ],
            ['field' => 'firstname', 'label' => 'First Name', 'width' => 150 ],
            ['field' => 'lastname', 'label' => 'Last Name', 'width' => 150 ],
            ['field' => 'email', 'label' => 'Email', 'width' => 260 ],
            ['field' => 'admin', 'label' => 'Admin', 'width' => 100 ],
            ['field' => 'created_at', 'label' => 'Created At', 'width' => 200, 'type' => 'date|dd/MM/yyyy HH:ii:ss' ],
            ['field' => 'updated_at', 'label' => 'Updated At', 'width' => 200, 'type' => 'date|dd/MM/yyyy HH:ii:ss'  ],
            ['field' => 'actions', 'sort' => false, 'width' => 120, 'fixed' => 'right' ],
        ];

        return view('users.index', compact('columns'));
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

    public function destroyBulk(string $ids)
    {
        return $this->usersService->deleteUsers($ids);
    }
}
