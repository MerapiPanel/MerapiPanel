<?php

namespace MerapiPanel\Module\Modules;

use MerapiPanel\Core\Abstract\Module;

class Service extends Module
{

    public function getListModule()
    {

        $stack = [];

        foreach (scandir(realpath(__DIR__ . '/..')) as $path) {
            if ($path == '.' || $path == '..') {
                continue;
            }

            $mod = "Module_" . ucfirst($path);
            $module  = $this->getBox()->$mod();
            $meta = [
                'name' => ucfirst($path),
                'description' => "",
                'version' => "",
                'author' => "",
                'license' => "",
                'homepage' => "",
            ];
            if ($module) {
                $meta    = $module->__getMeta();
            }

            $stack[] = $meta;
        }

        return $stack;
    }
}
