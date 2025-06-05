<?php

namespace App\Core;

use App\Middlewares\MiddlewareInterface;

class Router
{
    const MIDDLEWARE_LABEL = '_guards';

    protected $routes = [];

    public function get($uri, $action, $middleware = null)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action, $middleware = null)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(Request $request)
    {
        $method = $request->method();
        $uri = $request->uri();

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "Rota não encontrada!";
            return;
        }

        if (isset($this->routes[self::MIDDLEWARE_LABEL][$uri])) {
            $this->dispatchMiddlewares($this->routes[self::MIDDLEWARE_LABEL][$uri]);
        }

        $action = $this->routes[$method][$uri];
        [$controller, $methodAction] = explode('@', $action);
        $controller = "App\\Controllers\\$controller";

        if (!class_exists($controller)) {
            http_response_code(500);
            echo "Controller '$controller' não encontrado!";
            return;
        }

        if (!method_exists($controller, $methodAction)) {
            http_response_code(500);
            echo "Método '$methodAction' não encontrado no controller '$controller'!";
            return;
        }

        $controllerInstance = new $controller();

        $response = call_user_func([$controllerInstance, $methodAction], $request);
        $response->send();
    }

    /**
     * Verifica se a rota existe.
     */
    public function routeExists($method, $uri)
    {
        return isset($this->routes[$method][$uri]);
    }

    public function route($method, $uri)
    {
        if ($this->routeExists($method, $uri)) {
            header('Location: ' . $uri);
            exit;
        }

        throw new \Exception(sprintf('[%s] %s não definida', $method, $uri));
    }

    public function addGlobalMiddleware($uri, $middleware)
    {
        $this->routes[self::MIDDLEWARE_LABEL][$uri][] = $middleware;
    }

    protected function dispatchMiddlewares(array $middlewares = [])
    {
        foreach ($middlewares as $middleware) {
            $middleware = new $middleware();
            if ($middleware instanceof MiddlewareInterface && !$middleware->execute()) {
                exit;
            }
        }
    }
}
