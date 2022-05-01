<?php

namespace TokenizedLogin\TokenGenerators;

class FakeTokenGenerator
{
    /**
     * Generate token
     *
     * @return mixed random integer
     */
    public function generateToken()
    {
        return 123456;
    }
}