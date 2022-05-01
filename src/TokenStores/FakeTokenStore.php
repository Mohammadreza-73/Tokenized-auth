<?php

namespace TokenizedLogin\TokenStores;

class FakeTokenStore
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
        //
    }
}