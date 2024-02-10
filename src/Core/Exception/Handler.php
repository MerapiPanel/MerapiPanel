<?php

namespace MerapiPanel\Core\Exception;

use MerapiPanel\Core\View\View;

class Handler
{


    public function __construct()
    {
        register_shutdown_function([$this, 'handleFatalError']);
    }



    public function handleFatalError()
    {
        $error = error_get_last();

        $errorString = $error['message'];

        $message    = $this->extractMessageFromString($errorString);
        $stackTrace = $this->extractTracerFromString($errorString);

        $snippet = $this->getCodeSnippet($error['file'], $error['line']);

        $type = "Fatal Error";
        if ($this->extractTypeFromString($errorString)) {
            $type = $this->extractTypeFromString($errorString);
        }

        echo $this->view([
            "file" => $error['file'],
            "line" => $error['line'],
            "message" => $message,
            "code" => $error['code'] ?? 0,
            "type" => $type,
            "stack_trace" => $stackTrace,
            "snippet" => $snippet
        ]);
    }




    public function getCss()
    {

        return ".code-snippet { 
            font-family: Menlo, Monaco, Consolas, 'Courier New', monospace;
            font-size: 10px;
            border-spacing: 0;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            width: 200px;
        }
        .code-snippet tr td, .code-snippet tr th {
            word-break: keep-all;
            white-space: pre;
        }
        .code-snippet .line-number {
            text-align: right;
            padding: 0 10px;
        }
        .code-snippet .highlight {
            background-color: #ffff00;
            color: #ff0000;
        }
        ";
    }


    private function extractTypeFromString($errorString)
    {
        preg_match('/^(.*?)\:/i', $errorString, $matches);

        if (isset($matches[1])) {
            return trim(preg_replace('/^Uncaught\s/i', '', $matches[1]));
        }

        return null;
    }


    private function extractMessageFromString($errorString)
    {
        $stackTracePosition = strpos($errorString, 'Stack trace:');
        if ($stackTracePosition !== false) {
            return preg_replace("/^Uncaught\s" . implode("\\\\", explode("\\", $this->extractTypeFromString($errorString) . ':')) . "\s/i", '', str_replace(trim(substr($errorString, $stackTracePosition)), '', $errorString));
        } else {
            return $errorString;
        }
    }


    private function extractTracerFromString($errorString)
    {
        preg_match('/Stack trace:(.*)/s', $errorString, $matches);
        $stackTrace = isset($matches[1]) ? array_values(array_filter(array_map(function ($trace) {

            $trace = trim(preg_replace('/^#\d\s+/', '', $trace));

            if ($trace == "{main}" || $trace == "thrown") return "";

            return $trace;
        }, explode("\n", $matches[1])))) : [];

        $reversed = array_reverse($stackTrace);
        array_pop($reversed);
        return $reversed;
    }


    private function getCodeSnippet(string $file, int $line, int $maxLines = 25)
    {

        if (!file_exists($file)) {
            return 'File not found: ' . $file;
        }

        $half = intval($maxLines / 2);

        $advantage = 0;
        if ($half < ($maxLines / 2)) {
            $advantage += (($maxLines / 2) - $half) * 2;
        }

        $lines = file($file);
        $start = max(0, ($line - $half) - $advantage);
        $end = min(count($lines), $line + $half);

        $snippet = '<table class="code-snippet">';
        for ($i = $start; $i < $end; $i++) {

            $lineNumber = $i + 1;
            $lineContent = htmlspecialchars($lines[$i]);

            $tableRow = '<tr' . ($lineNumber == $line ? ' class="highlight"' : '') . '>';
            $tableRow .= '<td class="line-number">' . $lineNumber . ':</td>';
            $tableRow .= '<td class="line-content">' . $lineContent . '</td>';
            $snippet .= $tableRow . '</tr>';
        }
        $snippet .= '</table>';

        return $snippet;
    }


    function view($error = [
        "file" => "",
        "line" => 0,
        "message" => "",
        "code" => 0,
        "type" => "",
        "stack_trace" => [],
        "snippet" => ""
    ])
    {

        header("Content-Type: text/html;charset=UTF-8");

        $view = View::newInstance([__DIR__ . "/views"]);
        return $view->load("error.html.twig")->render([
            "error" => $error
        ]);
    }
}
