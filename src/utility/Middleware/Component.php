<?php

namespace MerapiPanel\Utility\Middleware;


abstract class Component {

    protected MiddlewareStack $middleware;

    public function __construct()
    {
        $this->middleware = new MiddlewareStack();
    }

    public function getMiddlewareStack(): MiddlewareStack
    {
        return $this->middleware;
    }

    public function addMiddleware(Middleware $middleware): void{

        $this->middleware->addMiddleware($middleware);
    }
}