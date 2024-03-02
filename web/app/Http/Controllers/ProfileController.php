<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Http\Requests\ProfileUpdateRequest;
use App\Api\UsersService;

class ProfileController extends Controller
{
    public function __construct(
        private UsersService $usersService
    ) {
        $this->usersService = $usersService;
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $response = $this->usersService->updateUser($request->user()->id, $request->validated());

        return response()->json([
            'message' => 'Profile Updated',
            'data' => $response['data'],
        ]);
    }
}
