<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json(new UserCollection($users));
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());

        return response()->json(new UserResource($user));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return response()->json(new UserResource($user));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(new UserResource($user));
    }
}
