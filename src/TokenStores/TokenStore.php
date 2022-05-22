<?php

namespace TokenizedLogin\TokenStores;

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
        $ttl = config('tokenized_auth.token_ttl');
        cache()->set($token . '_2factor_auth', $userId, now()->addSeconds($ttl));
    }

    public function getUidByToken($token)
    {
        $uid = cache()->pull($token . '_2factor_auth');
        
        return nullable($uid);
    }
}