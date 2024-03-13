<?php

namespace MerapiPanel\Module\Modules;

use MerapiPanel\BoxModule;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Box;

class Service extends Module
{

    private $stack_module = [];

    public function getListModule()
    {

        if (!empty($this->stack_module)) {
            return $this->stack_module;
        }

        $this->stack_module = [];

        foreach (scandir(realpath(__DIR__ . '/..')) as $path) {

            if ($path == '.' || $path == '..') {
                continue;
            }

            $info = Box::module($path)->getInfo();

            $this->stack_module[] = $info;
        }

        return $this->stack_module;
    }
}
