<?php

namespace MerapiPanel\Core\Exception;

use Exception;

class ModuleNotFound extends Error
{

    public function __construct(string $message, int $code = 0, Exception $previous = null)
    {

        parent::__construct($message, $code, $previous);

        foreach (debug_backtrace() as $trace) {
            if (isset($trace['file']) && (realpath($trace['file']) != realpath(__FILE__) && realpath($trace['file']) != realpath($this->getFile()))) {
                $this->setTrace($trace);
                break;
            }
        }
    }
}
