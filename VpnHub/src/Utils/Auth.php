<?php

namespace App\Utils;

use App\Entity\User;

class Auth
{
    public static function isAuth(): bool
    {
        $user = Session::get(Session::USER, 'user');

        if ($user && $user->getUsername()) {
            return true;
        }

        return false;
    }

    public static function getUser(): ?User
    {
        if (static::isAuth()) {
            return Session::get(Session::USER, 'user');
        }

        return null;
    }
}
