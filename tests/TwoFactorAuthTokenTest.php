<?php

namespace TokenizedLogin;

use TokenizedLogin\TestCase;
use Illuminate\Foundation\Auth\User;
use TokenizedLogin\Facades\AuthFacade;
use TokenizedLogin\Http\ResponderFacade;
use TokenizedLogin\Facades\TokenStoreFacade;
use TokenizedLogin\Facades\TokenSenderFacade;
use TokenizedLogin\Facades\UserProviderFacade;
use TokenizedLogin\Facades\TokenGeneratorFacade;

class TwoFactorAuthTokenTest extends TestCase
{
    public function test_the_happy_path()
    {
        User::unguard();

        UserProviderFacade::shouldReceive('isBanned')
            ->once()
            ->with(1)
            ->andReturn(false);
        
        $user = new User(['id' => 1, 'email' => 'foo@bar.com']);
        UserProviderFacade::shouldReceive('getUserbyEmail')
            ->once()
            ->with('foo@bar.com')
            ->andReturn(nullable($user));

        TokenGeneratorFacade::shouldReceive('generateToken')
            ->once()
            ->withNoArgs()
            ->andReturn('3$toqs');

        TokenStoreFacade::shouldReceive('saveToken')
            ->once()
            ->with('3$toqs', $user->id);

        TokenSenderFacade::shouldReceive('send')
            ->once()
            ->with('3$toqs', $user);
        
        ResponderFacade::shouldReceive('tokenSent')->once();

        $this->get('/api/tokenized-login/request-token?email=foo@bar.com');
    }

    public function test_user_is_banned()
    {
        User::unguard();
        
        UserProviderFacade::shouldReceive('isBanned')
            ->once()
            ->with(1)
            ->andReturn(true);
        
        $user = new User(['id' => 1, 'email' => 'foo@bar.com']);
        UserProviderFacade::shouldReceive('getUserByEmail')
            ->once()
            ->with('foo@bar.com')
            ->andReturn(nullable($user));

        TokenGeneratorFacade::shouldReceive('generateToken')->never();

        TokenStoreFacade::shouldReceive('saveToken')->never();

        TokenSenderFacade::shouldReceive('send')->never();

        $response = $this->get('/api/tokenized-login/request-token?email=foo@bar.com');

        $response->assertStatus(400);
        $response->assertJson(['error' => 'You are blocked']);
    }

    public function test_user_does_not_exists()
    {
        UserProviderFacade::shouldReceive('getUserByEmail')
            ->once()
            ->with('foo@bar.com')
            ->andReturn(nullable(null));

        UserProviderFacade::shouldReceive('isBanned')->never();
        TokenGeneratorFacade::shouldReceive('generateToken')->never();
        TokenStoreFacade::shouldReceive('saveToken')->never();
        ResponderFacade::shouldReceive('userNotFound')
            ->once()
            ->andReturn(response('hello'));

        $response = $this->get('/api/tokenized-login/request-token?email=foo@bar.com');
        $response->assertSee('hello');
    }

    public function test_email_not_valid()
    {
        UserProviderFacade::shouldReceive('getUserByEamil')->never();
        UserProviderFacade::shouldReceive('isBanned')->never();
        TokenGeneratorFacade::shouldReceive('generateToken')->never();
        TokenStoreFacade::shouldReceive('saveToken')->never();
        ResponderFacade::shouldReceive('emailNotValid')
            ->once()
            ->andReturn(response('hello'));

        $response = $this->get('/api/tokenized-login/request-token?email=foo_bar.com');
        $response->assertSee('hello');
    }

    public function test_user_is_guest()
    {
        AuthFacade::shouldReceive('check')
            ->once()
            ->andReturn(true);
        
        UserProviderFacade::shouldReceive('getUserByEamil')->never();
        UserProviderFacade::shouldReceive('isBanned')->never();
        TokenGeneratorFacade::shouldReceive('generateToken')->never();
        TokenStoreFacade::shouldReceive('saveToken')->never();
        ResponderFacade::shouldReceive('checkUserIsGuest')
            ->once()
            ->andReturn(response('hello'));

        $response = $this->get('/api/tokenized-login/request-token?email=foo@bar.com');
        $response->assertSee('hello');
    }
}