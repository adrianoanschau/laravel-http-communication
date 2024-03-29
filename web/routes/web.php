<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->to('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/list/users', [UsersController::class, 'list'])->name('users.list');
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');

    Route::middleware('is-admin')->group(function () {
        Route::resource('users', UsersController::class)->only(['store', 'update', 'destroy']);
        Route::delete('/users/bulk/{ids}', [UsersController::class, 'destroyBulk'])->name('users.destroy.bulk');
    });
});

require __DIR__.'/auth.php';
