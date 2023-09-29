<?php

namespace MerapiPanel\Module\Template\Api;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    function all_template()
    {

        $service = $this->service();
        return $service->getAllTemplate();
    }
}
