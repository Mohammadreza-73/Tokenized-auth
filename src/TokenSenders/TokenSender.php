<?php

namespace TokenizedLogin\TokenSenders;

use TokenizedLogin\LoginTokenNotification;
use Illuminate\Support\Facades\Notification;

class TokenSender
{
    public function send($user, $token)
    {
        Notification::sendNow($user, new LoginTokenNotification($token));
    }
}