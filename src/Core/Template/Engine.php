<?php

namespace il4mb\Mpanel\Core\Template;


use il4mb\Mpanel\Core\Container;
use Twig\Loader\FilesystemLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;

class Engine
{

    protected $twig;

    public function __construct(?Container $container = null)
    {

        $loader = new FilesystemLoader(__DIR__ . "/../../template");
        $this->twig = new \Twig\Environment($loader, ['cache' => false]);

        $this->twig->addExtension(new TranslationExtension($container ? $container("locale", "engine") : null)); // Pass your translator instance
        $this->twig->addExtension(new FormExtension()); // Form extension doesn't need additional dependencies


        // Load our own Twig extensions
        $files = glob(__DIR__ . "/extension/*.php");
        foreach ($files as $file) 
        {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = "il4mb\\Mpanel\\Core\\Template\\Extension\\" . ucfirst($file_name);

            if (class_exists($className)) {

                $this->twig->addExtension(new $className());
            }
        }
    }

    function load($template)
    {

        return $this->twig->render($template, []);
    }

    // Add a method to add global template variables
    public function addGlobal($name, $value)
    {

        $this->twig->addGlobal($name, $value);
    }

    // Add a method to check if a template exists
    public function templateExists($templateName)
    {
        return $this->twig->getLoader()->exists($templateName);
    }
}
