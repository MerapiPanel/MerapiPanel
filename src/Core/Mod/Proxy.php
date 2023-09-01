<?php

namespace il4mb\Mpanel\Core\Mod;

use ReflectionClass;

class Proxy
{


    function __construct($address)
    {
        if (!class_exists($address)) {
            $address .= "il4mb\\Mpanel\\" . $address;
            if (!class_exists($address)) {
                throw new \Exception("Error: $address not found");
            }
        }

        $reflection = new ReflectionClass($address);
    }
}
