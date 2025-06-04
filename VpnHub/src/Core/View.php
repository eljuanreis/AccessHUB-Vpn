<?php

namespace App\Core;

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
        
        return new Response(static::$twig->render($path, $data));
    }
}
