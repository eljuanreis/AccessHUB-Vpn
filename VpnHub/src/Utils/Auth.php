<?php

namespace App\Utils;

class Auth
{
    public static function isAuth() : bool
    {
        $user = Session::get(Session::USER, 'user');

        if ($user && $user->getUsername()) {
            return true;
        }

        return false;
    }
}
