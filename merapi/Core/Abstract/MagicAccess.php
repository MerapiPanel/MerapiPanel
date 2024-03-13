<?php

namespace MerapiPanel\Core\Abstract;

abstract class MagicAccess {
    

    public abstract function __set($name, $value);

    public abstract function __get($name);

    public abstract function __isset($name);

    public abstract function __unset($name);

}