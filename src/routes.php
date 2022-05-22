<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('tokenized_auth.route_prefix_url'))->middleware(config('tokenized_auth.throttler_middleware'))->group(function () {

    Route::get('/request-token', 'TokenSenderController@issueToken')
        ->name('2factor.requestToken');

    Route::get('/login', 'TokenSenderController@loginWithToken')
        ->name('2factor.login');

});
