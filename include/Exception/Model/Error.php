<?php

namespace MerapiPanel\Exception\Model;

use Exception;
use Throwable;

class Error extends Exception
{

    private function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    function setMessage($message)
    {
        $this->message = $message;
    }
    function setCode(int $code)
    {
        $this->code = $code;
    }
    function setFile($file)
    {
        $this->file = $file;
    }

    function setLine(int $line)
    {
        $this->line = $line;
    }

    public static function caught(Throwable $t, $message = null, $code = null)
    {
        $f = new self($message ?? $t->getMessage(), $code ?? $t->getCode(), $t->getPrevious());
        $f->setFile($t->getFile());
        $f->setLine($t->getLine());
        return $f;
    }
}
