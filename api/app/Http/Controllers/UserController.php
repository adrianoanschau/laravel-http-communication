<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::paginate($request->get('per_page'));

        return new UserCollection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $storeUserData = $request->validate([
            "email" => "required|string|email|unique:users",
            "firstname" => "string",
            "lastname" => "string",
        ]);

        $user = User::create($storeUserData);

        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $updateUserData = $request->validate([
            "email" => "string|email|unique:users",
            "firstname" => "string",
            "lastname" => "string",
            'reset_password' => "boolean"
        ]);

        if (isset($updateUserData['reset_password']) && $updateUserData['reset_password']) {
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
