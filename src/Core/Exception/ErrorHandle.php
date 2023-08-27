<?php

namespace il4mb\Mpanel\Core\Exception;

use il4mb\Mpanel\Core\Twig\TemplateEngine;
use Throwable;

class ErrorHandle
{

    private static ErrorHandle $instace;
    private bool $locked = false;

    final private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (!isset(self::$instace)) {
            self::$instace = new self();
        }

        return self::$instace;
    }

    public function shutdown()
    {

        $error = error_get_last();

        if ($error === null) {
            return;
        }

        $errorMessage = $error['message'];

        // Extract the error type
        $errorType = '';
        if (strpos($errorMessage, ':') !== false) {
            $errorType = trim(substr($errorMessage, 0, strpos($errorMessage, ':')));
        }

        // Extract the error message
        $errorMessage = trim(substr($errorMessage, strpos($errorMessage, ':') + 1));

        // Extract the stack trace
        $stackTrace = [];
        if (strpos($errorMessage, 'Stack trace:') !== false) {
            $stackTraceSection = substr($errorMessage, strpos($errorMessage, 'Stack trace:') + 12);
            $stackTraceLines = explode("\n", trim($stackTraceSection));
            foreach ($stackTraceLines as $line) {
                $stackTrace[] = trim($line);
            }
        }

        $error = [
            "type" => $errorType,
            "message" => $errorMessage,
            "code" => "999",
            "file" => $error['file'],
            "line" => $error['line'],
            "stack_trace" => $stackTrace
        ];

        $this->view($error);
    }

    function catch_error(Throwable $e)
    {

        $error = [
            "type"        => get_class($e),
            "code"        => $e->getCode(),
            "message"     => $e->getMessage(),
            "file"        => $e->getFile(),
            "line"        => $e->getLine(),
            "stack_trace" => []
        ];

        foreach ($e->getTrace() as $key => $trace) {
            $error['stack_trace'][] = "#" . $key . " " . $trace['file'] . ':' . $trace['line'];
        }

        $this->view($error);
    }


    function view($error)
    {

        if ($this->locked) {
            return;
        }

        $this->locked = true;


        $error['snippet'] = $this->getCodeSnippet($error['file'], $error['line']);

        $template = new TemplateEngine();
        $template->addGlobal('error', $error);

        if($template->templateExists("/error/error$error[code].html.twig")) {

            echo $template->load("/error/error$error[code].html.twig");
            return;

        }

        echo $template->load("/error/error.html.twig");
    }



    function getCodeSnippet($file, $line, $contextLines = 5)
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
