<?php

namespace App\Core;

class Request
{
    protected $method;
    protected $uri;
    protected $get;
    protected $post;
    protected $json;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->get = $_GET;
        $this->post = $_POST;

        // Detectar e decodificar JSON
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $this->json = json_decode(file_get_contents('php://input'), true);
        } else {
            $this->json = [];
        }
    }

    public function method()
    {
        return $this->method;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function input($key, $default = null)
    {
        return $this->json[$key] ?? $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all()
    {
        return array_merge($this->get, $this->post, $this->json);
    }
}
