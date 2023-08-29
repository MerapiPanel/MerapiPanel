<?php

namespace il4mb\Mpanel\Core;

use Exception;

class SystemFactory
{

    public function __construct()
    {

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        foreach ($trace as $traceItem) {
            if ($traceItem['class'] == 'Factory') {
                return;
            }
        }
        throw new Exception('Cannot create class ' . static::class . ' outside of factory');
    }
}
