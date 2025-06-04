<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
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

        // Injeção de dependência: passa Request para o método
        return call_user_func([$controllerInstance, $methodAction], $request);
    }
}
