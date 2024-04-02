<?php
namespace MerapiPanel\Exception;

use MerapiPanel\Exception\Error;

class Unhandle extends Error
{

    private function __construct($message, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        error_log("[Unhandle] {$message}");
    }


    public static function create($message, $code = 0, \Throwable $previous = null)
    {
        return new self($message, $code, $previous);
    }
}