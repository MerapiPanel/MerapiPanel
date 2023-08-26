<?php

namespace il4mb\Mpanel\Twig;

use il4mb\Mpanel\Application;
use Twig\Loader\FilesystemLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;

class TemplateEngine
{

    protected $twig;

    public function __construct(Application $app)
    {

        $loader = new FilesystemLoader($app->getDirectory() . "/Templates");
        $this->twig = new \Twig\Environment($loader, ['cache' => false]);

        // Load Symfony's Twig extensions
        // $this->twig->addExtension(new RoutingExtension($router)); // Pass your router instance
        $this->twig->addExtension(new TranslationExtension($app->getLocalEngine())); // Pass your translator instance
        $this->twig->addExtension(new FormExtension()); // Form extension doesn't need additional dependencies

        // Load our own Twig extensions
        $files = glob(__DIR__ . "/extension/*.php");
        foreach ($files as $file) 
        {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = "il4mb\Mpanel\Twig\Extension\\". ucfirst($file_name);

            if(class_exists($className))
            {

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
