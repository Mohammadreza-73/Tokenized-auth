<?php

namespace TokenizedLogin\TokenGenerators;

class TokenGenerator
{
    /**
     * Generate token
     *
     * @return mixed random integer
     */
    public function generateToken()
    {
        return random_int(100000, 1000000 - 1);
    }
}