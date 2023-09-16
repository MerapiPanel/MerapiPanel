<?php

namespace Mp\Mod;

use Exception;
use Mp\AppAware;
use Mp\Box;

class Factory extends AppAware
{

    protected Box $box;
    protected $segment;

    public function setBox(Box $box)
    {

        $this->box = $box;
        $this->segment = (string) $this->box->core_segment();
        $this->initController();
    }

    public function getBox(): ?Box
    {
        return $this->box;
    }


    public function initController()
    {

        // Directory where your PHP files are located
        $directory = realpath(__DIR__ . "/../../modules"); // You may need to specify your project's directory here

        // Get a list of all PHP files in the directory
        $phpFiles = glob($directory . '/*');

        $namespacePattern = 'Mp\\Modules\\';
        $controllers = [];

        foreach ($phpFiles as $file) {

            $mod = basename($file);
            $className = $namespacePattern . ucfirst($mod) . "\\Controller\\" . ucfirst($this->segment);

            if (class_exists($className)) {
                $controllers[] = [
                    "name" => $mod,
                    "addr" => $className
                ];
            }
        }

        foreach ($controllers as $controller) {

            $addr = $controller["addr"];
            $name = $controller["name"];
            $modBox =  $this->box->core_boxmod();

            $object = $modBox->$addr();
            $object->register($this->box->utilities_router());
        }
    }
}
