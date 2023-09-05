<?php

namespace il4mb\Mpanel\Core\View;

use il4mb\Mpanel\Core\Box;
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


        
    }

    function setBox(?Box $box)
    {

        $this->localeEngine = $box->core_locale();
        $this->loader       = $box->core_view_loader(__DIR__ . "/../../view");
        $this->twig         = $box->core_view_twig($this->loader, ["cache" => false]);
        $this->twig->addExtension(new TranslationExtension($this->localeEngine)); // Pass your translator instance


        // Load our own Twig extensions
        $files = glob(__DIR__ . "/extension/*.php");
        foreach ($files as $file) {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = "il4mb\\Mpanel\\Core\\view\\Extension\\" . ucfirst($file_name);

            if (class_exists($className)) {

                $this->twig->addExtension(new $className());
            }
        }

        $segment = $box->core_segment();
        $this->twig->addGlobal("$segment", $segment);
       
        // $this->twig->addGlobal('guest', $box->core_segment_guest());
        // $this->twig->addGlobal('admin', $box->core_mod_segment_admin());
    }

    function exist($name) {

        return false;
    }


    function render() {
        return $this->twig->render($this->twig->getview(), $this->twig->getGlobals());
    }
   
    function __call($name, $arguments)
    {
        if(method_exists($this->twig, $name)) {
            return call_user_func_array([$this->twig, $name], $arguments);
        }
    }
}
