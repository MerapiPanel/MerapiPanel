<?php

namespace il4mb\Mpanel\Core\Module;

use il4mb\Mpanel\Core\AppBox;
use il4mb\Mpanel\Core\Cog\Config;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

final class System 
{

    const CONF_FILE = __DIR__ . "/../../config/module.yml";
    public Config $config;

    protected $box;
    protected $modules = [];


    public function __construct()
    {

        $this->config    = new Config(self::CONF_FILE);

    }


    public function setBox(?AppBox $box) 
    {
        
        $this->box = $box;
        $this->scanModule(__DIR__ . "/../../modules");
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

            // $this->modules[] = new ModFactory($yml);

            $this->box->register(ModFactory::class, $yml);

            $mods[] = basename($filePath);
        }

        $this->config->set("mods", $mods);
    }



    function saveConfig()
    {

        $this->config->save();
    }
}