<?php

namespace il4mb\Mpanel\Core\Http;

use Closure;
use il4mb\Mpanel\Core\Http\Middleware;
use il4mb\Mpanel\Core\Http\Request;
use il4mb\Mpanel\Core\Http\Response;

class MiddlewareStack
{
    protected $middlewares;

    public function __construct()
    {
        $this->middlewares = [];
    }

    public function addMiddleware(Middleware $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function handle(Request $request, Closure $default): Response
    {
        $next = $this->createNextClosure($default, count($this->middlewares) - 1);

        return $next($request);
    }

    protected function createNextClosure(Closure $default, int $index): Closure
    {
        return function (Request $request) use ($default, $index) {
            if ($index < 0) {
                return $default($request);
            }

            $middleware = $this->middlewares[$index];
            $next = $this->createNextClosure($default, $index - 1);

            return $middleware->handle($request, $next);
        };
    }
}
