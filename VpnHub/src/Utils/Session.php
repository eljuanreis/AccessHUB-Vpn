<?php

namespace App\Utils;

class Session
{
    const USER = '_user';
    const FLASH = '_flash';

    public static function start()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_start();
    }

    public static function put($identifier, $key, $value = null): void
    {
        static::start();

        $_SESSION[$identifier][$key] = $value;
    }

    public static function get($identifier, $key, $default = null): mixed
    {
        static::start();

        return $_SESSION[$identifier][$key] ?? $default;
    }

    public static function destroy($identifier, $key): void
    {
        static::start();

        unset($_SESSION[$identifier][$key]);
    }
}
