<?php
namespace MerapiPanel\Views\Extension;

use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Views\Abstract\Extension;

class Bundle extends Extension
{

    public function fl_url(string $path, array $pattern = [])
    {

        $parse = parse_url($path);
        if (!isset($parse['scheme'])) {
            $parse['scheme'] = $_SERVER['REQUEST_SCHEME'];
        }
        if (!isset($parse['host'])) {
            $parse['host'] = $_SERVER['HTTP_HOST'];
        }

        $result_path = $parse['path'];
        preg_match_all('/\:([a-z0-9]+)/i', $result_path, $matches);


        if (isset($matches[1])) {
            foreach ($matches[1] as $value) {
                $result_path = preg_replace('/\:' . $value . '/', (isset($pattern[$value]) && !empty($pattern[$value]) ? $pattern[$value] : ""), $result_path);
            }
        }
        return $parse['scheme'] . "://" . $parse['host'] . "/" . ltrim($result_path, "/");
    }


    public function fn_time()
    {
        return time();
    }





    function fl_access_path(string $path)
    {

        if (!isset($_ENV["__MP_ACCESS_NAME__"])) {
            $request = Request::getInstance();
            foreach ($_ENV["__MP_ACCESS__"] as $key => $value) {
                if (strpos($request->getPath(), $value["prefix"]) === 0) {
                    $_ENV["__MP_ACCESS_NAME__"] = $key;
                    break;
                }
            }
        }

        if (!isset($_ENV["__MP_ACCESS__"], $_ENV["__MP_ACCESS_NAME__"])) {
            return $path;
        }
        $access = $_ENV["__MP_ACCESS__"][$_ENV["__MP_ACCESS_NAME__"]];
        $prefix = $access["prefix"];

        return rtrim($prefix, "/") . "/" . ltrim($path, "/");
    }



    function fl_preg_replace($pattern, $replacement, $subject)
    {
        return preg_replace($pattern, $replacement, $subject);
    }
}