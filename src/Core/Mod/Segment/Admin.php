<?php

namespace il4mb\Mpanel\Core\Mod\Segment;

class Admin extends SegmentAbstract {

    public function __get($name)
    {

        if ('title' == $name) {
            return 'The title';
        }

       // return "None";

        // throw some kind of error
    }

    public function __isset($name)
    {

        return true;
    }
}