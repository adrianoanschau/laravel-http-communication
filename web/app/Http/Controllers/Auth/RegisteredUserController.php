<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Api;

class RegisteredUserController extends Controller
{
    private Api\AuthService $apiAuthService;

    public function __construct(Api\AuthService $apiAuthService) {
        $this->apiAuthService = $apiAuthService;
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = $this->apiAuthService->registerAndLogin([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
        ]);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
