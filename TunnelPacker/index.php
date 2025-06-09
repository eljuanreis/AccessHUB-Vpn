<?php 


require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Request;
use App\Core\Router;

/**
 * Carregando configurações do ambiente.
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/**
 * Definição de rotas
 */
$router = new Router();
$router->get('/download', 'DownloadController@download');
$router->post('/make', 'DownloadController@make');
$router->post('/revoke', 'RevokeController@dispatch');

$router->dispatch(new Request());

