<?php

namespace App\Middlewares;

class AuthMiddleware implements MiddlewareInterface
{
    public function execute() : bool
    {
        return true;
    }
}
