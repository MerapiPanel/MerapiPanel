<?php

namespace il4mb\Mpanel\Core\Utilities\Middleware;

abstract class AwareComponent {

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