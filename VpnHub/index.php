<?php 

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Kernel;
use App\Core\Request;
use App\Middlewares\AuthMiddleware;

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

/**
 * Rotas sem autenticação.
 */
$router->get('/', 'AuthController@show');

$router->get('/login', 'AuthController@show');
$router->post('/login', 'AuthController@login');
$router->get('/password-reset', 'AuthController@showPasswordReset');
$router->post('/password-reset', 'AuthController@showPasswordReset');

/**
 * Rotas com autenticação.
 */
$router->addGlobalMiddleware('/painel', AuthMiddleware::class);
$router->get('/painel', 'PanelController@show');

$router->addGlobalMiddleware('/administrar-usuarios', AuthMiddleware::class);
$router->get('/administrar-usuarios', 'AdminUserController@show');
$router->post('/administrar-usuarios/criar', 'AdminUserController@store');
$router->post('/administrar-usuarios/eleger-admin', 'AdminUserController@promote');
$router->post('/administrar-usuarios/revogar-admin', 'AdminUserController@unpromote');
$router->post('/administrar-usuarios/ativar-acesso', 'AdminUserController@active');
$router->post('/administrar-usuarios/revogar-acesso', 'AdminUserController@inactive');
$router->post('/administrar-usuarios/remover-usuarios', 'AdminUserController@remove');


$router->dispatch(new Request());

