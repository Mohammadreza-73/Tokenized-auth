<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use TokenizedLogin\Facades\TokenStoreFacade;
use TokenizedLogin\Facades\TokenSenderFacade;

Route::get('/two-factor-auth/request-token', 'TokenSenderController@issueToken')
    ->name('2factor.requestToken');

Route::get('/two-factor-auth/login', 'TokenSenderController@loginWithToken')
    ->name('2factor.login');


if (app()->environment('local')) {
    
    Route::get('/test/token-notif', function () {
        User::unguard();
        $data = ['id' => 1, 'email' => 'mohammadreza.rahimi93@yahoo.com'];

        $user = new User($data);
        TokenSenderFacade::send($user, '123456');
    });

    Route::get('test/token-storage', function () {
        config()->set('two_factor_auth_config.token_ttl', 3);

        TokenStoreFacade::saveToken('1q2ew', 1);
        sleep(1);

        $uid = TokenStoreFacade::getUidByToken('1q2ew')->getOr(null);
        if ($uid != 1) {
            dd('Some problem with reading.');
        }

        $uid = TokenStoreFacade::getUidByToken('1q2ew')->getOr(null);
        if (! is_null($uid)) {
            dd('Some problem with reading.');
        }

        config()->set('two_factor_auth_config.token_ttl', 1);

        TokenStoreFacade::saveToken('1q2ew', 1);
        sleep(1.1);

        $uid = TokenStoreFacade::getUidByToken('1q2ew')->getOr(null);
        if (is_null($uid)) {
            dd('Some problem with reading.');
        }

        dd('Cache store seems to be OK');
    });

}