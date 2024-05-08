<?php

namespace MerapiPanel\Module\Panel\Views;

use MerapiPanel\Box\Module\__Fragment;

class Api extends __Fragment
{
    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    public function Base()
    {
        return $this->module->getBase();
    }

    public function ListMenu()
    {
        return $this->module->getMenu();
    }


    function isFile($path)
    {
        return is_file($path);
    }



    function getAbsolutePath($path)
    {
        return str_replace(str_replace('\\', '/', $_ENV['__MP_CWD__']), "", $path);
    }


    function getScripts() {
        return $this->module->Scripts->getScripts();
    }






    function allowed(){

        return $this->module->Service->allowed;
    }
}
