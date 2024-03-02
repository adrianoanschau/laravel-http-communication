<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        return response()->json([
            'data' => $this->userService->all(
                $request->has('page'),
                $request->get('per_page')
            ),
        ]);
    }

    /**
     * Get one User
     *
     * @param  string $id
     * @return App\Http\Resources\UserResource
     */
    public function show(string $id)
    {
        return response()->json([
            'data' => $this->userService->find($id),
        ]);
    }

    /**
     * Store one User
     *
     * @param  UserStoreRequest $request
     * @return App\Http\Resources\UserResource
     */
    public function store(UserStoreRequest $request)
    {
        return response()->json([
            'message' => 'User Created',
            'data' => $this->userService->create($request->all()),
        ], Response::HTTP_CREATED);
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
        return response()->json([
            'message' => 'User Updated',
            'data' => $this->userService->update($request->all(), $id),
        ]);
    }

    /**
     * Delete one User
     *
     * @param  string $id
     * @return App\Http\Resources\UserResource
     */
    public function destroy(string $id)
    {
        return response()->json([
            'message' => 'User Deleted',
            'data' => $this->userService->delete($id),
        ]);
    }

    /**
     * Delete a list of Users
     *
     * @param  string $ids //separated by semicolon
     * @return App\Http\Resources\UserCollection
     */
    public function destroyBulk(string $ids)
    {
        return response()->json([
            'message' => 'Users Deleted',
            'data' => $this->userService->bulkDelete($ids),
        ]);
    }
}
