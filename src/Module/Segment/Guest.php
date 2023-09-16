<?php

namespace Mp\Core\Segment;

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
