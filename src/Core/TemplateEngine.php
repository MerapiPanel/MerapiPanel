<?php

namespace il4mb\Mpanel\Core;

use il4mb\Mpanel\Application;
use Twig\Loader\FilesystemLoader;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\SecurityExtension;

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
        // $this->twig->addExtension(new SecurityExtension($security)); // Pass your security instance

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
