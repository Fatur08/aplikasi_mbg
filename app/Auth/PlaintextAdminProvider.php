<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class PlaintextMakerProvider extends EloquentUserProvider
{
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password_maker'];
        return $plain === $user->getAuthPassword(); // cocokkan langsung tanpa hash
    }
}