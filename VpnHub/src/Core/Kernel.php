<?php

namespace App\Core;

use Dotenv\Dotenv;

class Kernel
{
    protected static Kernel|null $application = null;
    protected array $app = [];

    public function webApplication($basePath, $entityPath)
    {
        $this->routes();
        $this->env($basePath);
        $this->view($basePath);
        $this->orm($entityPath);

        if (!static::$application) {
            static::$application = $this;
        }
    }

    public function ormConsoleApplication($basePath, $entityPath)
    {
        $this->env($basePath);
        $this->orm($entityPath);

        if (!static::$application) {
            static::$application = $this;
        }
    }

    protected function routes()
    {
        $router = new Router();
        $this->app['router'] = $router;
    }

    protected function env($basePath)
    {
        $dotenv = Dotenv::createImmutable($basePath);
        $dotenv->load();
        $this->app['env'] = $dotenv;
    }

    protected function orm($entityPath)
    {
        $orm = new Orm();
        $orm->load($entityPath);
        $this->app['orm'] = $orm;
    }

    protected function view($basePath)
    {
        $view = new View($basePath);
        $view->load();
        $this->app['view'] = $view;
    }

    public function get(string $name)
    {
        return $this->app[$name];
    }

    public static function getApplication()
    {
        return static::$application;
    }
}
