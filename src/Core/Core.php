<?php

namespace MerapiPanel\Core;

class Core {

    public static function __callStatic($name, $arguments)
    {
        $class = '\\MerapiPanel\\Core\\' . ucfirst($name);
    }
}