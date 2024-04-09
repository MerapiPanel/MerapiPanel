<?php
namespace MerapiPanel\Module\Setting\Views;

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



    function token(string $form)
    {

        $inputNames = [];
        preg_match_all("/<[input|textarea].*?name=\"(.*?)\".*?>/i", $form, $matches);
        if (isset($matches[1])) {
            $inputNames = $matches[1];
        }

        $route = Router::getInstance()->getRoute();
        if (!$route) {
            return "";
        }
        $contoller = $route->getController();
        if (gettype($contoller) !== "string") {
            return "";
        }

        preg_match("/Module\\\(\w+)\\\/i", $contoller, $matches);
        if (isset($matches[1])) {
            return AES::encrypt(serialize(["module" => $matches[1], "input" => $inputNames]));
        }
    }





    function save_endpoint()
    {

        if (isset($_ENV['__MP_ACCESS__']) && isset($_ENV['__MP_' . strtoupper($_ENV['__MP_ACCESS__']) . '__']['prefix'])) {
            return rtrim($_ENV['__MP_' . strtoupper($_ENV['__MP_ACCESS__']) . '__']['prefix'], "/") . '/setting/endpoint/save';
        }
        return "";
    }


}