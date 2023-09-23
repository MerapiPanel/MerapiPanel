<?php

namespace MerapiPanel\Module\Settings\Api;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module {

    function AllTimeZone() {
        return $this->service()->getTimeZones();
    }

    function settings() 
    {
        $container = $this->service()->__getSettings()->getContainer();

        $map = [];
        
        foreach ($container as $value) {
            $map[$value['name']] = $value['value'];
        }

        return $map;
    }
}