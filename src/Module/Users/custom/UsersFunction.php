<?php

namespace MerapiPanel\Module\Users\Custom;

use MerapiPanel\Core\View\Abstract\ViewFunction;

class UsersFunction extends ViewFunction
{

    public function getAvatar($email, $size = 120)
    {

        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=" . $size;

        return $grav_url;
    }
}
