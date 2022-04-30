<?php

namespace TwoFactorAuth\TokenStores;

class TokenStore
{
    /**
     * Store token in cache
     *
     * @param  string $token
     * @param  int    $userId
     * @return void
     */
    public function saveToken(string $token, int $userId): void
    {
        $ttl = config('two_factor_auth.token_ttl');
        cache()->set($token . '_2factor_auth', $userId, $ttl);
    }

    public function getUidByToken($token)
    {
        $uid = cache()->pull($token . '_2factor_auth');
        
        return nullable($uid);
    }
}