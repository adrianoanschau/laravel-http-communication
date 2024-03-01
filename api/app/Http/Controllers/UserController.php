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

    public function show(string $id)
    {
        return $this->userService->find($id);
    }

    public function store(UserStoreRequest $request)
    {
        return $this->userService->create($request->all());
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        return $this->userService->update($request->all(), $id);
    }

    public function destroy(string $id)
    {
        return $this->userService->delete($id);
    }

    public function destroyBulk(string $ids)
    {
        return $this->userService->bulkDelete($ids);
    }
}
