<?php

namespace MerapiPanel\Module\Auth\Middleware;

use MerapiPanel\Utility\Http\Request;
use Closure;
use Exception;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Middleware\Middleware;

class Login implements Middleware
{

    public function handle(Request $request, Closure $next): Response
    {

        // return Response::with("Session Expired")->redirect("/");

        // return new Response([
        //     "status" => 203,
        //     "message" => "Session Expired"
        // ], 203);

        return $next($request);
    }
}
