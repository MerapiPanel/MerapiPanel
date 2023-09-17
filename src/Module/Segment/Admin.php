<?php

namespace Mp\Module\Segment;

class Admin extends SegmentAbstract {

    public function __get($name)
    {

        if ('title' == $name) {
            return 'The title';
        }
    }

    public function __isset($name)
    {

        return true;
    }
}