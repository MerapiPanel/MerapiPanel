<?php

namespace MerapiPanel\Module\Articel;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;

class Service extends Module
{


    public array $config = [
        "name" => "Article",
        "slug" => "article",
        
    ];

    public function setBox(Box $box)
    {
        error_log("Config: " . json_encode([]));
    }
}
