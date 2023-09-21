<?php

namespace MerapiPanel\Module\ViewEngine;

use MerapiPanel\Box;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

class Service
{

    protected $box;
    protected $twig;
    protected $loader;
    protected $localeEngine;
    protected $globals = [];
    protected $initialize = false;

    function setBox(?Box $box)
    {

        $this->box          = $box;
        $this->localeEngine = $box->module_locale()->getRealInstance();
        $this->loader       = new Loader(realpath(__DIR__ . "/../../views"));
        $this->twig         = new \Twig\Environment($this->loader, ["cache" => false]);
        $this->twig->addExtension(new TranslationExtension($this->localeEngine)); // Pass your translator instance


        // Load our own Twig extensions
        $files = glob(__DIR__ . "/extension/*.php");
        foreach ($files as $file) {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = substr(self::class,0, strpos(self::class, basename(self::class))) . "Extension\\" . ucfirst($file_name);

            if (class_exists($className)) {

                $this->twig->addExtension(new $className());
            }
        }

        $guest = new Zone("guest");
        $admin = new Zone("admin");
        $admin->setBox($box);
        $guest->setBox($box);

        $this->twig->addGlobal("admin", $admin);
        $this->twig->addGlobal("guest", $guest);
    }


    function getLoader()
    {
        return $this->loader;
    }


    function render($fileName)
    {

        return ltrim($fileName, "/");
    }


    function addGlobal($name, $value)
    {
        $this->twig->addGlobal($name, $value);
    }



    function load($file)
    {

        return $this->twig->load($file);
    }


    /**
     * @deprecated - this is temporary function will delete soon
     * @return $this
     */
    function templateExists($fileName)
    {
        return false;
    }

    public function addExtension($extension)
    {
        $this->twig->addExtension($extension);
    }



    function getTwig()
    {
        return $this->twig;
    }
}
