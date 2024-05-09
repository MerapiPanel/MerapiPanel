<?php

namespace MerapiPanel\Module\Auth;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\AES;
use PDO;

class Service extends __Fragment
{

    protected Module $module;
    function onCreate(Module $module)
    {

        $this->module = $module;
    }


    function getLogedinUser()
    {
        $config = $this->module->getConfig();
        $cookie_name = $config->get("cookie_name");

        if (!isset($_COOKIE[$cookie_name]) || !$token = AES::decrypt($_COOKIE[$cookie_name] ?? "")) {
            return null;
        }

        $SQL = "SELECT * FROM session_token WHERE token = :token";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(['token' => $token]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($session) {
            return Box::module("User")->fetch(["id", "name", "email", "role", "status", "post_date", "update_date"], ["id" => $session['user_id']]);
        }
        return null;
    }


}
