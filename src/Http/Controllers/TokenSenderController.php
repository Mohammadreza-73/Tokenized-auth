<?php

namespace TwoFactorAuth\Http\Controllers;

use Illuminate\Routing\Controller;
use TwoFactorAuth\Facades\AuthFacade;
use TwoFactorAuth\Http\ResponderFacade;
use Illuminate\Support\Facades\Validator;
use TwoFactorAuth\Facades\TokenStoreFacade;
use TwoFactorAuth\Facades\TokenSenderFacade;
use TwoFactorAuth\Facades\UserProviderFacade;
use TwoFactorAuth\Facades\TokenGeneratorFacade;

class TokenSenderController extends Controller
{
    /**
     * Generate token and send notification to user
     *
     * @return \ResponderFacade token
     */
    public function issueToken()
    {
        $email = request('email');

        # Validate email
        $this->validateEmailIsValid();

        # Validate user is guest (do not send token for logged in users)
        $this->checkUserIsGuest();

        # Throttle the route
        
        # Find user row in DB of fail
        /**
         * @var \Imanghafoori\Helpers\Nullable $user
         */
        $user = UserProviderFacade::getUserByEmail($email)->getOrSend(
            [ResponderFacade::class, 'userNotFound']
        );
        
        # 1. stop block users
        if (UserProviderFacade::isBanned($user->id)) {
            return ResponderFacade::blockedUser();
        }
        
        # 2. Generate token
        $token = TokenGeneratorFacade::generateToken();
        
        # 3. Save token
        TokenStoreFacade::saveToken($token, $user->id);

        # 4. Send Token
        TokenSenderFacade::send($token, $user);

        # 5. Send Response
         return ResponderFacade::tokenSent();
    }

    /**
     * Login user by Token
     *
     * @return void
     */
    public function loginWithToken()
    {
        $token = request('token');

        /**
         * @var \Imanghafoori\Helpers\Nullable $user
         */
        $uid = TokenStoreFacade::getUidByToken($token)->getOrSend(
            [ResponderFacade::class, 'tokenNotFound']
        );

        AuthFacade::loginById($uid);

        return ResponderFacade::loggedIn();
    }

    /**
     * Validate user Email
     *
     * @return void
     */
    private function validateEmailIsValid()
    {
        $validator = Validator::make(request()->all(), ['email' => 'required|email']);

        if ($validator->fails()) {
            ResponderFacade::emailNotValid()->throwResponse();
        }
    }

    /**
     * Check user whether is login
     *
     * @return boolean
     */
    private function checkUserIsGuest()
    {
        if (AuthFacade::check()) {
            ResponderFacade::checkUserIsGuest()->throwResponse();
        }
    }
}