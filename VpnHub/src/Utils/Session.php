<?php

namespace App\Utils;

class Session 
{
    const FLASH = '_flash';

    protected static function start()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_start();
    }

    public static function put($identifier, $key, $value = null) 
    {
        static::start();

        $_SESSION[$identifier][$key] = $value;
    }

    public static function destroy($identifier, $key) 
    {
        static::start();

        unset($_SESSION[$identifier][$key]);
    }
}