<?php

namespace MerapiPanel\Module\ViewEngine;

use MerapiPanel\Box;
use MerapiPanel\Module\ViewEngine\extension\Encore;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\WebLinkExtension;
use Symfony\WebpackEncoreBundle\DependencyInjection\WebpackEncoreExtension;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;

class Service
{

    protected $box;
    protected $twig;
    protected $loader;
    protected $localeEngine;
    protected $globals = [];
    protected $initialize = false;
    protected $variables = [];

    function setBox(?Box $box)
    {

        $this->box          = $box;
        $this->localeEngine = $box->module_locale()->getRealInstance();
        $this->loader       = new Loader(realpath(__DIR__ . "/../../base/views"));
        $this->twig         = new \Twig\Environment($this->loader, ["cache" => false]);


        $this->twig = new \Twig\Environment($this->loader, [
            'cache' => false,
        ]);
        $this->twig->addExtension(new TranslationExtension($this->localeEngine)); // Pass your translator instance


        // Load our own Twig extensions
        $files = glob(__DIR__ . "/extension/*.php");
        foreach ($files as $file) {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = substr(self::class, 0, strpos(self::class, basename(self::class))) . "Extension\\" . ucfirst($file_name);

            if (class_exists($className)) {

                $this->twig->addExtension(new $className($box));
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


    public function getVariables() {
        return $this->variables;
    }


    function render($fileName, $vars = [])
    {

        $this->variables = array_merge($this->variables, $vars);

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
