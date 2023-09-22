<?php

namespace MerapiPanel\Module\Auth\Api;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    function setting_session()
    {

        $session = $this->service()->getSetting("session");
        if (!$session) {
            return false;
        }
        return $session;
    }

    
    function settings()
    {
        $container = $this->service()->getSetting()->getContainer();

        $container[1] = [
            'name' => 'session1',
            'value' => 1
        ];

        $map = [];
        
        foreach ($container as $value) {
            $map[$value['name']] = $value['value'];
        }

        return $map;
    }
}
