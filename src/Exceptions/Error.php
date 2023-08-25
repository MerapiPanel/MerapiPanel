<?php

namespace il4mb\Mpanel\Exceptions;

use Exception;
use il4mb\Mpanel\Application;
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
     * @return string The HTML view.
     */
    public function getHtmlView(Application $app)
    {
        $error = [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTrace()
        ];

        $app->get_template()->addGlobal('error', $error);
       return $app->get_template()->render("/error/error.html.twig");
        
    }

}
