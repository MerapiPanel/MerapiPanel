<?php

namespace il4mb\Mpanel\Exceptions;

use il4mb\Mpanel\Twig\TemplateEngine;


class ErrorHandle
{

    private static ErrorHandle $instace;

    final private function __construct(){}

    public static function getInstance(): self
    {
        if (!isset(self::$instace)) {
            self::$instace = new self();
        }

        return self::$instace;
    }

    function shutdown(): callable
    {
        return function () {
            echo $this->view();
        };
    }


    function view()
    {
        $error = error_get_last();

        if ($error === null) {
            return;
        }

        $error = [
            'title'   => "Fatal Error",
            'message' => $error['message'],
            'code'    => 0,
            'file'    => $error['file'],
            'line'    => $error['line'],
            'snippet' => $this->getCodeSnippet($error['file'], $error['line'])
        ];

        $template = new TemplateEngine();
        $template->addGlobal('error', $error);

        return $template->load("/error/error.html.twig");
    }



    function getCodeSnippet($file, $line, $contextLines = 10)
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
