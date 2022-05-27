<?php

use TokenizedLogin\Http\Controllers\TokenSenderController;

Route::prefix(config('tokenized_auth.route_prefix_url'))->middleware(config('tokenized_auth.throttler_middleware'))->group(function () {

    Route::post('/request-token', [TokenSenderController::class, 'issueToken'])
        ->name('2factor.requestToken');

    Route::post('/login', [TokenSenderController::class, 'loginWithToken'])
        ->name('2factor.login');

});
