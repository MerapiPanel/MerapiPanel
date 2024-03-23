<?php
namespace MerapiPanel\Core;

class Helper
{
    function url_path($path)
    {
        if (!isset ($_ENV["__MP_ACCESS__"], $_ENV["__MP_ACCESS_NAME__"])) {
            return $path;
        }
        $access = $_ENV["__MP_ACCESS__"][$_ENV["__MP_ACCESS_NAME__"]];
        $prefix = $access["prefix"];

        return rtrim($prefix, "/") . "/" . ltrim($path, "/");
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
}