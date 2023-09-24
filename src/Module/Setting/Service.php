<?php

namespace MerapiPanel\Module\Setting;

use MerapiPanel\Core\Abstract\Module;

class Service extends Module
{


    public function getTimeZones()
    {
        $raw = file_get_contents(__DIR__ . "/assets/timezone.json");
        return json_decode($raw, true);
    }
}
