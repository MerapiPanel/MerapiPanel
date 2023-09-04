<?php

namespace il4mb\Mpanel\Core\Segment;

class Guest extends SegmentAbstract
{

    public function __get($name)
    {

        return $this->$name();
    }

    public function __isset($name)
    {

        return true;
    }
}
