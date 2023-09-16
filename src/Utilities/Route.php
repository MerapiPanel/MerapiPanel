<?php

namespace Mp\Utility;

use Closure;
use Mp\Utility\Middleware\AwareComponent;

class Route extends AwareComponent
{


    const POST   = 'POST';
    const GET    = 'GET';
    const PUT    = 'PUT';
    const DELETE = 'DELETE';

    protected string $method;
    protected string $path;
    protected string|Closure $controller;

    

    /**
     * Constructor for the class.
     *
     * @param string $method The HTTP method to be used.
     * @param string $path The path for the request.
     * @param string|Closure $controller The controller to be used.
     */
    public function __construct($method, $path, $controller)
    {

        parent::__construct();

        $this->method          = $method;
        $this->path            = $path;
        $this->controller      = $controller;

    }


    /**
     * Retrieves the HTTP method used in the request.
     *
     * @return string The HTTP method used in the request.
     */
    public function getMethod(): string
    {

        return $this->method;

    }



    
    /**
     * Get the path.
     *
     * @return string The path.
     */
    public function getPath(): string
    {

        return $this->path;
        
    }



    
    /**
     * Retrieves the controller.
     *
     * @return string|Closure The controller.
     */
    public function getController(): string|Closure
    {

        return $this->controller;

    }

}
