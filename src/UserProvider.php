<?php

namespace TokenizedLogin;

use App\Models\User;

class UserProvider
{
    /**
     * Find user by email
     *
     * @param string $email
     * @return \Illuminate\Support\Collection|null
     */
    public function getUserbyEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * Check user is block
     *
     * @param integer $userId
     * @return boolean
     */
    public function isBanned(int $userId)
    {
        $user = User::find($userId) ?: new User;

        return $user->is_ban == 1 ? true : false;
    }
}