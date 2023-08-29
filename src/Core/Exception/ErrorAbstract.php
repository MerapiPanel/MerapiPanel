<?php

namespace il4mb\Mpanel\Core\Exception;

use Exception;
use il4mb\Mpanel\Core\Container;

abstract class ErrorAbstract extends Exception
{

    protected Container $container;
    protected $type;
    protected $message;
    protected $code;
    protected string $file;
    protected int $line;
    protected array $trace;
    protected string $snippet;


    public function __construct()
    {
    }


    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function getContainer() {
        return $this->container;
    }


    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function setCode(int $code)
    {
        $this->code = $code;
    }


    public function setFile(string $file)
    {
        $this->file = $file;

        if(isset($this->line)) 
        {
            $this->snippet = $this->getCodeSnippet($file, $this->line);
        }
    }

    public function setLine(int $line)
    {
        $this->line = $line;

        if(isset($this->file)) 
        {
            $this->snippet = $this->getCodeSnippet($this->file, $line);
        }
    }

    public function setTrace(array $trace)
    {
        $this->trace = $trace;
    }


    public function getSnippet() {

        return $this->snippet;
    }



    public function toArray() {

        return [
            'type' => $this->getType(),
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTrace()
        ];
    }



    private function getCodeSnippet(string $file, int $line, int $contextLines = 5)
    {

        if (!file_exists($file)) 
        {

            return 'File not found: ' . $file;
        }

        $lines = file($file);
        $start = max(0, $line - $contextLines);
        $end = min(count($lines), $line + $contextLines);

        $snippet = '';
        for ($i = $start; $i < $end; $i++) {
            $lineNumber = $i + 1;
            $lineContent = htmlspecialchars($lines[$i]);
            if ($lineNumber === $line) {
                $snippet .= "<span class='highlight'>{$lineNumber}: {$lineContent}</span>\n";
            } else {
                $snippet .= "{$lineNumber}: {$lineContent}\n";
            }
        }

        return '<pre>' . $snippet . '</pre>';
    }
}
