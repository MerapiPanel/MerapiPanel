<?php

namespace Mp\Modules\Index;

class Service
{

    protected $box;

    function setBox($box)
    {
        $this->box = $box;

        // $api = $this->box->api_index();

        // $detail =  $api->getDetails();

        // print_r($detail);
    }
}
