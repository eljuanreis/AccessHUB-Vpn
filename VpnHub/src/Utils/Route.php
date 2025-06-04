<?php

namespace App\Utils;

use App\Core\Kernel;

class Route
{
    public static function redirect($method, $uri)
    {
        $kernel = Kernel::getApplication();
        $router = $kernel->get('router');

        return $router->route($method, $uri);
    }
}
