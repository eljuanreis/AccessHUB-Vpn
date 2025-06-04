<?php

namespace App\Utils;

class Env
{
    public static function get(string $name, $default = null)
    {
        if (!isset($_ENV[$name]) || empty($_ENV[$name])) {
            return $default;
        }

        return $_ENV[$name];
    }
}
