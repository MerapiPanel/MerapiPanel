<?php

namespace il4mb\Mpanel\Core\Template;

use il4mb\Mpanel\Core\AppBox;
use il4mb\Mpanel\Core\Locale\Engine as LocaleEngine;
use il4mb\Mpanel\Core\Mod\Segment\Admin;
use il4mb\Mpanel\Core\Mod\Segment\Guest;
use Twig\Loader\FilesystemLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

class Entity
{

    protected $twig;
    protected $loader;
    protected $localeEngine;

    public function __construct()
    {


        $this->localeEngine = new LocaleEngine();
        $this->loader       = new FilesystemLoader(__DIR__ . "/../../template");
        $this->twig         = new Twig($this->loader, ['cache' => false]);
        $this->twig->addExtension(new TranslationExtension($this->localeEngine)); // Pass your translator instance


        // Load our own Twig extensions
        $files = glob(__DIR__ . "/extension/*.php");
        foreach ($files as $file) {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = "il4mb\\Mpanel\\Core\\Template\\Extension\\" . ucfirst($file_name);

            if (class_exists($className)) {

                $this->twig->addExtension(new $className());
            }
        }
    }

    function setBox(?AppBox $box)
    {

        $this->twig->addGlobal('guest', $box->core_mod_segment_guest());
        $this->twig->addGlobal('admin', $box->core_mod_segment_admin());
    }

    function load($template)
    {

        if(!$this->twig) {
            $this->twig = new Twig($this->loader, ['cache' => false]);
        }

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
