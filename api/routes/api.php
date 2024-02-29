<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the 'api' middleware group. Make something great!
|
*/
Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'version' => config('app.version')
    ]);
});

Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', function (Request $request) {
        return $request->user();
    });

    Route::post('logout', [UserAuthController::class, 'logout']);

    Route::middleware('ability:admin')
        ->resource('users', UserController::class)
        ->only(['index', 'store', 'destroy']);

    Route::middleware('is-owner-or-admin')
        ->resource('users', UserController::class)
        ->only(['show', 'update']);

    Route::middleware('ability:admin')
        ->delete('users/bulk/{ids}', [UserController::class, 'destroyBulk']);
});
