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

        $SQL = "SELECT A.id, A.name, A.email, A.role, A.post_date, A.update_date FROM session_token B JOIN users A ON A.id = B.user_id WHERE B.token = :token";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}
