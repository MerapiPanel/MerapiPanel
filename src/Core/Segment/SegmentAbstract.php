<?php

namespace il4mb\Mpanel\Core\Segment;

use il4mb\Mpanel\Core\Box;

abstract class SegmentAbstract
{

    protected $box;
    abstract function __get($name);
    abstract function __isset($name);


    final function setBox(?Box $box)
    {
        $this->box = $box;
    }

    final function getBox(): ?Box
    {
        return $this->box;
    }


    final function __call($address, $arguments)
    {

        $segment = ucfirst(basename($this::class));
        $addressParts = explode("_", $address);

        [$addressParts[0], $addressParts[1]] = [$addressParts[1], $addressParts[0]];

        $method = $addressParts[count($addressParts) - 1];
        unset($addressParts[count($addressParts) - 1]);

        $address = "";
        foreach ($addressParts as $key => $value) {
            $address .= "\\" . ucfirst($value);
        }

        $address =  "Modules" . $address . "\\" . $segment;

        if(!class_exists($address))
        {
            throw new \Exception("Error: $address not found");
        }
        $object = $this->box->$address($arguments);

        // $object = $this->getBox()->core_mod_proxy($address);


        if (!is_object($object)) {
            throw new \Exception("Error: $address not found");
        }

        return call_user_func([$object, $method]);
    }
}
