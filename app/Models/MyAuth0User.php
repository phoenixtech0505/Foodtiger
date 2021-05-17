<?php

namespace App\Models;

use App\User;
use Auth0\Login\Auth0User;

class MyAuth0User extends Auth0User
{
    public function eloquentUser()
    {
        if ($this->userInfo) {
            return User::findOrFail($this->userInfo['id']);
        }

        return null;
    }
}
