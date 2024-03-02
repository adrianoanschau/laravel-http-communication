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

    /**
     * Display the users list view.
     */
    public function list()
    {
        $columns = [
            ['field' => 'id', 'width' => 0],
            ['field' => 'username', 'label' => 'Username', 'width' => 180, 'fixed' => true ],
            ['field' => 'firstname', 'label' => 'First Name', 'width' => 150 ],
            ['field' => 'lastname', 'label' => 'Last Name', 'width' => 150 ],
            ['field' => 'email', 'label' => 'Email', 'width' => 260 ],
            ['field' => 'admin', 'label' => 'Admin', 'width' => 100, 'permission' => 'admin' ],
            ['field' => 'created_at', 'label' => 'Created At', 'width' => 200, 'type' => 'date|dd/MM/yyyy HH:ii:ss' ],
            ['field' => 'updated_at', 'label' => 'Updated At', 'width' => 200, 'type' => 'date|dd/MM/yyyy HH:ii:ss'  ],
        ];

        return view('users.index', compact('columns'));
    }

    /**
     * Get a list of Users
     *
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
    public function index()
    {
        return $this->usersService->listUsers();
    }

    /**
     * Store one User
     *
     * @param  Request $request
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|email',
        ]);

        return $this->usersService->storeUser($data);
    }

    /**
     * Update one User
     *
     * @param  Request $request
     * @param  string $id
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'firstname' => 'string',
            'lastname' => 'string',
            'email' => 'string|email',
        ]);

        return $this->usersService->updateUser($id, $data);
    }

    /**
     * Delete one User
     *
     * @param  string $id
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
    public function destroy(string $id)
    {
        return $this->usersService->deleteUser($id);
    }

    /**
     * Delete a list of Users
     *
     * @param  string $ids //separated with semicolon
     * @return Illuminate\Http\Client\PendingRequest::asJson
     */
    public function destroyBulk(string $ids)
    {
        return $this->usersService->deleteUsers($ids);
    }
}
