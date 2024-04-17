<?php

namespace MerapiPanel\Module\Auth;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Database\DB;
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
        $setting = $this->module->getSetting();
        $cookie_name = $setting->cookie_name;

        if (!isset($_COOKIE[$cookie_name])) {
            return null;
        }

        $token = $_COOKIE[$cookie_name];

        $SQL = "SELECT A.* FROM session_token B JOIN users A ON A.id = B.user_id WHERE B.token = :token";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}
