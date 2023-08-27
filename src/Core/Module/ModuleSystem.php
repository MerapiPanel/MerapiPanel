<?php

namespace il4mb\Mpanel\Core\Module;

use il4mb\Mpanel\Core\Config;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

class ModuleSystem
{

    const CONF_FILE = __DIR__ . "/../../config/module.yml";
    public Config $config;

    public function __construct()
    {

        $this->config = new Config(self::CONF_FILE);
        $this->scanModule(__DIR__."/../../modules");
    }


    function scanModule($directory)
    {

        $files = glob($directory . "/**/Service*.php");
        foreach ($files as $file) 
        {

            $file = new File($file);
            $filePath = pathinfo($file->getRealPath(), PATHINFO_DIRNAME);
            $ymlmod = $filePath . "/module.yml";

            if (file_exists($ymlmod)) 
            {

                $yml = Yaml::parseFile($ymlmod);

                $yml['location'] = strpos(PHP_OS, 'WIN') !== false ? str_replace("\\", "/", $filePath) : $filePath;
            }
        }
    }

    function saveConfig()
    {

        $this->config->save();
    }
}
