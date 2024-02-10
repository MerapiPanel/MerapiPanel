<?php

namespace MerapiPanel\Core\Exception;

use Exception;

class MethodNotFoud extends Service
{

    public function __construct(string $message, int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
