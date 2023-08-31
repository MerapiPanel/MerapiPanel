<?php

namespace il4mb\Mpanel\Core\Mod\Segment;

use il4mb\Mpanel\Core\AppBox;

abstract class SegmentAbstract
{

    protected $box;
    abstract function __get($name);
    abstract function __isset($name);


    final function setBox(?AppBox $box)
    {
        $this->box = $box;
    }

    final function getBox(): ?AppBox
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
       // $object = $this->box->$address($arguments);

       $object = $this->getBox()->core_mod_proxy($arguments);


        if (!is_object($object)) {
            throw new \Exception("Error: $address not found");
        }
        
        return call_user_func([$object, $method]);
    }
}
