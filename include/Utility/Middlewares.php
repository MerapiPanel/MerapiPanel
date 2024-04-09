<?php

namespace MerapiPanel\Utility;

use Closure;
use MerapiPanel\Box;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;

class Middlewares
{
    protected readonly array $middlewares;

    /**
     * Constructor for the Middlewares.
     *
     * @param array $middlewares The array of middlewares.
     */
    public function __construct(array $middlewares = [])
    {
        $initializeMiddleware = [];
        foreach ($middlewares as $middleware) {
            if (is_string($middleware)) {
                $module = Box::module(ucfirst($middleware))->Controller->Middleware;
                $initializeMiddleware[] = $module;
            } elseif (is_callable($middleware)) {
                $initializeMiddleware[] = $middleware;
            }
        }
        $this->middlewares = $initializeMiddleware;
    }



    /**
     * A description of the entire PHP function.
     *
     * @param Request $request description
     * @param Closure $default default request handler
     * @return Response
     */
    public function handle(Request $request, Closure $default): Response
    {
        $next = $this->createNextClosure($default, count($this->middlewares) - 1);
        return $next($request);
    }




    /**
     * Creates the next closure for handling middleware.
     *
     * @param Closure $default The default closure
     * @param int $index The index of the middleware
     * @return Closure
     */
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
