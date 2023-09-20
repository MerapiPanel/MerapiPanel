<?php

namespace MerapiQu\Module\Site;

class Service
{

    protected $box;

    function setBox($box)
    {
        $this->box = $box;
    }

    function adminLink($path = "")
    {
        $AppConfig = $this->box->getConfig();
        return rtrim($AppConfig['admin'], "/") . "/" . ltrim($path, "/");
    }
}
