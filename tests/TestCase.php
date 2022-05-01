<?php

namespace TokenizedLogin;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [\TokenizedLogin\TwoFactorAuthServiceProvider::class];
    }
}