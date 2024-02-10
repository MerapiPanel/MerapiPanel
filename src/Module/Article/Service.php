<?php

namespace MerapiPanel\Module\Content;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;

class Service extends Module
{

    public array $config = [
        "name" => "Content",
        "slug" => "content",
        
    ];

    public function setBox(Box $box)
    {
        error_log("Config: " . json_encode([]));
    }
}
