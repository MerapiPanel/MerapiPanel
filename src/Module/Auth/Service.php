<?php

namespace MerapiPanel\Module\Auth;

use MerapiPanel\Core\Abstract\Module;

class Service extends Module
{

    const COOKIE_KEY = "auth";

    public function isAdmin()
    {

        return isset($_COOKIE[self::COOKIE_KEY]) && $_COOKIE[self::COOKIE_KEY] == "admin";
    }




    public function pathFinder($path)
    {
    }




    public function getSetting($name = null)
    {
        $settings = $this->__getSettings();

        if (!$name) {
            return $settings;
        }

        if (!isset($settings[$name])) {
            $settings[$name] = 1;
        }

        return $settings[$name];
    }
}
