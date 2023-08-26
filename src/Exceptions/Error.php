<?php

namespace il4mb\Mpanel\Exceptions;

use Exception;
use il4mb\Mpanel\Application;
use il4mb\Mpanel\Twig\TemplateEngine;
use Throwable;

class Error extends Exception
{

    const ON_ERROR = 'on_error';

    /**
     * Class constructor.
     *
     * @param string $message The error message.
     * @param int $code The error code.
     * @param Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        // Call the parent class constructor.
        parent::__construct($message, $code, $previous);
    }




    /**
     * Returns the HTML view for the custom exception.
     *
     * @return string The HTML view
     */
    public function getHtmlView(Application|null $app = null)
    {
        $error = [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTrace(),
            'snippet' => self::getCodeSnippet($this->getFile(), $this->getLine()),
        ];

        if ($app instanceof Application) {
            /**
             * @var Application $app
             */
            $template = $app->getTemplate();
        } else {

            $template = new TemplateEngine();
        }

        $template->addGlobal('error', $error);
        return $template->load("/error/error.html.twig");
    }

    static function getCodeSnippet($file, $line, $contextLines = 5)
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
