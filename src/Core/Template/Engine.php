<?php

namespace il4mb\Mpanel\Core\Template;

use il4mb\Mpanel\Core\AppBox;
use il4mb\Mpanel\Core\Container;
use il4mb\Mpanel\Core\Locale\Engine as LocaleEngine;
use Twig\Loader\FilesystemLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

class Engine
{

    protected $twig;
    protected $loader;
    protected $localeEngine;

    public function __construct()
    {

        $this->localeEngine = new LocaleEngine();
        $this->loader = new FilesystemLoader(__DIR__ . "/../../template");
        $this->twig = new \Twig\Environment($this->loader, ['cache' => false]);
        $this->twig->addExtension(new TranslationExtension($this->localeEngine)); // Pass your translator instance


        // Load our own Twig extensions
        $files = glob(__DIR__ . "/extension/*.php");
        foreach ($files as $file) 
        {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = "il4mb\\Mpanel\\Core\\Template\\Extension\\" . ucfirst($file_name);

            if (class_exists($className)) 
            {

                $this->twig->addExtension(new $className());
            }
        }
    }

    function setContainer(?AppBox $box) 
    {

        $box->register($this->localeEngine);

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
