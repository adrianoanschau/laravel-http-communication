<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Api\AuthService;
use App\Api\ClientService;
use App\Api\UserProvider;
use App\Api\UsersService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::provider('api-user', function (Application $app, array $config) {
            return new UserProvider(new ClientService());
        });
    }
}
