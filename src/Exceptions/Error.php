<?php

namespace il4mb\Mpanel\Exceptions;

use Exception;
use il4mb\Mpanel\Core\EventSystem;
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

        // Trigger the ON_ERROR event.
        EventSystem::getInstance()->fire(self::ON_ERROR, [$this]);
        
    }




    /**
     * Returns the HTML view for the custom exception.
     *
     * @return string The HTML view.
     */
    public function getHtmlView()
    {

        // Define the HTML view for the custom exception
        $view = '<div style="border: 1px solid red; padding: 10px; background-color: #ffebeb; position:fixed; top: 50%; left:50%; transform:translate(-50%, -50%); width: 100%; max-width: 600px;">';
        $view .= '<h2>Error: ' . $this->getMessage() . '</h2>';
        $view .= '<p>Code: ' . $this->getCode() . '</p>';
        $view .= '<p>File: ' . $this->getFile() . '</p>';
        $view .= '<p>Line: ' . $this->getLine() . '</p>';
        $view .= '</div>';

        return $view;
        
    }

}
