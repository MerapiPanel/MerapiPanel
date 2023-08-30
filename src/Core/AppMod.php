<?php

namespace il4mb\Mpanel\Core;

class AppMod
{

    function __construct($command)
    {

        $commands = explode("_", $command);
        $segment  = ucfirst($commands[0]);
        $service  = ucfirst($commands[1]);
        $modName  = isset($commands[2]) ? ucfirst($commands[2]) : null;
    }
}
