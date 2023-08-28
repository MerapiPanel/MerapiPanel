<?php

namespace il4mb\Mpanel\Core\Module;

use il4mb\Mpanel\Core\Config;
use il4mb\Mpanel\Core\Container;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

final class System
{

    const CONF_FILE = __DIR__ . "/../../config/module.yml";
    public Config $config;

    protected $container;
    protected $modules = [];


    public function __construct()
    {

        $this->config    = new Config(self::CONF_FILE);
        $this->scanModule(__DIR__ . "/../../modules");
    }


    public function setContainer(?Container $container) 
    {
        
        $this->container = $container;
    }




    function scanModule($directory)
    {

        $mods  = [];
        $files = glob($directory . "/**/module*.yml");
        foreach ($files as $file) {


            $file = new File($file);
            $filePath = pathinfo($file->getRealPath(), PATHINFO_DIRNAME);

            $yml = Yaml::parseFile($file);
            $yml['location'] = strpos(PHP_OS, 'WIN') !== false ? str_replace("\\", "/", $filePath) : $filePath;

            $this->modules[] = new ModuleFactory($yml);

            $mods[] = basename($filePath);
        }

        $this->config->set("mods", $mods);
    }



    function saveConfig()
    {

        $this->config->save();
    }
}
