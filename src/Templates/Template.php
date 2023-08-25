<?php

namespace il4mb\Mpanel\Templates;

use Twig\Loader\FilesystemLoader;

class Template 
{

    protected $twig;

    public function __construct()
    {

        $loader = new FilesystemLoader(__DIR__);
        $this->twig = new \Twig\Environment($loader, ['cache' => false]);

    }

    public function render($templateName)
    {
        return $this->twig->render($templateName);
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