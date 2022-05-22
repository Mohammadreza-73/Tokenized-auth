<?php

namespace TokenizedLogin;

use Illuminate\Support\Facades\Route;
use TokenizedLogin\Facades\AuthFacade;
use Illuminate\Support\ServiceProvider;
use TokenizedLogin\Http\ResponderFacade;
use TokenizedLogin\TokenStores\TokenStore;
use TokenizedLogin\Facades\TokenStoreFacade;
use TokenizedLogin\Authenticator\SessionAuth;
use TokenizedLogin\Facades\TokenSenderFacade;
use TokenizedLogin\Facades\UserProviderFacade;
use TokenizedLogin\TokenStores\FakeTokenStore;
use TokenizedLogin\Facades\TokenGeneratorFacade;
use TokenizedLogin\TokenSenders\FakeTokenSender;
use TokenizedLogin\TokenGenerators\FakeTokenGenerator;

class TwoFactorAuthServiceProvider extends ServiceProvider
{
    private $namespace = 'TokenizedLogin\Http\Controllers';

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/tokenized_auth.php', 'tokenized_auth');

        AuthFacade::shouldProxyTo(SessionAuth::class);
        UserProviderFacade::shouldProxyTo(config('tokenized_auth.user_provider'));

        if (app()->runningUnitTests()) {
            $tokenGenerator = FakeTokenGenerator::class;
            $tokenStore = FakeTokenStore::class;    
            $tokenSender = FakeTokenSender::class;

        } else {
            $tokenGenerator = config('tokenized_auth.token_generator');
            $tokenStore = TokenStore::class;
            $tokenSender = config('tokenized_auth.token_sender');
        }

        ResponderFacade::shouldProxyTo(config('tokenized_auth.responses'));
        TokenGeneratorFacade::shouldProxyTo($tokenGenerator);
        TokenStoreFacade::shouldProxyTo($tokenStore);
        TokenSenderFacade::shouldProxyTo($tokenSender);
    }

    public function boot()
    {
        if (! $this->app->routesAreCached() && config('tokenized_auth.use_default_routes')) {
            $this->defineRoutes();
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config' => $this->app->configPath(),
            ], 'tokenized_auth');
        }
    }

    private function defineRoutes()
    {
        Route::prefix('api')
            ->middleware(config('tokenized_auth.route_middlewares'))
            ->namespace($this->namespace)
            ->group(__DIR__.'/routes.php');
    }
}