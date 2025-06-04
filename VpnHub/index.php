<?php 

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Kernel;
use App\Core\Request;
use App\Core\Router;

/**
 * Carregando configurações do ambiente.
 */
$kernel = new Kernel();
$kernel->webApplication(
   basePath: __DIR__ ,
   entityPath: __DIR__ . '/src/Entity'
);

/**
 * Definição de rotas
 */
$router = $kernel->get('router');
$router->get('/login', 'AuthController@show');
$router->post('/login', 'AuthController@login');

$router->get('/revoke', 'RevokeController@dispatch');

$router->dispatch(new Request());

