<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

trait RememberMeTrait
{
    protected function setRememberMeToken($user)
    {
        $token = Str::random(60);
        $user->remember_token = Hash::make($token);
        $user->save();

        Cookie::queue(
            'remember_token',
            $token,
            43200, 
            null,
            null,
            true,  
            true   
        );
    }

    protected function validateRememberMeToken($user, $token)
    {
        return $user && Hash::check($token, $user->remember_token);
    }

    protected function forgetRememberMeToken($user)
    {
        $user->remember_token = null;
        $user->save();
        Cookie::queue(Cookie::forget('remember_token'));
    }
}