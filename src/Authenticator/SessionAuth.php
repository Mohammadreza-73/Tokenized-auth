<?php

namespace TwoFactorAuth\Authenticator;

use Illuminate\Support\Facades\Auth;

class SessionAuth
{
    public function check()
    {
        return Auth::check();
    }

    public function loginById(int $uid)
    {
        auth()->loginUsingId($uid);
    }
}