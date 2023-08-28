<?php

namespace il4mb\Mpanel\Core\Module;

use il4mb\Mpanel\Core\Config;

final class ModuleFactory
{

    protected Config $config;
    protected string $directory;


    public function __construct($yml = []) 
    {

        if(!isset($yml['location'])){
            throw new \Exception('Module location is not set');
        }

        $this->config = Config::fromArray($yml);

    }

}