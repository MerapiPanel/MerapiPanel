<?php

namespace MerapiPanel\Module\Setting;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Module\Setting\ViewParser;
use MerapiPanel\Views\View;

class Service extends __Fragment
{

    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module) {

        View::getInstance()->getTwig()->addTokenParser(new ViewParser());
        $this->module = $module;
    }


    public function getTimeZones()
    {
        $raw = file_get_contents(__DIR__ . "/assets/timezone.json");
        return json_decode($raw, true);
    }
}
