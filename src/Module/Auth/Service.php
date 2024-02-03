<?php

namespace MerapiPanel\Module\Auth;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\AES;

class Service extends Module
{

//    const COOKIE_KEY = "m-auth";

    public function isAdmin()
    {

        return true;
        return isset($_COOKIE[($this->getSetting()["cookie"]["cookie_key"])]);
    }



    public function setAuthSession($username)
    {

        $data = [
            "username" => $username,
            "time" => time()
        ];

        error_log(json_encode($data));
        $encrypted = AES::encrypt(json_encode($data));
        setcookie(($this->getSetting()["cookie"]["cookie_key"]), $encrypted, time() + (86400 * 30), "/");
    }



    public function getLogedInUsername()
    {

        $raw = AES::decrypt($_COOKIE[($this->getSetting()["cookie"]["cookie_key"])]);
        if (!$raw) return false;

        $data = json_decode($raw, true);
        if (!$data || !isset($data["username"])) return false;

        return $data["username"];
    }



    public function getSetting()
    {

        return [
            "cookie" => [
                "cookie_key" =>  "m-auth",
                "cookie_lifetime" => 86400,
            ],
            "login_path" => "/login"
        ];
    }

}
