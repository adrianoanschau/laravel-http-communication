<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserAuthController extends Controller
{
    public function register(Request $request){
        $registerUserData = $request->validate([
            "firstname" => "required|string",
            "lastname" => "required|string",
            "email" => "required|string|email|unique:users",
            "username" => "required|string|unique:users|min:4",
            "password" => "required|min:8"
        ]);

        $user = User::create([
            "firstname" => $registerUserData["firstname"],
            "lastname" => $registerUserData["lastname"],
            "email" => $registerUserData["email"],
            "username" => $registerUserData["username"],
            "password" => Hash::make($registerUserData["password"]),
        ]);

        return response()->json([
            "message" => "User Created",
        ], 201);
    }

    public function login(Request $request){
        $loginUserData = $request->validate([
            "username" => "required|string|min:4",
            "password" => "required|min:8"
        ]);

        $user = User::where("username", $loginUserData["username"])->first();

        if(!$user || !Hash::check($loginUserData["password"], $user->password)){
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
