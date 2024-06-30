<?php

namespace MerapiPanel\Utility;

use Closure;

class Route
{


    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    protected readonly string $method;
    protected readonly string $path;
    protected readonly string|Closure|array $controller;
    protected array $middlewares = [];



    /**
     * Constructor for the class.
     *
     * @param string $method The HTTP method to be used.
     * @param string $path The path for the request.
     * @param string|Closure|array $controller The controller to be used.
     * @param string|Closure $middleware The middleware to be used.
     */
    public function __construct($method, $path, $controller, $middleware = null)
    {

        $this->method = $method;
        $this->path = $path;
        $this->controller = $controller;
        if ($middleware) {
            $this->middlewares = [$middleware];
        }
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
    public function getController(): string|Closure|array
    {

        return $this->controller;

    }


    public function addMiddleware(string|Closure $middleware)
    {

        $this->middlewares[] = $middleware;
    }


    /**
     * Get the middleware for the route.
     *
     * @return null|string|Closure
     */
    public function getMiddlewares(): array
    {

        return $this->middlewares;
    }

    public function __toString()
    {
        return $this->getPath();
    }

}
