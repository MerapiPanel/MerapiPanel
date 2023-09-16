<?php

namespace Mp\View;

use Mp\Box;
use Mp\view\Twig;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Throwable;

class Entity
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
        $this->localeEngine = $box->core_locale();
        $this->loader       = $box->view_loader(realpath(__DIR__ . "/../../templates"));
        $this->twig         = new \Twig\Environment($this->loader, ["cache" => false]);
        $this->twig->addExtension(new TranslationExtension($this->localeEngine)); // Pass your translator instance


        // Load our own Twig extensions
        $files = glob(__DIR__ . "/extension/*.php");
        foreach ($files as $file) {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = "Mp\\view\\Extension\\" . ucfirst($file_name);

            if (class_exists($className)) {

                $this->twig->addExtension(new $className());
            }
        }

        $segment = $box->core_segment();
        $this->twig->addGlobal("$segment", $segment);
    }


    function getLoader()
    {
        return $this->loader;
    }


    function render($fileName)
    {

        return "/html_" . $this->box->segment() . "/" . ltrim($fileName, "/");
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



    function getTwig()
    {
        return $this->twig;
    }
}
