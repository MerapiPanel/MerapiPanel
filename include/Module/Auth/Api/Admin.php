<?php

namespace MerapiPanel\Module\Auth\Api;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    function setting_session_name()
    {

        $session = $this->service()->getSetting("session_name");
        if (!$session) {
            return false;
        }
        return $session;
    }



    function username()
    {
        return $this->service()->getLogedInUsername();
    }


    function settings()
    {

        $container = $this->service()->getSetting()->getContainer();

        $map = [];

        foreach ($container as $value) {
            $map[$value['name']] = $value['value'];
        }

        return $map;
    }
}
