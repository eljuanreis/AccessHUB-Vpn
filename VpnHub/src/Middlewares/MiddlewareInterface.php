<?php

namespace App\Middlewares;

interface MiddlewareInterface
{
    public function execute() : bool;
}
