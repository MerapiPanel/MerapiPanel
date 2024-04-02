<?php

namespace MerapiPanel\Core\Exception;

use Exception;
use Throwable;

abstract class Error extends Exception
{


    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


    protected function setTrace(array $trace = []) {

        if(isset($trace['file'])) {
            $this->file = $trace['file'];
        }

        if(isset($trace['line'])) {
            $this->line = $trace['line'];
        }
    }
}
