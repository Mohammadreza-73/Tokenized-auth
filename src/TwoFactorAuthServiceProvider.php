<?php

namespace TwoFactorAuth;

use TwoFactorAuth\TokenStores\TokenStore;
use TwoFactorAuth\TokenStores\FakeTokenStore;
use Illuminate\Support\Facades\Route;
use TwoFactorAuth\Facades\AuthFacade;
use Illuminate\Support\ServiceProvider;
use TwoFactorAuth\Facades\TokenStoreFacade;
use TwoFactorAuth\TokenSenders\TokenSender;
use TwoFactorAuth\Authenticator\SessionAuth;
use TwoFactorAuth\Facades\TokenSenderFacade;
use TwoFactorAuth\Facades\UserProviderFacade;
use TwoFactorAuth\Facades\TokenGeneratorFacade;
use TwoFactorAuth\TokenSenders\FakeTokenSender;
use TwoFactorAuth\TokenGenerators\FakeTokenGenerator;

class TwoFactorAuthServiceProvider extends ServiceProvider
{
    protected $namespace = 'TwoFactorAuth\Http\Controllers';

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/two_factor_auth_config.php', 'two_factor_auth');

        AuthFacade::shouldProxyTo(SessionAuth::class);
        UserProviderFacade::shouldProxyTo(UserProvider::class);

        if (app()->runningUnitTests()) {
            $tokenGenerator = FakeTokenGenerator::class;
            $tokenStore = FakeTokenStore::class;    
            $tokenSender = FakeTokenSender::class;

        } else {
            $tokenGenerator = TokenGenerator::class;
            $tokenStore = TokenStore::class;
            $tokenSender = TokenSender::class;
        }

        TokenGeneratorFacade::shouldProxyTo($tokenGenerator);
        TokenStoreFacade::shouldProxyTo($tokenStore);
        TokenSenderFacade::shouldProxyTo($tokenSender);
    }

    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            $this->defineRoutes();
        }
    }

    private function defineRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('two_factor_auth/routes.php'));
    }
}