<?php
namespace MerapiPanel\Module\Setting\Views\Admin;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\AES;
use MerapiPanel\Utility\Router;
use MerapiPanel\Views\View;

class Api extends __Fragment
{

    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {

        $this->module = $module;
    }








    function save_endpoint()
    {

        if (isset($_ENV['__MP_ACCESS__']) && isset($_ENV['__MP_' . strtoupper($_ENV['__MP_ACCESS__']) . '__']['prefix'])) {
            return rtrim($_ENV['__MP_' . strtoupper($_ENV['__MP_ACCESS__']) . '__']['prefix'], "/") . '/setting/endpoint/save';
        }
        return "";
    }




    


}