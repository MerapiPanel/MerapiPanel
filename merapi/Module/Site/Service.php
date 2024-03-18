<?php

namespace MerapiPanel\Module\Site;

class Service
{

    protected $box;

    function setBox($box)
    {
        $this->box = $box;
    }

    function adminLink($path = "")
    {
        return rtrim($_ENV["__MP_ADMIN__"], "/") . "/" . ltrim($path, "/");
    }


    function createLink($path = "")
    {

        $parse = parse_url($path);

        if (!isset($parse['host'])) {
            $parse['host'] = $_SERVER['HTTP_HOST'];
        }
        if (!isset($parse['scheme'])) {
            $parse['scheme'] = $_SERVER['REQUEST_SCHEME'];
        }

        return $parse['scheme'] . "://" . $parse['host'] . $parse['path'];
    }
}
