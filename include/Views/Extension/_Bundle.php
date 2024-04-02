<?php

namespace MerapiPanel\Views\Extension;

use MerapiPanel\Box;
use MerapiPanel\Core\AES;
use MerapiPanel\Core\Helper;
use MerapiPanel\Core\Token\parser\FormToken;
use Twig\Extension\AbstractExtension;
use \Twig\TwigFunction;
use \Twig\TwigFilter;

class _Bundle extends AbstractExtension
{

    // public function __construct()
    // {
    //     //$this->box = $box;
    // }

    // public function getTokenParsers()
    // {
    //     return [
    //         new FormToken(),
    //     ];
    // }


    // function getFunctions()
    // {

    //     return [
    //         new TwigFunction('time', fn() => (function_exists("time") ? time() : date("YmdHis"))),
    //         new TwigFunction('setJsModule', [$this, 'setJsModule']),
    //         new TwigFunction('merapi_token', [$this, 'merapiToken']),
    //     ];}

    // public function getFilters()
    // {

    //     $filters = [];

    //     $thisMethod = array_diff(get_class_methods($this), get_class_methods(AbstractExtension::class));
    //     foreach ($thisMethod as $function) {
    //         if (strpos($function, "__") === 0)
    //             continue;
    //         if (strpos($function, "filter_") === 0) {
    //             $filters[] = new TwigFilter(substr($function, 7), [$this, $function]);
    //         }
    //     }


    //     $helper = new Helper();
    //     $helper_methods = get_class_methods($helper);


    //     foreach ($helper_methods as $function) {
    //         if (strpos($function, "__") === false) {
    //             $filters[] = new TwigFilter($function, [$helper, $function]);
    //         }
    //     }

    //     return $filters;
    // }


    // function filter_aes_decrypt($data)
    // {

    //     return AES::decrypt($data);
    // }

    // function filter_aes_encrypt($data)
    // {

    //     return AES::encrypt($data);
    // }

    // function filter_preg_replace($subject, $pattern, $replacement)
    // {
    //     return preg_replace($pattern, $replacement, $subject);
    // }









    // function filter_assets($absoluteFilePath = null, $encrypt = true)
    // {

        
        
    //     //return Box::module("FileManager")->Assets->url($absoluteFilePath, $encrypt);
    // }


    // private function getModuleName($absoluteFilePath)
    // {
    // }








    // function filter_url($path)
    // {
    //     $parse = parse_url($path);
    //     if (!isset ($parse['scheme'])) {
    //         $parse['scheme'] = $_SERVER['REQUEST_SCHEME'];
    //     }
    //     if (!isset ($parse['host'])) {
    //         $parse['host'] = $_SERVER['HTTP_HOST'];
    //     }

    //     return $parse['scheme'] . "://" . $parse['host'] . "/" . ltrim($parse['path'], "/");
    // }









    // function filter_admin_path($path)
    // {
    //     return rtrim($_ENV["__MP_ADMIN__"], "/") . "/" . ltrim($path, "/");
    // }


    // function filter_access_path($path)
    // {


    //     if (!isset ($_ENV["__MP_ACCESS__"], $_ENV["__MP_ACCESS_NAME__"])) {
    //         return $path;
    //     }
    //     $access = $_ENV["__MP_ACCESS__"][$_ENV["__MP_ACCESS_NAME__"]];
    //     $prefix = $access["prefix"];

    //     return rtrim($prefix, "/") . "/" . ltrim($path, "/");
    // }



    // function setJsModule($module)
    // {

    //     $_module = isset ($_COOKIE["_module"]) ? json_decode($_COOKIE["_module"], true) : [];
    //     $module = array_unique(array_merge($_module, [$module]));
    //     setcookie("_module", json_encode($module), time() + (86400 * 30), "/");
    // }






    // public function merapiToken()
    // {

    //     // return $this->box->Core_Token_Token()->generate();
    // }
}
