<?php

namespace il4mb\Mpanel\Core\Exception;

use Exception;
use Throwable;

class CodeException extends Exception
{


    function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    function setFile($file)
    {
        $this->file = $file;
    }

    function setLine($line)
    {
        $this->line = $line;
    }

    function setMessage($message)
    {
        $this->message = $message;
    }
}
