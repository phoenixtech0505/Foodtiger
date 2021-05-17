<?php

namespace App\Repositories;

use App\Models\MyAuth0User;
use App\User;
use Auth0\Login\Auth0JWTUser;
use Auth0\Login\Auth0User;
use Auth0\Login\Repository\Auth0UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;

class CustomUserRepository extends Auth0UserRepository
{
    /**
     * Get an existing user or create a new one.
     *
     * @param array $profile - Auth0 profile
     *
     * @return User
     */
    protected function upsertUser($profile)
    {
        return User::firstOrCreate(
            ['email' => $profile['email']],
            [
                'name' => $profile['name'] ?? '',
            ]
        );
    }

    /**
     * Authenticate a user with a decoded ID Token.
     *
     * @param object $jwt
     *
     * @return Auth0JWTUser
     */
    public function getUserByDecodedJWT(array $decodedJwt): Authenticatable
    {
        $user = $this->upsertUser($decodedJwt);

        return new Auth0JWTUser($user->getAttributes());
    }

    /**
     * Get a User from the database using Auth0 profile information.
     *
     * @param array $userinfo
     *
     * @return MyAuth0User
     */
    public function getUserByUserInfo(array $userinfo): Authenticatable
    {
        $user = $this->upsertUser($userinfo['profile']);
        $userAtributes = $user->getAttributes();
        $userAtributes['user_id'] = $userAtributes['id'];

        return new MyAuth0User($userAtributes, $userinfo['accessToken']);
    }
}
