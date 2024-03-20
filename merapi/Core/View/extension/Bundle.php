<?php

namespace MerapiPanel\Core\View\Extension;

use MerapiPanel\Box;
use MerapiPanel\Core\AES;
use MerapiPanel\Core\Token\parser\FormToken;
use \Twig\TwigFunction;
use \Twig\TwigFilter;

class Bundle extends \Twig\Extension\AbstractExtension
{



    protected $box;




    public function __construct(Box $box)
    {
        $this->box = $box;
    }









    public function getTokenParsers()
    {
        return [
            new FormToken(),
        ];
    }








    function getFunctions()
    {

        return [
            new TwigFunction('setJsModule', [$this, 'setJsModule']),
            new TwigFunction('merapi_token', [$this, 'merapiToken']),
        ];
    }









    public function getFilters()
    {
        return [
            new TwigFilter('admin_url', [$this, 'admin_path']),
            new TwigFilter('admin_path', [$this, 'admin_path']),
            new TwigFilter('assets', [$this, 'assets']),
            new TwigFilter('url', [$this, 'url']),
            new TwigFilter('preg_replace', [$this, 'preg_replace']),
            new TwigFilter('aes_decrypt', [$this, 'aes_decrypt']),
            new TwigFilter('aes_encrypt', [$this, 'aes_encrypt']),
        ];
    }


    function aes_decrypt($data)
    {

        return AES::decrypt($data);
    }

    function aes_encrypt($data)
    {

        return AES::encrypt($data);
    }

    function preg_replace($subject, $pattern, $replacement)
    {
        return preg_replace($pattern, $replacement, $subject);
    }









    function assets($absoluteFilePath = null)
    {


        return Box::module("FileManager")->service("Assets")->url($absoluteFilePath);
    }


    private function getModuleName($absoluteFilePath)
    {
    }








    function url($path)
    {
        $parse = parse_url($path);
        if (!isset ($parse['scheme'])) {
            $parse['scheme'] = $_SERVER['REQUEST_SCHEME'];
        }
        if (!isset ($parse['host'])) {
            $parse['host'] = $_SERVER['HTTP_HOST'];
        }

        return $parse['scheme'] . "://" . $parse['host'] . "/" . ltrim($parse['path'], "/");
    }









    function admin_path($path)
    {
        return rtrim($_ENV["__MP_ADMIN__"], "/") . "/" . ltrim($path, "/");
    }





    function setJsModule($module)
    {

        $_module = isset ($_COOKIE["_module"]) ? json_decode($_COOKIE["_module"], true) : [];
        $module = array_unique(array_merge($_module, [$module]));
        setcookie("_module", json_encode($module), time() + (86400 * 30), "/");
    }






    public function merapiToken()
    {

        return $this->box->Core_Token_Token()->generate();
    }
}
