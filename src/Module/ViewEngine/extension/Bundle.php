<?php

namespace MerapiPanel\Module\ViewEngine\Extension;

use MerapiPanel\Box;
use MerapiPanel\Module\ViewEngine\Twig;
use \Twig\TwigFunction;
use \Twig\TwigFilter;

class Bundle extends \Twig\Extension\AbstractExtension
{

    protected $box;
    public function __construct(Box $box)
    {
        $this->box = $box;
    }

    function getFunctions()
    {

        return [
            new TwigFunction('setJsModule', [$this, 'setJsModule'])
        ];
    }


    public function getFilters()
    {
        return [
            new TwigFilter('admin_url', [$this, 'admin_url']),
            new TwigFilter('assets', [$this, 'assets']),
            new TwigFilter('url', [$this, 'url'])
        ];
    }


    function assets($file = null)
    {

        $root = $_SERVER['DOCUMENT_ROOT'];
        $base = str_replace($root, "", str_replace("\\", '/', realpath(__DIR__ . "/../..")));
        preg_match_all('/\@\w+/ims', $file, $matches);

        if (isset($matches[0][0])) {
            foreach ($matches[0] as $match) {
                $file = str_replace($match, rtrim($base, '/') . "/" . substr($match, 1), $file);
            }
        }

        return $file;
    }


    function url($path)
    {
        $parse = parse_url($path);
        if (!isset($parse['scheme'])) {
            $parse['scheme'] = $_SERVER['REQUEST_SCHEME'];
        }
        if (!isset($parse['host'])) {
            $parse['host'] = $_SERVER['HTTP_HOST'];
        }

        return $parse['scheme'] . "://" . $parse['host'] . "/" . ltrim($parse['path'], "/");
    }

    function admin_url($path)
    {

        $AppConfig = $this->box->getConfig();
        return rtrim($AppConfig['admin'], "/") . "/" . ltrim($path, "/");
    }


    function setJsModule($module)
    {

        $_module = isset($_COOKIE["_module"]) ? json_decode($_COOKIE["_module"], true) : [];
        $module = array_unique(array_merge($_module, [$module]));
        setcookie("_module", json_encode($module), time() + (86400 * 30), "/");
    }
}
