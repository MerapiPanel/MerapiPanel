<?php

namespace Mp\Module\Panel\Api;

class Guest
{


    function hallo()
    {
        return "Hallo1";
    }

    public function navs()
    {
        return [
            [
                'name' => 'Home',
                'link' => '/'
            ]
        ];
    }
}
