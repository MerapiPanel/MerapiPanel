<?php

namespace il4mb\Mpanel\Core\Modules;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

class ModuleStack {

    protected $config = [
        "location" => __DIR__,
        "namespace" => "il4mb\\Mpanel\\Modules\\",
    ];


    public function __construct() {

        $files = glob($this->config["location"] . "/*/Service.php");
        foreach ($files as $file) {
            

            $fileSys = new File($file);

            $filePath = $fileSys->getPath();
            
            $ymlmod = $filePath."/module.yml";

            if (file_exists($ymlmod)) {
                
               $yml = Yaml::parseFile($ymlmod);

               $yml['location'] = strpos(PHP_OS, 'WIN') !== false ? str_replace("\\", "/", $filePath) : $filePath;
               //print_r($yml);

               //echo"<hr>";
            }
        }

        //print_r($files);
    }

    
}