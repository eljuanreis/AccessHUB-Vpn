<?php

namespace App\Core;

use App\Utils\Session;
use Twig\Environment;

class View
{
    protected string $viewsPath;
    protected string $viewsCache;
    
    public static Environment $twig;

    public function __construct(protected string $basePath)
    {
        $this->viewsPath = $basePath.'/src/Resources/Views';
        $this->viewsCache = $basePath.'/src/Resources/Views/caching';

    }
    /**
     * Inicializado no Kernel.
     */
    public function load()
    {
        $loader = new \Twig\Loader\FilesystemLoader($this->viewsPath);
        static::$twig = new \Twig\Environment($loader, [
            'cache' => $this->viewsCache,
            'auto_reload' => true,  // Opcional, mas útil em desenvolvimento.
        ]);
    }

    public static function make(string $path, array $data = [])
    {
        $path = sprintf('%s.html.twig', $path);

        $flashMessages = static::getFlashMessages();

        $data = array_merge($data, $flashMessages);

        $page = static::$twig->render($path, $data);
        
        return new Response($page);
    }

    protected static function getFlashMessages()
    {
        session_start();

        $data = [];
        
        if (isset($_SESSION[Session::FLASH])) {
            foreach ($_SESSION[Session::FLASH] as $key => $flash) {
                $data[$key] = $flash;
                Session::destroy(Session::FLASH, $key);
            }
        }

        return $data;
    }
}
