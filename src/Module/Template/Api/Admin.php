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



    public function getStylesheets()
    {

        $scripts =  $this->service()->getInitialScript();
        return $scripts['css'];
    }

    public function getJavascripts()
    {

        $scripts =  $this->service()->getInitialScript();
        return $scripts['js'];
    }
}
