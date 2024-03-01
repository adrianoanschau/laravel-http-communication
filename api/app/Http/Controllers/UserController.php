<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    )
    {
        $this->userService = $userService;
    }

    /**
     * Get a Collection of Users
     *
     * @param  Request $request
     * @return App\Http\Resources\UserCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer',
            'per_page' => 'integer',
        ]);

        return $this->userService->all(
            $request->has('page'),
            $request->get('per_page')
        );
    }

    /**
     * Get one User
     *
     * @param  string $id
     * @return App\Http\Resources\UserResource
     */
    public function show(string $id)
    {
        return $this->userService->find($id);
    }

    /**
     * Store one User
     *
     * @param  UserStoreRequest $request
     * @return App\Http\Resources\UserResource
     */
    public function store(UserStoreRequest $request)
    {
        return $this->userService->create($request->all());
    }

    /**
     * Update one User
     *
     * @param  UserUpdateRequest $request
     * @param  string $id
     * @return App\Http\Resources\UserResource
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        return $this->userService->update($request->all(), $id);
    }

    /**
     * Delete one User
     *
     * @param  string $id
     * @return App\Http\Resources\UserResource
     */
    public function destroy(string $id)
    {
        return $this->userService->delete($id);
    }

    /**
     * Delete a list of Users
     *
     * @param  string $ids //separated by semicolon
     * @return App\Http\Resources\UserCollection
     */
    public function destroyBulk(string $ids)
    {
        return $this->userService->bulkDelete($ids);
    }
}
