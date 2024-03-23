<?php

namespace MerapiPanel\Module\Auth;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\AES;

class Service extends Module
{

    public function admin() {

        return true;
    }

    public function client() {

        return true;
    }

    public function isAdmin()
    {

        return true;
        return isset($_COOKIE[($this->getSetting()["cookie"]["cookie_key"])]);
    }



    public function setSession($user_id)
    {

        $data = [
            "user_id" => $user_id,
            "time" => time()
        ];

        $encrypted = AES::encrypt(json_encode($data));
        setcookie(($this->getSetting()["cookie"]["cookie_key"]), $encrypted, time() + (86400 * 30), "/");
    }



    public function getSession()
    {

        $raw = AES::decrypt($_COOKIE[($this->getSetting()["cookie"]["cookie_key"])]);
        if (!$raw)
            return false;
        $data = json_decode($raw, true);
        if (!$data)
            return false;

        if (isset($data["user_id"])) {
            return Box::module("Users")->service()->getUserById($data["user_id"]);
        } else if (isset($data["username"])) {
            return Box::module("Users")->service()->getUserByUsername($data["username"]);
        }
    }




    public function getLogedInUsername()
    {

        $raw = AES::decrypt($_COOKIE[($this->getSetting()["cookie"]["cookie_key"])]);
        if (!$raw)
            return false;

        $data = json_decode($raw, true);
        if (!$data || !isset($data["username"]))
            return false;

        return $data["username"];
    }



    public function getSetting()
    {

        return [
            "cookie" => [
                "cookie_key" => "m-auth",
                "cookie_lifetime" => 86400,
            ],
            "login_path" => "/login"
        ];
    }

}
