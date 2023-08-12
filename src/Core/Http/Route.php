<?php

namespace il4mb\Mpanel\Core\Http;

use Closure;

class Route
{


    const POST   = 'POST';
    const GET    = 'GET';
    const PUT    = 'PUT';
    const DELETE = 'DELETE';


    protected MiddlewareStack $middlewareStack;


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

        $this->middlewareStack = new MiddlewareStack();
        $this->method          = $method;
        $this->path            = $path;
        $this->controller      = $controller;

    }



    
    /**
     * Adds a middleware to the route.
     *
     * @param Middleware $middleware The middleware to be added.
     * @return Route The updated Route object.
     */
    public function addMiddleware(Middleware $middleware): Route
    {

        $this->middlewareStack->addMiddleware($middleware);

        return $this;

    }


    

    /**
     * Retrieves the middleware stack.
     *
     * @return MiddlewareStack The middleware stack.
     */
    public function getMiddlewareStack(): MiddlewareStack
    {

        return $this->middlewareStack;

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
