<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class UserAuthController extends Controller
{
    public function register(RegisterRequest $request){
        User::create([
            "firstname" => $request->get("firstname"),
            "lastname" => $request->get("lastname"),
            "email" => $request->get("email"),
            "username" => $request->get("username"),
            "password" => Hash::make($request->get("password")),
        ]);

        return response()->json([
            "message" => "User Created",
        ], 201);
    }

    public function login(LoginRequest $request){

        $user = User::where("username", $request->get("username"))->first();

        if(!$user || !Hash::check($request->get("password"), $user->password)){
            return response()->json([
                "message" => "Invalid Credentials"
            ], 401);
        }

        $abilities = [];

        if ($user->isAdmin()) {
            $abilities[] = 'admin';
        }

        $token = $user->createToken($user->username."-AuthToken", $abilities)->plainTextToken;

        return response()->json([
            "access_token" => $token,
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
          "message" => "logged out"
        ]);
    }
}
