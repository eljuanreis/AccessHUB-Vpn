<?php 

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Kernel;
use App\Core\Request;
use App\Middlewares\AdminMiddleware;
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

// Rotas de recuperação de senha
$router->get('/password-reset', 'AuthController@showPasswordReset'); // Formulário para solicitar reset
$router->post('/password-reset', 'AuthController@sendResetPasswordLink'); // Envia o e-mail com o link
$router->get('/password-reset/confirm', 'AuthController@showResetPasswordForm'); // Formulário para nova senha
$router->post('/password-reset/confirm', 'AuthController@resetPassword'); // Salva nova senha

/**
 * Rotas com autenticação.
 */
$router->addGlobalMiddleware('/administrar-usuarios', AdminMiddleware::class);
$router->get('/administrar-usuarios', 'AdminUserController@show');
$router->post('/administrar-usuarios/criar', 'AdminUserController@store');
$router->post('/administrar-usuarios/eleger-admin', 'AdminUserController@promote');
$router->post('/administrar-usuarios/revogar-admin', 'AdminUserController@unpromote');
$router->post('/administrar-usuarios/ativar-acesso', 'AdminUserController@active');
$router->post('/administrar-usuarios/revogar-acesso', 'AdminUserController@inactive');
$router->post('/administrar-usuarios/remover-usuarios', 'AdminUserController@remove');

$router->addGlobalMiddleware('/vpn', AuthMiddleware::class);
$router->get('/vpn', 'ConfigurationController@show');
$router->post('/vpn', 'ConfigurationController@generate');
$router->post('/vpn/delete', 'ConfigurationController@delete');
$router->get('/vpn/download', 'ConfigurationController@download');

$router->dispatch(new Request());

