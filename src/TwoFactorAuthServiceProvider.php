<?php

namespace TokenizedLogin;

use Illuminate\Support\Facades\Route;
use TokenizedLogin\Facades\AuthFacade;
use Illuminate\Support\ServiceProvider;
use TokenizedLogin\TokenStores\TokenStore;
use TokenizedLogin\Facades\TokenStoreFacade;
use TokenizedLogin\TokenSenders\TokenSender;
use TokenizedLogin\Authenticator\SessionAuth;
use TokenizedLogin\Facades\TokenSenderFacade;
use TokenizedLogin\Facades\UserProviderFacade;
use TokenizedLogin\TokenStores\FakeTokenStore;
use TokenizedLogin\Facades\TokenGeneratorFacade;
use TokenizedLogin\Http\ResponderFacade;
use TokenizedLogin\Http\Responses\Responses;
use TokenizedLogin\TokenSenders\FakeTokenSender;
use TokenizedLogin\TokenGenerators\TokenGenerator;
use TokenizedLogin\TokenGenerators\FakeTokenGenerator;

class TwoFactorAuthServiceProvider extends ServiceProvider
{
    protected $namespace = 'TokenizedLogin\Http\Controllers';

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

        ResponderFacade::shouldProxyTo(Responses::class);
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
            ->group(__DIR__.'/routes.php');
    }
}