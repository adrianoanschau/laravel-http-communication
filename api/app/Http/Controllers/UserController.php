<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer',
            'per_page' => 'integer',
        ]);

        $users = User::paginate($request->get('per_page', 10));

        return new UserCollection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function store(UserStoreRequest $request)
    {
        $user = User::create($request->all());

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $updateUserData = $request->all();

        if ($updateUserData['reset_password']) {
            unset($updateUserData['reset_password']);

            $updateUserData['password'] = null;
        }

        $user->update($updateUserData);

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return new UserResource($user);
    }
}
