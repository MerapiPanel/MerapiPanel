<?php

namespace il4mb\Mpanel\Modules\Api;

class Service
{

    protected $box;

    public function setBox($box)
    {
        $this->box = $box;
    }

    public function __call($name, $arguments)
    {
        $parts = explode("_", $name);

        $mod     = ucfirst($parts[0]);
        $address = $mod . "\\Api\\Guest";
        $object  = $this->box->$address();
        unset($parts[0]);

        if (!empty($parts)) {

            $method = implode("_", $parts);

            if ($object->isMethodExists($method)) {

                return call_user_func([$object, $method], $arguments);

            } else {

                throw new \Exception("API " . $mod . " doesn't have method " . $method);

            }
        }

        return $object;
    }
}
