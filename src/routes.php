<?php

use Illuminate\Support\Facades\Route;

Route::get('/two-factor-auth/request-token', 'TokenSenderController@issueToken')
    ->name('2factor.requestToken');

Route::get('/two-factor-auth/login', 'TokenSenderController@loginWithToken')
    ->name('2factor.login');
