<?php

namespace Mp\Exception;
use Exception;
use Mp\Box;
use Throwable;

abstract class ErrorAbstract extends Exception
{

    protected Box $box;
    protected $type;
    protected $message;
    protected $code;
    protected string $file;
    protected int $line;
    protected array $stack_trace;
    protected string $snippet;


    public function __construct()
    {
        
        // error_reporting(0);
        register_shutdown_function([$this, "shutdown"]);
    }


    abstract public function catch_error(Throwable $e);
    abstract public function view();
    abstract public function shutdown();


    final public function setBox(Box $box)
    {
        $this->box = $box;
    }

    public function getBox()
    {
        return $this->box;
    }


    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getType()
    {
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

        if (isset($this->line)) {
            $this->snippet = $this->getCodeSnippet($file, $this->line);
        }
    }

    public function setLine(int $line)
    {
        $this->line = $line;

        if (isset($this->file)) {
            $this->snippet = $this->getCodeSnippet($this->file, $line);
        }
    }

    public function setStackTrace(array $stack_trace)
    {

        $this->stack_trace = [];
        foreach ($stack_trace as $key => $trace) {
            if (gettype($trace) == "string") {
                $this->stack_trace = $stack_trace;
                break;
            }
            $this->stack_trace[] = "#" . $key . " " . (isset($trace['file']) ? $trace['file'] : $trace['function']) . ':' . (isset($trace['line']) ? $trace['line'] : "()");
        }
    }

    function getStackTrace()
    {
        return $this->stack_trace;
    }


    public function getSnippet()
    {

        return $this->snippet;
    }



    public function toArray()
    {

        return [
            'type' => $this->getType(),
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'stack_trace' => $this->getStackTrace()
        ];
    }



    private function getCodeSnippet(string $file, int $line, int $contextLines = 5)
    {

        if (!file_exists($file)) {

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
