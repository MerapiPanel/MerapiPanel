<?php

namespace MerapiPanel\Module\Settings\Api;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module {

    function AllTimeZone() {
        return $this->service()->getTimeZones();
    }
}