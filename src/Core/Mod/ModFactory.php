<?php

namespace il4mb\Mpanel\Core\Mod;


use il4mb\Mpanel\Core\Cog\Config;

class ModFactory
{

    const USER   = "user";
    const PUBLIC = "public";

    protected Config $config;
    protected string $directory;
    protected object $mod;
    protected string $className;


    public function __construct()
    {

        $this->initController();
    }

    public function initController()
    {

        // Directory where your PHP files are located
        $directory = realpath(__DIR__ . "/../../modules"); // You may need to specify your project's directory here

        // Get a list of all PHP files in the directory
        $phpFiles = glob($directory . '/*/Controller/*.php');

        $namespacePattern = 'il4mb\\Mpanel\\Modules\\';

        
    }
}
