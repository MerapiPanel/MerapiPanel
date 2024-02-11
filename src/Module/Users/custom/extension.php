<?php

namespace MerapiPanel\Module\Users\Custom;

class Extension
{

    public function getAvatar($email, $size = 120)
    {

        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=" . $size;

        return $grav_url;
    }
}
