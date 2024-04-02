<?php
namespace MerapiPanel\Core;

use MerapiPanel\Core\Views\View;

class Helper
{
    function url_path($path, $pattern = [])
    {
        if (!isset ($_ENV["__MP_ACCESS__"], $_ENV["__MP_ACCESS_NAME__"])) {
            return $path;
        }
        $access = $_ENV["__MP_ACCESS__"][$_ENV["__MP_ACCESS_NAME__"]];
        $prefix = $access["prefix"];

        $final_path = rtrim($prefix, "/") . "/" . ltrim($path, "/");
        foreach ($pattern as $key => $value) {
            if ($value && !empty ($value) && is_string($value)) {
                $final_path = preg_replace("/{" . $key . "\}/mi", "/$value", $final_path);
            }
        }

        return implode("/", array_filter(explode("/", $final_path)));
    }

    function url($path, $pattern = [])
    {
        $parse = parse_url($path);
        if (!isset ($parse['scheme'])) {
            $parse['scheme'] = $_SERVER['REQUEST_SCHEME'];
        }
        if (!isset ($parse['host'])) {
            $parse['host'] = $_SERVER['HTTP_HOST'];
        }

        $result_path = $parse['path'];
        preg_match_all('/\:([a-z0-9]+)/i', $result_path, $matches);

        // error_log($result_path . " " . json_encode($matches));
        if (isset ($matches[1])) {
            foreach ($matches[1] as $value) {
                $result_path = preg_replace('/\:' . $value . '/', (isset ($pattern[$value]) && !empty ($pattern[$value]) ? $pattern[$value] : ""), $result_path);
            }
        }
        return $parse['scheme'] . "://" . $parse['host'] . "/" . ltrim($result_path, "/");
    }
}