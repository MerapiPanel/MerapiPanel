<?php

namespace MerapiPanel\Exception;

use Exception;
use Throwable;

class Error extends Exception
{

    private static array $stack = [];

    public static function getStack()
    {
        return self::$stack;
    }


    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        self::$stack[] = $this;
    }


    public static function catch(Throwable $e) {

    }
}
