<?php

namespace MerapiQu\Module\Users\Custom;

use MerapiQu\Module\ViewEngine\Abstract\FunctionAbstract;

class Extension extends FunctionAbstract
{


    public function getAvatar($email, $size = 120)
    {


        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=" . $size;


        return $grav_url;
    }
}
