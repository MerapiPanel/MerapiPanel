<?php

namespace MerapiPanel\Core\Exception;

use MerapiPanel\Core\View\View;

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

class Handler
{


    private $isErrorHandled = false;

    public function __construct()
    {
        register_shutdown_function([$this, 'handleFatalError']);
    }


    /**
     * Description: Handle and display the uncaught fatal error
     */
    public function handleFatalError()
    {

        $error = error_get_last();

        if ($this->isErrorHandled) return;

        $errorString = $error['message'];

        $message    = $this->extractMessageFromString($errorString);
        $stackTrace = $this->extractTracerFromString($errorString, $error['file']);
        $snippet    = $this->getCodeSnippet($error['file'], $error['line']);

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




    /**
     * Description: Handle and display the error
     * @param Throwable $error
     */
    function handle_error(\Throwable $error)
    {
        $this->isErrorHandled = true;
        header("HTTP/1.1 " . $error->getCode() . " " . $error->getMessage());

        $errorData = [
            "file" => $error->getFile(),
            "line" => $error->getLine(),
            "message" => $error->getMessage(),
            "code" => $error->getCode(),
            "type" => get_class($error),
            "stack_trace" => $this->transformTracerFromArray($error->getTrace()),
            "snippet" => $this->getCodeSnippet($error->getFile(), $error->getLine()),
        ];

        echo $this->view($errorData);
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



    private function transformTracerFromArray(array $traceData = [])
    {

        $tracer = [];

        foreach ($traceData as $trace) {
            $tracer[] = [
                "file" => $trace['file'] ?? $trace['class'] ?? '',
                "line" => $trace['line'] ?? null,
                "function" => $trace['function']
            ];
        }

        $reversed = array_reverse($tracer);
        return $reversed;
    }


    private function extractTracerFromString($errorString, $errorFile): array
    {

        preg_match('/Stack trace:(.*)/s', $errorString, $matches);
        $stackTrace = isset($matches[1]) ? array_values(array_filter(array_map(function ($trace) use (&$isEnd) {
            $trace = trim(preg_replace('/^#\d\s+/', '', $trace));
            if ($trace == "{main}" || $trace == "thrown") return "";
            $traceData = $this->splitTraceDataFromString($trace);
            return $traceData;
        }, explode("\n", $matches[1])))) : [];

        $reversed = array_reverse($stackTrace);
        array_pop($reversed);

        $reversed = array_slice($reversed, 0, array_search($errorFile, array_column($reversed, "file")) + 1);

        return $reversed;
    }




    private function splitTraceDataFromString($traceString)
    {
        $matches = [];

        // Extracting file more robustly, accounting for different path formats
        preg_match('/.*\.php|\[(.*)\]/', $traceString, $matches);
        $file = $matches[0] ?? '';

        // Extracting line
        preg_match('/\((\d+)\)/', $traceString, $matches);
        $line = $matches[1] ?? '';

        // Extracting class with improved pattern to handle backslashes in namespaces
        preg_match('/:\s*([^->]+)->/', $traceString, $matches);
        $class = str_replace("\\\\", "\\", $matches[1] ?? '');

        // Extracting method with a pattern that captures method names accurately
        preg_match('/->(\w+)\(/', $traceString, $matches);
        $method = $matches[1] ?? '';

        return [
            "file" => $file,
            "line" => $line,
            "class" => $class,
            "method" => $method
        ];
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
