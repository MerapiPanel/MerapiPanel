<?php

namespace MerapiPanel\Module\Template\Custom;

use MerapiPanel\Module\ViewEngine\Abstract\FilterAbstract;

class Extension extends FilterAbstract
{


    public function admin_url($path)
    {

        echo $path;
    }
}
