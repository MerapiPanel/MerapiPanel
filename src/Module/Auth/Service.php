<?php

namespace MerapiPanel\Module\Auth;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\AES;

class Service extends Module
{

    const COOKIE_KEY = "m-auth";

    public function isAdmin()
    {

        return isset($_COOKIE[self::COOKIE_KEY]);
    }



    public function setAuthSession($username)
    {

        $data = [
            "username" => $username,
            "time" => time()
        ];

        error_log(json_encode($data));
        $encrypted = AES::encrypt(json_encode($data));
        setcookie(self::COOKIE_KEY, $encrypted, time() + (86400 * 30), "/");
    }



    public function getLogedInUsername()
    {

        $raw = AES::decrypt($_COOKIE[self::COOKIE_KEY]);
        if (!$raw) return false;

        $data = json_decode($raw, true);
        if (!$data || !isset($data["username"])) return false;
        
        return $data["username"];
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
